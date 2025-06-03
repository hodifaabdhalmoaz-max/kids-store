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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->decimal('subtotal', 10, 2);
            $table->decimal('discount', 10, 2)->default(0);
            $table->decimal('tax', 10, 2);
            $table->decimal('total', 10, 2);
            $table->string('firstname');
            $table->string('lastname');
            $table->string('mobile');
            $table->string('email');
            $table->string('line1');
            $table->string('line2')->nullable();
            $table->string('city');
            $table->string('province');
            $table->string('country');
            $table->string('zipcode');
            $table->enum('status', ['ordered', 'processing', 'shipped', 'delivered', 'canceled'])->default('ordered');
            $table->boolean('is_shipping_different')->default(false);
            $table->string('shipping_method')->nullable();
            $table->decimal('shipping_cost', 10, 2)->default(0);
            $table->string('payment_method')->nullable();
            $table->string('transaction_id')->nullable();
            $table->timestamps();
            
            // Add indexes for better performance
            $table->index('status');
            $table->index('created_at');
            $table->index('user_id');
            $table->index('payment_method');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
