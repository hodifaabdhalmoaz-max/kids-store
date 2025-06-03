<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Color;
use App\Models\Size;
use App\Models\Review;
use App\Services\StatisticService;
use App\Services\AuditService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    protected $statisticService;
    protected $auditService;

    public function __construct(StatisticService $statisticService, AuditService $auditService)
    {
        $this->statisticService = $statisticService;
        $this->auditService = $auditService;
    }

    /**
     * Display a listing of the products.
     */
    public function index(Request $request)
    {
        $query = Product::query();

        // Apply category filter
        if ($request->has('category')) {
            $category = Category::where('slug', $request->category)->first();
            if ($category) {
                $query->where('category_id', $category->id);
            }
        }

        // Apply brand filter
        if ($request->has('brand')) {
            $brand = Brand::where('slug', $request->brand)->first();
            if ($brand) {
                $query->where('brand_id', $brand->id);
            }
        }

        // Apply color filter
        if ($request->has('color')) {
            $color = Color::where('code', $request->color)->first();
            if ($color) {
                $query->whereHas('colors', function($q) use ($color) {
                    $q->where('color_id', $color->id);
                });
            }
        }

        // Apply size filter
        if ($request->has('size')) {
            $size = Size::where('code', $request->size)->first();
            if ($size) {
                $query->whereHas('sizes', function($q) use ($size) {
                    $q->where('size_id', $size->id);
                });
            }
        }

        // Apply price range filter
        if ($request->has('min_price') && $request->has('max_price')) {
            $query->where(function($q) use ($request) {
                $q->whereBetween('regular_price', [$request->min_price, $request->max_price])
                  ->orWhereBetween('sale_price', [$request->min_price, $request->max_price]);
            });
        }

        // Apply search filter
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('short_description', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('SKU', 'like', "%{$search}%");
            });

            // Log search statistic with results count
            $resultsCount = $query->count();
            $this->statisticService->logSearch($search, $resultsCount);

        // Also log to audit trail
        $this->auditService->log('search', [
            'query' => $search,
            'results_count' => $resultsCount
        ]);
        }

        // Apply sorting
        $sortBy = $request->sort_by ?? 'created_at';
        $sortDirection = $request->sort_direction ?? 'desc';
        $query->orderBy($sortBy, $sortDirection);

        // Get paginated results
        $perPage = $request->per_page ?? 12;
        $products = $query->paginate($perPage)->appends($request->query());

        // Get filters for sidebar
        $categories = Category::orderBy('name')->get();
        $brands = Brand::orderBy('name')->get();
        $colors = Color::active()->ordered()->get();
        $sizes = Size::active()->ordered()->get();

        return view('shop', compact('products', 'categories', 'brands', 'colors', 'sizes'));
    }

    /**
     * Display the specified product.
     */
    public function show($slug)
    {
        $product = Product::where('slug', $slug)->firstOrFail();

        // Log product view statistic
        $this->statisticService->logProductView($product->id);

        // Also log to audit trail
        $this->auditService->log('product_view', [
            'product_id' => $product->id,
            'product_name' => $product->name,
            'product_price' => $product->current_price
        ]);

        // Get related products
        $relatedProducts = $product->relatedProducts()->take(4)->get();
        if ($relatedProducts->count() < 4) {
            // If not enough related products, get products from same category
            $categoryProducts = Product::where('category_id', $product->category_id)
                ->where('id', '!=', $product->id)
                ->take(4 - $relatedProducts->count())
                ->get();
            $relatedProducts = $relatedProducts->merge($categoryProducts);
        }

        // Get product reviews
        $reviews = $product->reviews()->active()->latest()->get();
        $avgRating = $reviews->avg('rating');

        return view('details', compact('product', 'relatedProducts', 'reviews', 'avgRating'));
    }

    /**
     * Store a review for the specified product.
     */
    public function storeReview(Request $request, $productId)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'title' => 'required|string|max:255',
            'comment' => 'required|string',
        ]);

        $product = Product::findOrFail($productId);

        $review = new Review([
            'product_id' => $product->id,
            'user_id' => Auth::id(),
            'rating' => $request->rating,
            'title' => $request->title,
            'comment' => $request->comment,
            'status' => true, // Auto-approve reviews for now
        ]);

        $review->save();

        // Log review creation
        $this->auditService->log('review_added', [
            'product_id' => $product->id,
            'product_name' => $product->name,
            'rating' => $request->rating,
            'review_id' => $review->id
        ]);

        return redirect()->back()->with('success', __('messages.review_added'));
    }

    /**
     * Search for products.
     */
    public function search(Request $request)
    {
        $search = $request->search;

        $products = Product::where('name', 'like', "%{$search}%")
            ->orWhere('short_description', 'like', "%{$search}%")
            ->orWhere('description', 'like', "%{$search}%")
            ->orWhere('SKU', 'like', "%{$search}%")
            ->paginate(12);

        // Log search statistic with results count
        $resultsCount = $products->total();
        $this->statisticService->logSearch($search, $resultsCount);

        // Also log to audit trail
        $this->auditService->log('search_page', [
            'query' => $search,
            'results_count' => $resultsCount
        ]);

        return view('search', compact('products', 'search'));
    }

    /**
     * Display products by category.
     */
    public function category($slug)
    {
        $category = Category::where('slug', $slug)->firstOrFail();

        $products = Product::where('category_id', $category->id)
            ->paginate(12);

        // Log category view statistic
        $this->statisticService->logCategoryView($category->id);

        // Also log to audit trail
        $this->auditService->log('category_view', [
            'category_id' => $category->id,
            'category_name' => $category->name,
            'products_count' => $products->total()
        ]);

        return view('category', compact('category', 'products'));
    }

    /**
     * Display products by brand.
     */
    public function brand($slug)
    {
        $brand = Brand::where('slug', $slug)->firstOrFail();

        $products = Product::where('brand_id', $brand->id)
            ->paginate(12);

        // Log brand view statistic
        $this->auditService->log('brand_view', [
            'brand_id' => $brand->id,
            'brand_name' => $brand->name,
            'products_count' => $products->total()
        ]);

        return view('brand', compact('brand', 'products'));
    }
}
