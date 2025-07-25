<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class CategoryFactory extends Factory
{
    protected $model = Category::class;

    public function definition()
    {
        return [
            'name' => $this->faker->unique()->word,
        ];
    }

    /**
     * تنظیم والد برای دسته‌بندی
     */
    public function withParent()
    {
        return $this->state(function (array $attributes) {
            return [
                'parent_id' => Category::factory()
            ];
        });
    }

    /**
     * ایجاد دسته‌بندی ریشه (بدون والد)
     */
    public function root()
    {
        return $this->state(function (array $attributes) {
            return [
                'parent_id' => null
            ];
        });
    }

    /**
     * ایجاد ساختار درختی نمونه
     */
    public function withChildren()
    {
        return $this->afterCreating(function (Category $category) {
            Category::factory()
                ->count(3)
                ->create(['parent_id' => $category->id]);
        });
    }
}
