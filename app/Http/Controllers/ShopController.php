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
        $searchService = app(\App\Services\SearchService::class);

        // إعداد المعاملات
        $params = $request->all();
        $params['per_page'] = $request->query('size') ?: $request->query('per_page', 12);
        $params['sort_by'] = $request->query('order') ?: $request->query('sort_by', 'relevance');

        // تحويل أرقام الترتيب القديمة إلى مسميات البحث الجديد
        if (is_numeric($params['sort_by'])) {
            $mapping = [
                1 => 'newest',
                2 => 'oldest',
                3 => 'price_low_high',
                4 => 'price_high_low'
            ];
            $params['sort_by'] = $mapping[$params['sort_by']] ?? 'relevance';
        }

        // جلب المنتجات
        $products = $searchService->searchProducts($params);

        // جلب الفلاتر
        $filters = $searchService->getSearchFilters();

        return view('shop', [
            'products' => $products,
            'brands' => $filters['brands'],
            'categories' => $filters['categories'],
            'colors' => $filters['colors'],
            'sizes' => $filters['sizes'],
            'min_price' => $request->query('min_price') ?: $request->query('min', 1),
            'max_price' => $request->query('max_price') ?: $request->query('max', 500),
            'f_brands' => $request->query('brands', ''),
            'f_categories' => $request->query('categories', ''),
            'size' => $params['per_page'],
            'order' => $params['sort_by']
        ]);
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
        return $this->index($request);
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
