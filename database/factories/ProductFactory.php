<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $regularPrice = $this->faker->randomFloat(2, 10, 1000);
        $salePrice = $this->faker->boolean(30) ? $regularPrice * 0.8 : $regularPrice;

        return [
            'name' => $this->faker->words(3, true),
            'slug' => $this->faker->slug,
            'short_description' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'SKU' => $this->faker->unique()->regexify('[A-Z]{3}[0-9]{3}'),
            'regular_price' => $regularPrice,
            'sale_price' => $salePrice,
            'stock_status' => $this->faker->randomElement(['instock', 'outofstock']),
            'featured' => $this->faker->boolean(20),
            'quantity' => $this->faker->numberBetween(0, 100),
            'image' => 'product.jpg',
            'category_id' => 1,
            'brand_id' => 1,
            'views' => $this->faker->numberBetween(0, 1000),
        ];
    }
}
