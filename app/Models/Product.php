<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'short_description',
        'description',
        'regular_price',
        'sale_price',
        'SKU',
        'stock_status',
        'featured',
        'quantity',
        'views',
        'image',
        'images',
        'category_id',
        'brand_id',
        'is_offer',
        'details',
    ];

    protected function casts(): array
    {
        return [
            'regular_price' => 'decimal:2',
            'sale_price' => 'decimal:2',
            'featured' => 'boolean',
            'quantity' => 'integer',
            'views' => 'integer',
            'is_offer' => 'boolean',
            'details' => 'array',
        ];
    }

    public function category()
    {
        return $this->belongsTo(Category::class,'category_id');
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class,'brand_id');
    }

    public function colors()
    {
        return $this->belongsToMany(Color::class, 'product_colors')
            ->withPivot('quantity', 'price_adjustment', 'image')
            ->withTimestamps();
    }

    public function sizes()
    {
        return $this->belongsToMany(Size::class, 'product_sizes')
            ->withPivot('quantity', 'price_adjustment')
            ->withTimestamps();
    }

    /**
     * Get the reviews for the product.
     */
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Get only active/approved reviews.
     */
    public function activeReviews()
    {
        return $this->hasMany(Review::class)->where('status', true);
    }

    /**
     * Get average rating from active reviews.
     */
    public function getAverageRatingAttribute()
    {
        if ($this->relationLoaded('reviews')) {
            return $this->reviews->avg('rating') ?? 0;
        }
        return $this->activeReviews()->avg('rating') ?? 0;
    }

    /**
     * Get total count of active reviews.
     */
    public function getReviewCountAttribute()
    {
        if ($this->relationLoaded('reviews')) {
            return $this->reviews->count();
        }
        return $this->activeReviews()->count();
    }

    /**
     * Get the card title for display (short description if available, otherwise product name).
     */
    public function getCardTitleAttribute()
    {
        return !empty($this->short_description) ? $this->short_description : $this->name;
    }
}

