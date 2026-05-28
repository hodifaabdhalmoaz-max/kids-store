<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Services\SearchService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

/**
 * ShopController — handles all storefront browsing, search, and category views.
 *
 * Uses SearchService as a single source of truth for querying products.
 * Sorting/filter parameter resolution is centralised via resolveSortParams().
 */
class ShopController extends Controller
{
    private SearchService $search;

    public function __construct(SearchService $search)
    {
        $this->search = $search;
    }

    /* ──────────────────────────────────────────
     |  SHARED PARAM RESOLVER (DRY)
     * ────────────────────────────────────────── */

    /**
     * Normalise sort/filter params from the request into a
     * canonical format understood by SearchService.
     *
     * Handles both legacy numeric order values and the new string-based ones.
     */
    private function resolveSortParams(Request $request, array $params): array
    {
        $params['per_page'] = $request->query('size') ?: $request->query('per_page', 12);

        // Accept 'order' from sort modal, or 'sort_by' directly
        $sortBy  = $request->query('order') ?: $request->query('sort_by', 'newest');
        $sortDir = $request->query('order_dir') ?: $request->query('sort_direction', 'desc');

        // Legacy numeric mapping (backwards compatibility)
        if (is_numeric($sortBy)) {
            $sortBy = [1 => 'newest', 2 => 'oldest', 3 => 'price', 4 => 'price'][$sortBy] ?? 'newest';
            if ($sortBy === 'price') {
                $sortDir = ($sortBy == 3) ? 'asc' : 'desc';
            }
        }

        $params['sort_by']        = $sortBy;
        $params['sort_direction'] = $sortDir;

        return $params;
    }

    /**
     * Build the shared view data for sort/filter modals.
     */
    private function buildViewData(Request $request, $products, array $extra = []): array
    {
        $filters = $this->search->getSearchFilters();

        return array_merge([
            'products'  => $products,
            'brands'    => $filters['brands'],
            'categories'=> $filters['categories'],
            'colors'    => $filters['colors'],
            'sizes'     => $filters['sizes'],
            'min_price' => $request->query('min_price', 0),
            'max_price' => $request->query('max_price', 500000),
            'f_brands'  => $request->query('brands', ''),
            'f_categories' => $request->query('categories', ''),
            'size'      => $request->query('size', 12),
            'order'     => $request->query('order', 'newest'),
            'order_dir' => $request->query('order_dir', 'desc'),
        ], $extra);
    }

    /* ──────────────────────────────────────────
     |  SHOP INDEX
     * ────────────────────────────────────────── */

    public function index(Request $request)
    {
        $params   = $this->resolveSortParams($request, $request->all());
        $products = $this->search->searchProducts($params);

        return view('shop', $this->buildViewData($request, $products));
    }

    /* ──────────────────────────────────────────
     |  PRODUCT DETAILS
     * ────────────────────────────────────────── */

    public function product_details(string $product_slug)
    {
        $product = Product::with([
            'brand', 'category',
            'reviews' => fn($q) => $q->where('status', true)->with('user')->latest(),
        ])->where('slug', $product_slug)->firstOrFail();

        $rproducts = Product::where('slug', '<>', $product_slug)
            ->where('category_id', $product->category_id)
            ->select(['id', 'name', 'slug', 'short_description', 'regular_price', 'sale_price', 'image', 'images', 'category_id', 'brand_id'])
            ->withCount(['reviews as active_reviews_count' => fn($q) => $q->where('status', true)])
            ->withAvg(['reviews as active_reviews_avg' => fn($q) => $q->where('status', true)], 'rating')
            ->inRandomOrder()
            ->limit(8)
            ->get();

        return view('details', compact('product', 'rproducts'));
    }

    /* ──────────────────────────────────────────
     |  SEARCH
     * ────────────────────────────────────────── */

