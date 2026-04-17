<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Color;
use App\Models\Size;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class SearchService
{
    /**
     * البحث المتقدم عن المنتجات مع دعم البحث الذكي والفلترة المتقدمة
     *
     * @param array $params
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function searchProducts(array $params)
    {
        $query = Product::with(['category', 'brand', 'colors', 'sizes']);

        // تطبيق البحث النصي المتقدم
        if (isset($params['search']) && !empty($params['search'])) {
            $search = $this->sanitizeSearchTerm($params['search']);
            $searchTerms = $this->extractSearchTerms($search);

            $query->where(function($q) use ($search, $searchTerms) {
                // البحث الأساسي في الحقول الرئيسية
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('short_description', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('SKU', 'like', "%{$search}%");

                // البحث في أسماء الفئات والعلامات التجارية
                $q->orWhereHas('category', function($categoryQuery) use ($search) {
                    $categoryQuery->where('name', 'like', "%{$search}%");
                })
                ->orWhereHas('brand', function($brandQuery) use ($search) {
                    $brandQuery->where('name', 'like', "%{$search}%");
                });

                // البحث في الألوان والأحجام
                $q->orWhereHas('colors', function($colorQuery) use ($search) {
                    $colorQuery->where('name', 'like', "%{$search}%");
                })
                ->orWhereHas('sizes', function($sizeQuery) use ($search) {
                    $sizeQuery->where('name', 'like', "%{$search}%");
                });

                // البحث المتقدم بالكلمات المفتاحية المتعددة
                if (count($searchTerms) > 1) {
                    foreach ($searchTerms as $term) {
                        $q->orWhere('name', 'like', "%{$term}%")
                          ->orWhere('short_description', 'like', "%{$term}%")
                          ->orWhere('description', 'like', "%{$term}%");
                    }
                }
            });

            // تسجيل عملية البحث للإحصائيات
            $this->logSearchQuery($search);
        }

        // فلترة حسب الفئة
        $categories = $params['categories'] ?? $params['category'] ?? null;
        if (!empty($categories)) {
            $categoryIds = is_array($categories) ? $categories : explode(',', $categories);
            $query->whereIn('category_id', $categoryIds);
        }

        // فلترة حسب العلامة التجارية
        $brands = $params['brands'] ?? $params['brand'] ?? null;
        if (!empty($brands)) {
            $brandIds = is_array($brands) ? $brands : explode(',', $brands);
            $query->whereIn('brand_id', $brandIds);
        }

        // فلترة حسب نطاق الأسعار المحسن
        if (isset($params['min_price']) || isset($params['max_price'])) {
            $minPrice = isset($params['min_price']) ? (float) $params['min_price'] : 0;
            $maxPrice = isset($params['max_price']) ? (float) $params['max_price'] : 1000000;

            $query->where(function($q) use ($minPrice, $maxPrice) {
                $q->where(function($sq) use ($minPrice, $maxPrice) {
                    $sq->whereNull('sale_price')
                       ->whereBetween('regular_price', [$minPrice, $maxPrice]);
                })->orWhere(function($sq) use ($minPrice, $maxPrice) {
                    $sq->whereNotNull('sale_price')
                       ->whereBetween('sale_price', [$minPrice, $maxPrice]);
                });
            });
        }

        // فلترة حسب حالة المخزون
        if (isset($params['stock_status']) && !empty($params['stock_status'])) {
            $query->where('stock_status', $params['stock_status']);
        }

        // فلترة المنتجات المميزة
        if (isset($params['featured']) && $params['featured'] !== '') {
            $query->where('featured', (bool) $params['featured']);
        }

        // فلترة حسب الألوان
        if (isset($params['colors']) && !empty($params['colors'])) {
            $colors = is_array($params['colors']) ? $params['colors'] : explode(',', $params['colors']);
            $query->whereHas('colors', function($colorQuery) use ($colors) {
                $colorQuery->whereIn('colors.id', $colors);
            });
        }

        // فلترة حسب الأحجام
        if (isset($params['sizes']) && !empty($params['sizes'])) {
            $sizes = is_array($params['sizes']) ? $params['sizes'] : explode(',', $params['sizes']);
            $query->whereHas('sizes', function($sizeQuery) use ($sizes) {
                $sizeQuery->whereIn('sizes.id', $sizes);
            });
        }

        // تطبيق الترتيب المحسن
        $this->applySorting($query, $params);

        // تطبيق التصفح مع الحفاظ على معاملات البحث
        $perPage = isset($params['per_page']) ? (int) $params['per_page'] : 12;
        if ($perPage <= 0 || $perPage > 100) {
            $perPage = 12;
        }

        return $query->paginate($perPage)->appends(request()->query());
    }

    /**
     * البحث المتقدم في الفئات مع دعم البحث الهجين
     *
     * @param string $search
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function searchCategories($search, $limit = 10)
    {
        $search = $this->sanitizeSearchTerm($search);

        return Category::where('name', 'like', "%{$search}%")
            ->orWhere('description', 'like', "%{$search}%")
            ->withCount('products')
            ->orderByDesc('products_count')
            ->limit($limit)
            ->get();
    }

    /**
     * البحث المتقدم في العلامات التجارية
     *
     * @param string $search
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function searchBrands($search, $limit = 10)
    {
        $search = $this->sanitizeSearchTerm($search);

        return Brand::where('name', 'like', "%{$search}%")
            ->withCount('products')
            ->orderByDesc('products_count')
            ->limit($limit)
            ->get();
    }

    /**
     * البحث السريع مع الاقتراحات التلقائية
     *
     * @param string $query
     * @param int $limit
     * @return array
     */
    public function quickSearch($query, $limit = 5)
    {
        $search = $this->sanitizeSearchTerm($query);

        if (strlen($search) < 2) {
            return [];
        }

        // البحث في المنتجات
        $products = Product::where('name', 'like', "%{$search}%")
            ->orWhere('short_description', 'like', "%{$search}%")
            ->select('id', 'name', 'slug', 'image', 'regular_price', 'sale_price')
            ->limit($limit)
            ->get();

        // البحث في الفئات
        $categories = Category::where('name', 'like', "%{$search}%")
            ->select('id', 'name', 'slug', 'image')
            ->limit(3)
            ->get();

        // البحث في العلامات التجارية
        $brands = Brand::where('name', 'like', "%{$search}%")
            ->select('id', 'name', 'slug', 'image')
            ->limit(3)
            ->get();

        return [
            'products' => $products,
            'categories' => $categories,
            'brands' => $brands,
            'suggestions' => $this->getSearchSuggestions($search)
        ];
    }

    /**
     * الحصول على اقتراحات البحث الذكية
     *
     * @param string $query
     * @return array
     */
    public function getSearchSuggestions($query)
    {
        // اقتراحات من أسماء المنتجات الشائعة
        $productSuggestions = Product::where('name', 'like', "%{$query}%")
            ->select('name')
            ->distinct()
            ->limit(5)
            ->pluck('name')
            ->toArray();

        // اقتراحات من الفئات
        $categorySuggestions = Category::where('name', 'like', "%{$query}%")
            ->select('name')
            ->limit(3)
            ->pluck('name')
            ->toArray();

        // اقتراحات من العلامات التجارية
        $brandSuggestions = Brand::where('name', 'like', "%{$query}%")
            ->select('name')
            ->limit(3)
            ->pluck('name')
            ->toArray();

        return array_merge($productSuggestions, $categorySuggestions, $brandSuggestions);
    }

    /**
     * الحصول على المصطلحات الشائعة في البحث
     *
     * @param int $limit
     * @return array
     */
    public function getPopularSearchTerms($limit = 10)
    {
        try {
            return Cache::remember('popular_search_terms', 3600, function() use ($limit) {
                return DB::table('statistics')
                    ->where('type', 'search')
                    ->select('data->query as term', DB::raw('COUNT(*) as count'))
                    ->groupBy('term')
                    ->orderByDesc('count')
                    ->limit($limit)
                    ->get()
                    ->pluck('count', 'term')
                    ->toArray();
            });
        } catch (\Exception $e) {
            Log::error('Error fetching popular search terms: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * الحصول على فلاتر البحث المتاحة
     *
     * @return array
     */
    public function getSearchFilters()
    {
        return Cache::remember('search_filters', 1800, function() {
            return [
                'categories' => Category::select('id', 'name', 'slug')
                    ->orderBy('name')
                    ->get(),
                'brands' => Brand::select('id', 'name', 'slug')
                    ->orderBy('name')
                    ->get(),
                'colors' => Color::select('id', 'name', 'hex_code')
                    ->whereHas('products')
                    ->orderBy('name')
                    ->get(),
                'sizes' => Size::select('id', 'name', 'code')
                    ->whereHas('products')
                    ->orderBy('order')
                    ->get(),
                'price_ranges' => $this->getPriceRanges()
            ];
        });
    }

    /**
     * الحصول على نطاقات الأسعار المتاحة
     *
     * @return array
     */
    protected function getPriceRanges()
    {
        $maxPrice = Product::max('regular_price');
        $ranges = [];

        if ($maxPrice > 0) {
            $step = ceil($maxPrice / 5);
            for ($i = 0; $i < 5; $i++) {
                $min = $i * $step;
                $max = ($i + 1) * $step;
                if ($i == 4) $max = $maxPrice; // آخر نطاق يشمل الحد الأقصى

                $ranges[] = [
                    'min' => $min,
                    'max' => $max,
                    'label' => number_format($min) . ' - ' . number_format($max) . ' ريال'
                ];
            }
        }

        return $ranges;
    }

    /**
     * تطبيق الترتيب المحسن للنتائج
     *
     * @param Builder $query
     * @param array $params
     * @return void
     */
    protected function applySorting($query, $params)
    {
        $sortBy = $params['sort_by'] ?? 'relevance';
        $sortDirection = $params['sort_direction'] ?? 'desc';

        // قائمة الحقول المسموح بها للترتيب
        $allowedSortFields = [
            'name', 'created_at', 'regular_price', 'sale_price',
            'quantity', 'featured', 'relevance'
        ];
        $allowedSortDirections = ['asc', 'desc'];

        if (!in_array($sortBy, $allowedSortFields)) {
            $sortBy = 'relevance';
        }

        if (!in_array($sortDirection, $allowedSortDirections)) {
            $sortDirection = 'desc';
        }

        switch ($sortBy) {
            case 'relevance':
                // ترتيب حسب الصلة (المنتجات المميزة أولاً، ثم الأحدث)
                $query->orderByDesc('featured')
                      ->orderByDesc('created_at');
                break;

            case 'price_low_high':
                // ترتيب حسب السعر من الأقل للأعلى (مع مراعاة سعر التخفيض)
                $query->orderByRaw('COALESCE(sale_price, regular_price) ASC');
                break;

            case 'price_high_low':
                // ترتيب حسب السعر من الأعلى للأقل
                $query->orderByRaw('COALESCE(sale_price, regular_price) DESC');
                break;

            case 'newest':
                $query->orderByDesc('created_at');
                break;

            case 'oldest':
                $query->orderBy('created_at');
                break;

            case 'name_a_z':
                $query->orderBy('name');
                break;

            case 'name_z_a':
                $query->orderByDesc('name');
                break;

            case 'stock':
                $query->orderByDesc('quantity');
                break;

            default:
                $query->orderBy($sortBy, $sortDirection);
        }
    }

    /**
     * استخراج الكلمات المفتاحية من نص البحث
     *
     * @param string $search
     * @return array
     */
    protected function extractSearchTerms($search)
    {
        // تقسيم النص إلى كلمات منفصلة
        $terms = preg_split('/\s+/', trim($search));

        // تنظيف الكلمات وإزالة الكلمات القصيرة جداً
        $cleanTerms = [];
        foreach ($terms as $term) {
            $cleanTerm = $this->sanitizeSearchTerm($term);
            if (strlen($cleanTerm) >= 2) {
                $cleanTerms[] = $cleanTerm;
            }
        }

        return array_unique($cleanTerms);
    }

    /**
     * تسجيل عملية البحث للإحصائيات والتحليلات
     *
     * @param string $query
     * @return void
     */
    protected function logSearchQuery($query)
    {
        try {
            DB::table('statistics')->insert([
                'type' => 'search',
                'data' => json_encode([
                    'query' => $query,
                    'timestamp' => now(),
                    'user_agent' => request()->userAgent(),
                    'ip' => request()->ip()
                ]),
                'created_at' => now(),
                'updated_at' => now()
            ]);
        } catch (\Exception $e) {
            Log::error('Error logging search query: ' . $e->getMessage());
        }
    }

    /**
     * تنظيف وتعقيم مصطلح البحث لمنع SQL injection
     *
     * @param string $term
     * @return string
     */
    protected function sanitizeSearchTerm($term)
    {
        // إزالة محاولات SQL injection
        $term = str_replace(['%', '_'], ['\%', '\_'], $term);

        // إزالة الأحرف الضارة مع الحفاظ على الأحرف العربية والإنجليزية والأرقام
        $term = preg_replace('/[^\p{L}\p{N}\s\-_]/u', '', $term);

        // تنظيف المسافات الزائدة
        $term = preg_replace('/\s+/', ' ', trim($term));

        return $term;
    }

    /**
     * البحث المتقدم مع دعم الفلترة المتعددة
     *
     * @param array $filters
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function advancedSearch(array $filters)
    {
        $cacheKey = 'advanced_search_' . md5(serialize($filters));

        return Cache::remember($cacheKey, 300, function() use ($filters) {
            return $this->searchProducts($filters);
        });
    }

    /**
     * البحث بالباركود أو SKU
     *
     * @param string $code
     * @return Product|null
     */
    public function searchByCode($code)
    {
        $code = $this->sanitizeSearchTerm($code);

        return Product::where('SKU', $code)
            ->orWhere('SKU', 'like', "%{$code}%")
            ->with(['category', 'brand'])
            ->first();
    }

    /**
     * البحث في المنتجات ذات الصلة
     *
     * @param Product $product
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getRelatedProducts(Product $product, $limit = 8)
    {
        return Product::where('id', '!=', $product->id)
            ->where(function($query) use ($product) {
                $query->where('category_id', $product->category_id)
                      ->orWhere('brand_id', $product->brand_id);
            })
            ->where('stock_status', 'instock')
            ->orderByDesc('featured')
            ->orderByDesc('created_at')
            ->limit($limit)
            ->get();
    }
}
