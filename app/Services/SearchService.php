<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class SearchService
{
    /**
     * Search for products.
     *
     * @param array $params
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function searchProducts(array $params)
    {
        $query = Product::query();
        
        // Apply search filter
        if (isset($params['search']) && !empty($params['search'])) {
            $search = $this->sanitizeSearchTerm($params['search']);
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('short_description', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('SKU', 'like', "%{$search}%");
            });
        }
        
        // Apply category filter
        if (isset($params['category']) && !empty($params['category'])) {
            $category = Category::where('slug', $params['category'])->first();
            if ($category) {
                $query->where('category_id', $category->id);
            }
        }
        
        // Apply brand filter
        if (isset($params['brand']) && !empty($params['brand'])) {
            $brand = Brand::where('slug', $params['brand'])->first();
            if ($brand) {
                $query->where('brand_id', $brand->id);
            }
        }
        
        // Apply price range filter
        if (isset($params['min_price']) && isset($params['max_price'])) {
            $minPrice = (float) $params['min_price'];
            $maxPrice = (float) $params['max_price'];
            
            $query->where(function($q) use ($minPrice, $maxPrice) {
                $q->whereBetween('regular_price', [$minPrice, $maxPrice])
                  ->orWhereBetween('sale_price', [$minPrice, $maxPrice]);
            });
        }
        
        // Apply sorting
        $sortBy = $params['sort_by'] ?? 'created_at';
        $sortDirection = $params['sort_direction'] ?? 'desc';
        
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
        
        // Apply pagination
        $perPage = isset($params['per_page']) ? (int) $params['per_page'] : 12;
        if ($perPage <= 0 || $perPage > 100) {
            $perPage = 12;
        }
        
        return $query->paginate($perPage)->withQueryString();
    }
    
    /**
     * Search for categories.
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
            ->limit($limit)
            ->get();
    }
    
    /**
     * Search for brands.
     *
     * @param string $search
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function searchBrands($search, $limit = 10)
    {
        $search = $this->sanitizeSearchTerm($search);
        
        return Brand::where('name', 'like', "%{$search}%")
            ->limit($limit)
            ->get();
    }
    
    /**
     * Get popular search terms.
     *
     * @param int $limit
     * @return array
     */
    public function getPopularSearchTerms($limit = 10)
    {
        return DB::table('statistics')
            ->where('type', 'search')
            ->select('data->query as term', DB::raw('COUNT(*) as count'))
            ->groupBy('term')
            ->orderByDesc('count')
            ->limit($limit)
            ->get()
            ->pluck('count', 'term')
            ->toArray();
    }
    
    /**
     * Sanitize search term to prevent SQL injection.
     *
     * @param string $term
     * @return string
     */
    protected function sanitizeSearchTerm($term)
    {
        // Remove any SQL injection attempts
        $term = str_replace(['%', '_'], ['\%', '\_'], $term);
        
        // Remove any potentially harmful characters
        $term = preg_replace('/[^\p{L}\p{N}\s\-_]/u', '', $term);
        
        return $term;
    }
}
