<?php

namespace App\Repositories;

use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Color;
use App\Models\Size;
use App\Repositories\Contracts\ProductRepositoryInterface;
use App\Services\CacheService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

class ProductRepository extends BaseRepository implements ProductRepositoryInterface
{
    protected $cacheService;

    /**
     * ProductRepository constructor
     *
     * @param Product $model
     * @param CacheService $cacheService
     */
    public function __construct(Product $model, CacheService $cacheService)
    {
        parent::__construct($model);
        $this->cacheService = $cacheService;
    }

    /**
     * Find product by slug
     *
     * @param string $slug
     * @return Product|null
     */
    public function findBySlug(string $slug): ?Product
    {
        return $this->cacheService->remember(
            'product_by_slug',
            fn() => $this->model->where('slug', $slug)->first(),
            'long',
            [CacheService::CACHE_TAGS['products']],
            ['slug' => $slug]
        );
    }

    /**
     * Find product by SKU
     *
     * @param string $sku
     * @return Product|null
     */
    public function findBySku(string $sku): ?Product
    {
        return $this->cacheService->remember(
            'product_by_sku',
            fn() => $this->model->where('SKU', $sku)->first(),
            'long',
            [CacheService::CACHE_TAGS['products']],
            ['sku' => $sku]
        );
    }

    /**
     * Get products by category
     *
     * @param int $categoryId
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getByCategory(int $categoryId, int $perPage = 12): LengthAwarePaginator
    {
        return $this->model->where('category_id', $categoryId)
            ->with(['category', 'brand'])
            ->paginate($perPage);
    }

    /**
     * Get products by brand
     *
     * @param int $brandId
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getByBrand(int $brandId, int $perPage = 12): LengthAwarePaginator
    {
        return $this->model->where('brand_id', $brandId)
            ->with(['category', 'brand'])
            ->paginate($perPage);
    }

    /**
     * Get featured products
     *
     * @param int $limit
     * @return Collection
     */
    public function getFeatured(int $limit = 8): Collection
    {
        return $this->cacheService->remember(
            'products_featured',
            fn() => $this->model->where('featured', true)
                ->with(['category', 'brand'])
                ->limit($limit)
                ->get(),
            'long',
            [CacheService::CACHE_TAGS['products']],
            ['limit' => $limit]
        );
    }

    /**
     * Get latest products
     *
     * @param int $limit
     * @return Collection
     */
    public function getLatest(int $limit = 8): Collection
    {
        return $this->cacheService->remember(
            'products_latest',
            fn() => $this->model->with(['category', 'brand'])
                ->latest('created_at')
                ->limit($limit)
                ->get(),
            'medium',
            [CacheService::CACHE_TAGS['products']],
            ['limit' => $limit]
        );
    }