    public function search(Request $request)
    {
        $params = $this->resolveSortParams($request, $request->all());

        // Dynamic tab filtering
        if ($request->has('tab') && $request->tab !== 'all') {
            $params['search'] = trim(($request->search ?? '') . ' ' . $request->tab);
        }

        $products    = $this->search->searchProducts($params);
        $searchQuery = $request->query('search');

        // Generate dynamic tabs from matching product names
        $tabs = collect();
        if ($searchQuery) {
            $tabs = Product::where('name', 'LIKE', "%{$searchQuery}%")
                ->pluck('name')
                ->map(fn($name) => explode(' ', trim($name))[0])
                ->unique()
                ->filter(fn($word) => mb_strlen($word) > 2)
                ->take(10)
                ->values();
        }

        return view('search-results', $this->buildViewData($request, $products, [
            'tabs'         => $tabs,
            'current_tab'  => $request->query('tab', 'all'),
            'search_query' => $searchQuery,
        ]));
    }

    /**
     * البحث السريع مع الاقتراحات التلقائية (AJAX)
     */
    public function quickSearch(Request $request)
    {
        $request->validate(['q' => 'required|string|min:2|max:100']);

        return response()->json([
            'success' => true,
            'data'    => $this->search->quickSearch($request->q, 8),
        ]);
    }

    /* ──────────────────────────────────────────
     |  CATEGORIES
     * ────────────────────────────────────────── */

    public function categories()
    {
        $categories = Cache::remember('shop_all_categories', 1800, fn() =>
            Category::select('id', 'name', 'slug', 'image')->orderBy('name')->get()
        );
        return view('categories', compact('categories'));
    }

    /**
     * Category — specific shop view with dynamic tabs.
     */
    public function category(Request $request, string $slug)
    {
        $category = Category::where('slug', $slug)->firstOrFail();

        $params = $this->resolveSortParams($request, $request->all());
        $params['categories'] = $category->id;

        // Dynamic tab filtering
        if ($request->has('tab') && $request->tab !== 'all') {
            $params['search'] = $request->tab;
        }

        $products = $this->search->searchProducts($params);

        // Dynamic tabs from category product names
        $tabs = Product::where('category_id', $category->id)
            ->pluck('name')
            ->map(fn($name) => explode(' ', trim($name))[0])
            ->unique()
            ->filter()
            ->take(10)
            ->values();

        return view('category-shop', $this->buildViewData($request, $products, [
            'category'    => $category,
            'tabs'        => $tabs,
            'current_tab' => $request->query('tab', 'all'),
        ]));
    }

    /* ──────────────────────────────────────────
     |  OFFERS
     * ────────────────────────────────────────── */

    public function offers(Request $request)
    {
        $params = $this->resolveSortParams($request, $request->all());
        $params['is_offer'] = 1;

        // Dynamic tab filtering for categories
        if ($request->has('tab') && $request->tab !== 'all') {
            $category = Category::where('slug', $request->tab)->first();
            if ($category) {
                $params['categories'] = $category->id;
            }
        }

        $products = $this->search->searchProducts($params);

        // Get categories for tabs that have offer products
        $tabs = Category::whereHas('products', function($query) {
            $query->where('is_offer', 1);
        })->select('name', 'slug')->get();

        return view('offers', $this->buildViewData($request, $products, [
            'tabs'        => $tabs,
            'current_tab' => $request->query('tab', 'all'),
        ]));
    }

    /* ──────────────────────────────────────────
     |  BRAND
     * ────────────────────────────────────────── */

    public function brand(string $slug)
    {
        $brand    = Brand::where('slug', $slug)->firstOrFail();
        $perPage  = request()->query('size', 12);

        $products = Product::with(['brand', 'category'])
            ->where('brand_id', $brand->id)
            ->paginate($perPage);

        $brands     = Cache::remember('shop_brands', 600, fn() => Brand::orderBy('name')->get());
        $categories = Cache::remember('shop_categories', 600, fn() => Category::orderBy('name')->get());

        return view('shop', compact('products', 'brands', 'categories', 'brand'));
    }
}
