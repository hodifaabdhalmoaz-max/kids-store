<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Services\StatisticService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    protected $statisticService;

    public function __construct(StatisticService $statisticService)
    {
        $this->statisticService = $statisticService;
    }

    /**
     * Display a listing of the products.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        // Create cache key based on request parameters
        $cacheKey = 'products_' . md5(json_encode($request->all()));
        
        // Try to get from cache first (5 minutes)
        return Cache::remember($cacheKey, 300, function () use ($request) {
            $query = Product::with(['category', 'brand']);
            
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
                
                // Log search statistic
                $resultsCount = $query->count();
                $this->statisticService->logSearch($search, $resultsCount);
            }
            
            // Apply sorting
            $sortBy = $request->sort_by ?? 'created_at';
            $sortDirection = $request->sort_direction ?? 'desc';
            
            // Validate sort parameters to prevent SQL injection
            $allowedSortFields = ['name', 'created_at', 'regular_price', 'sale_price'];
            $allowedSortDirections = ['asc', 'desc'];
            
            if (!in_array($sortBy, $allowedSortFields)) {
                $sortBy = 'created_at';
            }
            
            if (!in_array($sortDirection, $allowedSortDirections)) {
                $sortDirection = 'desc';
            }
            
            $query->orderBy($sortBy, $sortDirection);
            
            // Get paginated results
            $perPage = $request->per_page ?? 12;
            if ($perPage <= 0 || $perPage > 100) {
                $perPage = 12;
            }
            
            $products = $query->paginate($perPage);
            
            return response()->json([
                'success' => true,
                'data' => $products,
            ]);
        });
    }

    /**
     * Display the specified product.
     *
     * @param  string  $slug
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($slug)
    {
        // Create cache key
        $cacheKey = 'product_' . $slug;
        
        // Try to get from cache first (10 minutes)
        return Cache::remember($cacheKey, 600, function () use ($slug) {
            $product = Product::with(['category', 'brand', 'colors', 'sizes', 'reviews.user'])
                ->where('slug', $slug)
                ->first();
            
            if (!$product) {
                return response()->json([
                    'success' => false,
                    'message' => 'Product not found',
                ], 404);
            }
            
            // Log product view statistic
            $this->statisticService->logProductView($product->id);
            
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
            
            return response()->json([
                'success' => true,
                'data' => [
                    'product' => $product,
                    'related_products' => $relatedProducts,
                    'avg_rating' => $product->reviews->avg('rating') ?? 0,
                ],
            ]);
        });
    }

    /**
     * Store a review for the specified product.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $productId
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeReview(Request $request, $productId)
    {
        $validator = Validator::make($request->all(), [
            'rating' => 'required|integer|min:1|max:5',
            'title' => 'required|string|max:255',
            'comment' => 'required|string',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }
        
        $product = Product::find($productId);
        
        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found',
            ], 404);
        }
        
        $review = $product->reviews()->create([
            'user_id' => auth()->id(),
            'rating' => $request->rating,
            'title' => $request->title,
            'comment' => $request->comment,
            'status' => true, // Auto-approve reviews for now
        ]);
        
        // Clear product cache
        Cache::forget('product_' . $product->slug);
        
        return response()->json([
            'success' => true,
            'data' => $review,
            'message' => 'Review added successfully',
        ]);
    }

    /**
     * Get featured products.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function featured()
    {
        // Create cache key
        $cacheKey = 'products_featured';
        
        // Try to get from cache first (30 minutes)
        return Cache::remember($cacheKey, 1800, function () {
            $products = Product::with(['category', 'brand'])
                ->where('featured', true)
                ->where('stock_status', 'instock')
                ->take(8)
                ->get();
            
            return response()->json([
                'success' => true,
                'data' => $products,
            ]);
        });
    }

    /**
     * Get new arrivals.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function newArrivals()
    {
        // Create cache key
        $cacheKey = 'products_new_arrivals';
        
        // Try to get from cache first (30 minutes)
        return Cache::remember($cacheKey, 1800, function () {
            $products = Product::with(['category', 'brand'])
                ->where('stock_status', 'instock')
                ->orderBy('created_at', 'desc')
                ->take(8)
                ->get();
            
            return response()->json([
                'success' => true,
                'data' => $products,
            ]);
        });
    }

    /**
     * Get products by category.
     *
     * @param  string  $slug
     * @return \Illuminate\Http\JsonResponse
     */
    public function byCategory($slug)
    {
        // Create cache key
        $cacheKey = 'products_category_' . $slug;
        
        // Try to get from cache first (10 minutes)
        return Cache::remember($cacheKey, 600, function () use ($slug) {
            $category = Category::where('slug', $slug)->first();
            
            if (!$category) {
                return response()->json([
                    'success' => false,
                    'message' => 'Category not found',
                ], 404);
            }
            
            $products = Product::with(['brand'])
                ->where('category_id', $category->id)
                ->paginate(12);
            
            // Log category view statistic
            $this->statisticService->logCategoryView($category->id);
            
            return response()->json([
                'success' => true,
                'data' => [
                    'category' => $category,
                    'products' => $products,
                ],
            ]);
        });
    }
}
