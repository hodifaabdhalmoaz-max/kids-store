<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index(Request $request)
    {
        // دعم الطريقة القديمة والجديدة
        $size = $request->query('size') ?: $request->query('per_page', 12);
        $o_column = "";
        $o_order = "";
        $order = $request->query('order', -1);
        $f_brands = $request->query('brands', '');
        $f_categories = $request->query('categories', '');
        $min_price = $request->query('min') ?: $request->query('min_price', 1);
        $max_price = $request->query('max') ?: $request->query('max_price', 500);

        switch($order)
        {
            case 1:
                $o_column = "created_at";
                $o_order = "DESC";
                break;
            case 2:
                $o_column = "created_at";
                $o_order = "ASC";
                break;
            case 3:
                $o_column = "sale_price";
                $o_order = "ASC";
                break;
            case 4:
                $o_column = "sale_price";
                $o_order = "DESC";
                break;
            default:
                $o_column = "id";
                $o_order = "DESC";
        }

        // الحصول على فلاتر البحث
        try {
            $searchService = app(\App\Services\SearchService::class);
            $searchFilters = $searchService->getSearchFilters();

            $brands = $searchFilters['brands'] ?? collect();
            $categories = $searchFilters['categories'] ?? collect();
            $colors = $searchFilters['colors'] ?? collect();
            $sizes = $searchFilters['sizes'] ?? collect();
        } catch (\Exception $e) {
            // في حالة فشل SearchService، استخدم البيانات مباشرة
            $brands = \App\Models\Brand::orderBy('name', 'ASC')->get();
            $categories = \App\Models\Category::orderBy('name', 'ASC')->get();
            $colors = \App\Models\Color::orderBy('name', 'ASC')->get();
            $sizes = \App\Models\Size::orderBy('order', 'ASC')->get();
        }

        $products = Product::where(function($query) use($f_brands){
            if (!empty($f_brands)) {
                $query->whereIn('brand_id', explode(',', $f_brands));
            }
        })
        ->where(function($query) use($f_categories){
            if (!empty($f_categories)) {
                $query->whereIn('category_id', explode(',', $f_categories));
            }
        })
        ->where(function($query) use($min_price,$max_price){
            $query->whereBetween('regular_price',[$min_price,$max_price])
            ->orWhereBetween('sale_price',[$min_price,$max_price]);
        })
        ->orderBy($o_column,$o_order)->paginate($size);

        return view('shop', compact(
            'products', 'size', 'order', 'brands', 'f_brands',
            'categories', 'f_categories', 'min_price', 'max_price',
            'colors', 'sizes'
        ));
    }

    //Details
    public function product_details($product_slug)
    {
        $product = Product::where('slug', $product_slug)->first();
        $rproducts = Product::where('slug', '<>',$product_slug)->get()->take(8);
        return view('details', compact('product', 'rproducts'));
    }

    // البحث المتقدم والذكي
    public function search(Request $request)
    {
        // التحقق من صحة البيانات المدخلة
        $request->validate([
            'search' => 'nullable|string|max:255',
            'category' => 'nullable|string|max:100',
            'brand' => 'nullable|string|max:100',
            'min_price' => 'nullable|numeric|min:0',
            'max_price' => 'nullable|numeric|min:0',
            'colors' => 'nullable|string',
            'sizes' => 'nullable|string',
            'sort_by' => 'nullable|string|in:relevance,price_low_high,price_high_low,newest,oldest,name_a_z,name_z_a',
            'per_page' => 'nullable|integer|min:6|max:48'
        ]);

        // إعداد معاملات البحث
        $searchParams = [
            'search' => $request->query('search'),
            'category' => $request->query('category'),
            'brand' => $request->query('brand'),
            'min_price' => $request->query('min_price'),
            'max_price' => $request->query('max_price'),
            'colors' => $request->query('colors'),
            'sizes' => $request->query('sizes'),
            'stock_status' => $request->query('stock_status'),
            'featured' => $request->query('featured'),
            'sort_by' => $request->query('sort_by', 'relevance'),
            'sort_direction' => $request->query('sort_direction', 'desc'),
            'per_page' => $request->query('per_page', 12)
        ];

        // تنفيذ البحث باستخدام SearchService
        $searchService = app(\App\Services\SearchService::class);
        $products = $searchService->searchProducts($searchParams);

        // الحصول على فلاتر البحث
        $searchFilters = $searchService->getSearchFilters();

        // الحصول على الاقتراحات الشائعة
        $popularTerms = $searchService->getPopularSearchTerms(5);

        return view('shop', [
            'products' => $products,
            'brands' => $searchFilters['brands'],
            'categories' => $searchFilters['categories'],
            'colors' => $searchFilters['colors'],
            'sizes' => $searchFilters['sizes'],
            'priceRanges' => $searchFilters['price_ranges'],
            'search' => $request->query('search'),
            'currentFilters' => $searchParams,
            'popularTerms' => $popularTerms,
            'f_categories' => $request->query('categories', ''),
            'f_brands' => $request->query('brands', ''),
            'size' => $request->query('per_page', 12),
            'order' => $request->query('sort_by', -1),
            'min_price' => $request->query('min_price', 1),
            'max_price' => $request->query('max_price', 500)
        ]);
    }

    /**
     * البحث السريع مع الاقتراحات التلقائية (AJAX)
     */
    public function quickSearch(Request $request)
    {
        $request->validate([
            'q' => 'required|string|min:2|max:100'
        ]);

        $searchService = app(\App\Services\SearchService::class);
        $results = $searchService->quickSearch($request->q, 8);

        return response()->json([
            'success' => true,
            'data' => $results
        ]);
    }

    // Category
    public function category($slug)
    {
        $category = Category::where('slug', $slug)->firstOrFail();
        $size = request()->query('size') ? request()->query('size') : 12;

        $products = Product::where('category_id', $category->id)->paginate($size);
        $brands = Brand::orderBy('name', 'ASC')->get();
        $categories = Category::orderBy('name','ASC')->get();

        return view('shop', compact('products', 'brands', 'categories', 'category'));
    }

    // Brand
    public function brand($slug)
    {
        $brand = Brand::where('slug', $slug)->firstOrFail();
        $size = request()->query('size') ? request()->query('size') : 12;

        $products = Product::where('brand_id', $brand->id)->paginate($size);
        $brands = Brand::orderBy('name', 'ASC')->get();
        $categories = Category::orderBy('name','ASC')->get();

        return view('shop', compact('products', 'brands', 'categories', 'brand'));
    }
}
