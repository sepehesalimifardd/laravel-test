<?php


use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Tests\TestCase;

class ProductTest extends TestCase
{
    public function test_create_product()
    {
        $user = User::factory()->create();
        $category = Category::factory()->create();

        $response = $this->actingAs($user, 'sanctum')
            ->postJson('/api/products', [
                'title' => 'New Product',
                'price' => 100000,
                'stock' => 10,
                'category_id' => $category->id,
                'attributes' => [
                    ['id' => 3, 'value' => 'Red'],
                    ['id' => 5, 'value' => 'Cotton']
                ]
            ]);

        $response->dump();

        $response->assertStatus(201)
            ->assertJsonPath('title', 'New Product');
    }

    public function test_product_version_history()
    {
        $product = Product::factory()->create();

        $this->actingAs($product->user, 'sanctum')
            ->putJson("/api/products/{$product->id}", [
                'price' => 150000
            ]);

        $response = $this->actingAs($product->user, 'sanctum')
            ->getJson("/api/products/{$product->id}/history");

        $response->assertOk()
            ->assertJsonCount(1);
    }
}
