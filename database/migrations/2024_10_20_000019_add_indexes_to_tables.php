<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add indexes to products table
        Schema::table('products', function (Blueprint $table) {
            $table->index('name');
            $table->index('stock_status');
            $table->index('featured');
            $table->index('regular_price');
            $table->index('sale_price');
            $table->index('created_at');
        });
        
        // Add indexes to categories table
        Schema::table('categories', function (Blueprint $table) {
            $table->index('name');
            $table->index('parent_id');
        });
        
        // Add indexes to brands table
        Schema::table('brands', function (Blueprint $table) {
            $table->index('name');
        });
        
        // Add indexes to orders table
        Schema::table('orders', function (Blueprint $table) {
            $table->index('status');
            $table->index('created_at');
            $table->index('delivered_date');
            $table->index('canceled_date');
        });
        
        // Add indexes to order_items table
        Schema::table('order_items', function (Blueprint $table) {
            $table->index('rstatus');
        });
        
        // Add indexes to transactions table
        Schema::table('transactions', function (Blueprint $table) {
            $table->index('status');
            $table->index('mode');
            $table->index('created_at');
        });
        
        // Add indexes to reviews table
        Schema::table('reviews', function (Blueprint $table) {
            $table->index('rating');
            $table->index('status');
            $table->index('created_at');
        });
        
        // Add indexes to wishlists table
        Schema::table('wishlists', function (Blueprint $table) {
            $table->index('created_at');
        });
        
        // Add indexes to addresses table
        Schema::table('addresses', function (Blueprint $table) {
            $table->index('isdefault');
            $table->index('type');
        });
        
        // Add indexes to attributes table
        Schema::table('attributes', function (Blueprint $table) {
            $table->index('is_filterable');
            $table->index('frontend_type');
        });
        
        // Add indexes to product_attributes table
        Schema::table('product_attributes', function (Blueprint $table) {
            $table->index(['product_id', 'attribute_id']);
        });
        
        // Add indexes to product_sizes table
        Schema::table('product_sizes', function (Blueprint $table) {
            $table->index('quantity');
        });
        
        // Add indexes to product_colors table
        Schema::table('product_colors', function (Blueprint $table) {
            $table->index('quantity');
        });
        
        // Add indexes to shipping_methods table
        Schema::table('shipping_methods', function (Blueprint $table) {
            $table->index('is_active');
            $table->index('order');
        });
        
        // Add indexes to payment_methods table
        Schema::table('payment_methods', function (Blueprint $table) {
            $table->index('is_active');
            $table->index('order');
        });
        
        // Add indexes to settings table
        Schema::table('settings', function (Blueprint $table) {
            $table->index('group');
            $table->index('order');
        });
        
        // Add indexes to pages table
        Schema::table('pages', function (Blueprint $table) {
            $table->index('status');
            $table->index('is_default');
            $table->index('order');
        });
        
        // Add indexes to newsletter_subscribers table
        Schema::table('newsletter_subscribers', function (Blueprint $table) {
            $table->index('is_active');
            $table->index('subscribed_at');
        });
        
        // Add indexes to order_tracking table
        Schema::table('order_tracking', function (Blueprint $table) {
            $table->index('status');
            $table->index('created_at');
        });
        
        // Add indexes to statistics table
        Schema::table('statistics', function (Blueprint $table) {
            $table->index('type');
            $table->index('created_at');
        });
        
        // Add indexes to countries table
        Schema::table('countries', function (Blueprint $table) {
            $table->index('is_active');
        });
        
        // Add indexes to states table
        Schema::table('states', function (Blueprint $table) {
            $table->index('is_active');
        });
        
        // Add indexes to cities table
        Schema::table('cities', function (Blueprint $table) {
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove indexes from products table
        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex(['name']);
            $table->dropIndex(['stock_status']);
            $table->dropIndex(['featured']);
            $table->dropIndex(['regular_price']);
            $table->dropIndex(['sale_price']);
            $table->dropIndex(['created_at']);
        });
        
        // Remove indexes from categories table
        Schema::table('categories', function (Blueprint $table) {
            $table->dropIndex(['name']);
            $table->dropIndex(['parent_id']);
        });
        
        // Remove indexes from brands table
        Schema::table('brands', function (Blueprint $table) {
            $table->dropIndex(['name']);
        });
        
        // Remove indexes from orders table
        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropIndex(['created_at']);
            $table->dropIndex(['delivered_date']);
            $table->dropIndex(['canceled_date']);
        });
        
        // Remove indexes from order_items table
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropIndex(['rstatus']);
        });
        
        // Remove indexes from transactions table
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropIndex(['mode']);
            $table->dropIndex(['created_at']);
        });
        
        // Remove indexes from reviews table
        Schema::table('reviews', function (Blueprint $table) {
            $table->dropIndex(['rating']);
            $table->dropIndex(['status']);
            $table->dropIndex(['created_at']);
        });
        
        // Remove indexes from wishlists table
        Schema::table('wishlists', function (Blueprint $table) {
            $table->dropIndex(['created_at']);
        });
        
        // Remove indexes from addresses table
        Schema::table('addresses', function (Blueprint $table) {
            $table->dropIndex(['isdefault']);
            $table->dropIndex(['type']);
        });
        
        // Remove indexes from attributes table
        Schema::table('attributes', function (Blueprint $table) {
            $table->dropIndex(['is_filterable']);
            $table->dropIndex(['frontend_type']);
        });
        
        // Remove indexes from product_attributes table
        Schema::table('product_attributes', function (Blueprint $table) {
            $table->dropIndex(['product_id', 'attribute_id']);
        });
        
        // Remove indexes from product_sizes table
        Schema::table('product_sizes', function (Blueprint $table) {
            $table->dropIndex(['quantity']);
        });
        
        // Remove indexes from product_colors table
        Schema::table('product_colors', function (Blueprint $table) {
            $table->dropIndex(['quantity']);
        });
        
        // Remove indexes from shipping_methods table
        Schema::table('shipping_methods', function (Blueprint $table) {
            $table->dropIndex(['is_active']);
            $table->dropIndex(['order']);
        });
        
        // Remove indexes from payment_methods table
        Schema::table('payment_methods', function (Blueprint $table) {
            $table->dropIndex(['is_active']);
            $table->dropIndex(['order']);
        });
        
        // Remove indexes from settings table
        Schema::table('settings', function (Blueprint $table) {
            $table->dropIndex(['group']);
            $table->dropIndex(['order']);
        });
        
        // Remove indexes from pages table
        Schema::table('pages', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropIndex(['is_default']);
            $table->dropIndex(['order']);
        });
        
        // Remove indexes from newsletter_subscribers table
        Schema::table('newsletter_subscribers', function (Blueprint $table) {
            $table->dropIndex(['is_active']);
            $table->dropIndex(['subscribed_at']);
        });
        
        // Remove indexes from order_tracking table
        Schema::table('order_tracking', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropIndex(['created_at']);
        });
        
        // Remove indexes from statistics table
        Schema::table('statistics', function (Blueprint $table) {
            $table->dropIndex(['type']);
            $table->dropIndex(['created_at']);
        });
        
        // Remove indexes from countries table
        Schema::table('countries', function (Blueprint $table) {
            $table->dropIndex(['is_active']);
        });
        
        // Remove indexes from states table
        Schema::table('states', function (Blueprint $table) {
            $table->dropIndex(['is_active']);
        });
        
        // Remove indexes from cities table
        Schema::table('cities', function (Blueprint $table) {
            $table->dropIndex(['is_active']);
        });
    }
};
