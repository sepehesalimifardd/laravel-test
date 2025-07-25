<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\User;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'category_id' => Category::factory(),
            'title' => $this->faker->sentence,
            'content' => $this->faker->paragraph,
            'price' => $this->faker->numberBetween(10000,2000000),
            'stock' => $this->faker->numberBetween(0, 100),
        ];
    }
}