    /**
     * Search products
     *
     * @param string $search
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function search(string $search, int $perPage = 12): LengthAwarePaginator
    {
        return $this->model->where(function($query) use ($search) {
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('short_description', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('SKU', 'like', "%{$search}%");
        })
        ->with(['category', 'brand'])
        ->paginate($perPage);
    }

    /**
     * Get products with filters
     *
     * @param array $filters
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getWithFilters(array $filters, int $perPage = 12): LengthAwarePaginator
    {
        $query = $this->getFilteredQuery($filters);

        return $query->with(['category', 'brand'])
            ->paginate($perPage);
    }

    /**
     * Get filtered query builder
     *
     * @param array $filters
     * @return Builder
     */
    public function getFilteredQuery(array $filters): Builder
    {
        $query = $this->newQuery();

        // Category filter
        if (!empty($filters['category'])) {
            if (is_numeric($filters['category'])) {
                $query->where('category_id', $filters['category']);
            } else {
                $category = Category::where('slug', $filters['category'])->first();
                if ($category) {
                    $query->where('category_id', $category->id);
                }
            }
        }

        // Brand filter
        if (!empty($filters['brand'])) {
            if (is_numeric($filters['brand'])) {
                $query->where('brand_id', $filters['brand']);
            } else {
                $brand = Brand::where('slug', $filters['brand'])->first();
                if ($brand) {
                    $query->where('brand_id', $brand->id);
                }
            }
        }

        // Color filter
        if (!empty($filters['color'])) {
            $color = Color::where('code', $filters['color'])->first();
            if ($color) {
                $query->whereHas('colors', function($q) use ($color) {
                    $q->where('color_id', $color->id);
                });
            }
        }

        // Size filter
        if (!empty($filters['size'])) {
            $size = Size::where('code', $filters['size'])->first();
            if ($size) {
                $query->whereHas('sizes', function($q) use ($size) {
                    $q->where('size_id', $size->id);
                });
            }
        }

        // Price range filter
        if (!empty($filters['min_price']) && !empty($filters['max_price'])) {
            $query->where(function($q) use ($filters) {
                $q->whereBetween('regular_price', [$filters['min_price'], $filters['max_price']])
                  ->orWhereBetween('sale_price', [$filters['min_price'], $filters['max_price']]);
            });
        }

        // Search filter
        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('short_description', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('SKU', 'like', "%{$search}%");
            });
        }

        // Stock status filter
        if (!empty($filters['stock_status'])) {
            $query->where('stock_status', $filters['stock_status']);
        }

        // Featured filter
        if (isset($filters['featured'])) {
            $query->where('featured', $filters['featured']);
        }

        // Sorting
        $sortBy = $filters['sort_by'] ?? 'created_at';
        $sortDirection = $filters['sort_direction'] ?? 'desc';
        $query->orderBy($sortBy, $sortDirection);

        return $query;
    }

    /**
     * Get related products
     *
     * @param Product $product
     * @param int $limit
     * @return Collection
     */
    public function getRelated(Product $product, int $limit = 4): Collection
    {
        // First try to get related products if relationship exists
        $relatedProducts = $product->relatedProducts()->take($limit)->get();

        if ($relatedProducts->count() < $limit) {
            // Get products from same category
            $categoryProducts = $this->model->where('category_id', $product->category_id)
                ->where('id', '!=', $product->id)
                ->with(['category', 'brand'])
                ->take($limit - $relatedProducts->count())
                ->get();

            $relatedProducts = $relatedProducts->merge($categoryProducts);
        }

        return $relatedProducts;
    }

    /**
     * Get products by price range
     *
     * @param float $minPrice
     * @param float $maxPrice
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getByPriceRange(float $minPrice, float $maxPrice, int $perPage = 12): LengthAwarePaginator
    {
        return $this->model->where(function($query) use ($minPrice, $maxPrice) {
            $query->whereBetween('regular_price', [$minPrice, $maxPrice])
                  ->orWhereBetween('sale_price', [$minPrice, $maxPrice]);
        })
        ->with(['category', 'brand'])
        ->paginate($perPage);
    }

    /**
     * Get products by color
     *
     * @param int $colorId
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getByColor(int $colorId, int $perPage = 12): LengthAwarePaginator
    {
        return $this->model->whereHas('colors', function($query) use ($colorId) {
            $query->where('color_id', $colorId);
        })
        ->with(['category', 'brand', 'colors'])
        ->paginate($perPage);
    }

    /**
     * Get products by size
     *
     * @param int $sizeId
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getBySize(int $sizeId, int $perPage = 12): LengthAwarePaginator
    {
        return $this->model->whereHas('sizes', function($query) use ($sizeId) {
            $query->where('size_id', $sizeId);
        })
        ->with(['category', 'brand', 'sizes'])
        ->paginate($perPage);
    }

    /**
     * Get out of stock products
     *
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getOutOfStock(int $perPage = 15): LengthAwarePaginator
    {
        return $this->model->where('stock_status', 'outofstock')
            ->orWhere('quantity', 0)
            ->with(['category', 'brand'])
            ->paginate($perPage);
    }

    /**
     * Get low stock products
     *
     * @param int $threshold
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getLowStock(int $threshold = 10, int $perPage = 15): LengthAwarePaginator
    {
        return $this->model->where('quantity', '>', 0)
            ->where('quantity', '<=', $threshold)
            ->where('stock_status', 'instock')
            ->with(['category', 'brand'])
            ->paginate($perPage);
    }

    /**
     * Get top selling products
     *
     * @param int $limit
     * @return Collection
     */
    public function getTopSelling(int $limit = 10): Collection
    {
        return $this->cacheService->remember(
            'products_top_selling',
            fn() => $this->model->withCount('orderItems')
                ->orderBy('order_items_count', 'desc')
                ->with(['category', 'brand'])
                ->limit($limit)
                ->get(),
            'long',
            [CacheService::CACHE_TAGS['products'], CacheService::CACHE_TAGS['statistics']],
            ['limit' => $limit]
        );
    }

    /**
     * Get products with reviews
     *
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getWithReviews(int $perPage = 12): LengthAwarePaginator
    {
        return $this->model->has('reviews')
            ->with(['category', 'brand', 'reviews'])
            ->paginate($perPage);
    }

    /**
     * Get products by rating
     *
     * @param float $minRating
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getByRating(float $minRating, int $perPage = 12): LengthAwarePaginator
    {
        return $this->model->whereHas('reviews', function($query) use ($minRating) {
            $query->selectRaw('AVG(rating) as avg_rating')
                  ->groupBy('product_id')
                  ->havingRaw('AVG(rating) >= ?', [$minRating]);
        })
        ->with(['category', 'brand', 'reviews'])
        ->paginate($perPage);
    }

    /**
     * Get products on sale
     *
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getOnSale(int $perPage = 12): LengthAwarePaginator
    {
        return $this->model->whereColumn('sale_price', '<', 'regular_price')
            ->with(['category', 'brand'])
            ->paginate($perPage);
    }

    /**
     * Get products by multiple categories
     *
     * @param array $categoryIds
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getByCategories(array $categoryIds, int $perPage = 12): LengthAwarePaginator
    {
        return $this->model->whereIn('category_id', $categoryIds)
            ->with(['category', 'brand'])
            ->paginate($perPage);
    }

    /**
     * Get products by multiple brands
     *
     * @param array $brandIds
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getByBrands(array $brandIds, int $perPage = 12): LengthAwarePaginator
    {
        return $this->model->whereIn('brand_id', $brandIds)
            ->with(['category', 'brand'])
            ->paginate($perPage);
    }

    /**
     * Get products with specific stock status
     *
     * @param string $status
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getByStockStatus(string $status, int $perPage = 15): LengthAwarePaginator
    {
        return $this->model->where('stock_status', $status)
            ->with(['category', 'brand'])
            ->paginate($perPage);
    }

    /**
     * Update product stock
     *
     * @param int $productId
     * @param int $quantity
     * @return bool
     */
    public function updateStock(int $productId, int $quantity): bool
    {
        $product = $this->find($productId);
        if (!$product) {
            return false;
        }

        $newQuantity = $product->quantity + $quantity;
        $stockStatus = $newQuantity > 0 ? 'instock' : 'outofstock';

        return $this->updateById($productId, [
            'quantity' => $newQuantity,
            'stock_status' => $stockStatus
        ]);
    }

    /**
     * Increment product views
     *
     * @param int $productId
     * @return bool
     */
    public function incrementViews(int $productId): bool
    {
        return $this->model->where('id', $productId)->increment('views') > 0;
    }

    /**
     * Get products created between dates
     *
     * @param string $startDate
     * @param string $endDate
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getCreatedBetween(string $startDate, string $endDate, int $perPage = 15): LengthAwarePaginator
    {
        return $this->model->whereBetween('created_at', [$startDate, $endDate])
            ->with(['category', 'brand'])
            ->paginate($perPage);
    }

    /**
     * Get products with images
     *
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getWithImages(int $perPage = 12): LengthAwarePaginator
    {
        return $this->model->whereNotNull('image')
            ->where('image', '!=', '')
            ->with(['category', 'brand'])
            ->paginate($perPage);
    }

    /**
     * Get products without images
     *
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getWithoutImages(int $perPage = 15): LengthAwarePaginator
    {
        return $this->model->where(function($query) {
            $query->whereNull('image')
                  ->orWhere('image', '');
        })
        ->with(['category', 'brand'])
        ->paginate($perPage);
    }

    /**
     * Create new product and invalidate cache
     *
     * @param array $data
     * @return Product
     */
    public function create(array $data): Product
    {
        $product = parent::create($data);
        $this->invalidateProductCache();
        return $product;
    }

    /**
     * Update product and invalidate cache
     *
     * @param Product $product
     * @param array $data
     * @return bool
     */
    public function update($product, array $data): bool
    {
        $result = parent::update($product, $data);
        if ($result) {
            $this->invalidateProductCache();
            $this->invalidateSpecificProductCache($product);
        }
        return $result;
    }

    /**
     * Delete product and invalidate cache
     *
     * @param Product $product
     * @return bool
     */
    public function delete($product): bool
    {
        $result = parent::delete($product);
        if ($result) {
            $this->invalidateProductCache();
            $this->invalidateSpecificProductCache($product);
        }
        return $result;
    }

    /**
     * Invalidate all product-related cache
     *
     * @return void
     */
    public function invalidateProductCache(): void
    {
        $this->cacheService->flushTags([CacheService::CACHE_TAGS['products']]);
    }

    /**
     * Invalidate specific product cache
     *
     * @param Product $product
     * @return void
     */
    protected function invalidateSpecificProductCache(Product $product): void
    {
        // Clear specific product caches
        $this->cacheService->forget('product_by_slug', ['slug' => $product->slug]);
        $this->cacheService->forget('product_by_sku', ['sku' => $product->SKU]);
    }


}
