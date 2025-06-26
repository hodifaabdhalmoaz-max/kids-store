<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add indexes to products table for better performance
        $this->addIndexSafely('products', ['featured', 'created_at'], 'products_featured_created_idx');
        $this->addIndexSafely('products', ['stock_status', 'created_at'], 'products_stock_created_idx');
        $this->addIndexSafely('products', ['category_id', 'featured'], 'products_category_featured_idx');
        $this->addIndexSafely('products', ['brand_id', 'featured'], 'products_brand_featured_idx');
        $this->addIndexSafely('products', ['regular_price', 'sale_price'], 'products_price_idx');
        $this->addIndexSafely('products', 'views', 'products_views_idx');

        // Add indexes to orders table
        Schema::table('orders', function (Blueprint $table) {
            $table->index(['user_id', 'status'], 'orders_user_status_idx');
            $table->index(['status', 'created_at'], 'orders_status_created_idx');
            $table->index(['created_at', 'total'], 'orders_created_total_idx');
        });

        // Add indexes to order_items table
        Schema::table('order_items', function (Blueprint $table) {
            $table->index(['product_id', 'created_at'], 'order_items_product_created_idx');
        });

        // Add indexes to reviews table
        Schema::table('reviews', function (Blueprint $table) {
            $table->index(['product_id', 'status'], 'reviews_product_status_idx');
            $table->index(['user_id', 'created_at'], 'reviews_user_created_idx');
            $table->index(['rating', 'status'], 'reviews_rating_status_idx');
        });

        // Add indexes to user_activities table
        Schema::table('user_activities', function (Blueprint $table) {
            $table->index(['user_id', 'created_at'], 'user_activities_user_created_idx');
            $table->index(['action', 'created_at'], 'user_activities_action_created_idx');
            $table->index('ip_address', 'user_activities_ip_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove indexes from products table
        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex('products_featured_created_idx');
            $table->dropIndex('products_stock_created_idx');
            $table->dropIndex('products_category_featured_idx');
            $table->dropIndex('products_brand_featured_idx');
            $table->dropIndex('products_price_idx');
            $table->dropIndex('products_views_idx');
        });

        // Remove indexes from orders table
        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex('orders_user_status_idx');
            $table->dropIndex('orders_status_created_idx');
            $table->dropIndex('orders_created_total_idx');
        });

        // Remove indexes from order_items table
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropIndex('order_items_product_created_idx');
        });

        // Remove indexes from reviews table
        Schema::table('reviews', function (Blueprint $table) {
            $table->dropIndex('reviews_product_status_idx');
            $table->dropIndex('reviews_user_created_idx');
            $table->dropIndex('reviews_rating_status_idx');
        });

        // Remove indexes from user_activities table
        Schema::table('user_activities', function (Blueprint $table) {
            $table->dropIndex('user_activities_user_created_idx');
            $table->dropIndex('user_activities_action_created_idx');
            $table->dropIndex('user_activities_ip_idx');
        });
    }

    /**
     * Add index safely (only if it doesn't exist)
     */
    private function addIndexSafely(string $table, $columns, string $indexName): void
    {
        if (!$this->indexExists($table, $indexName)) {
            try {
                Schema::table($table, function (Blueprint $blueprint) use ($columns, $indexName) {
                    $blueprint->index($columns, $indexName);
                });
                echo "✅ Added index: {$indexName}\n";
            } catch (\Exception $e) {
                echo "⚠️ Failed to add index {$indexName}: " . $e->getMessage() . "\n";
            }
        } else {
            echo "ℹ️ Index {$indexName} already exists\n";
        }
    }

    /**
     * Check if index exists
     */
    private function indexExists(string $table, string $indexName): bool
    {
        try {
            $indexes = DB::select("SHOW INDEX FROM {$table} WHERE Key_name = ?", [$indexName]);
            return !empty($indexes);
        } catch (\Exception $e) {
            return false;
        }
    }
};
