<?php


// use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Category;
use App\Models\User;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    public function test_category_tree_structure()
    {
        $user = User::factory()->create();
        $parent = Category::factory()->create(['name' => 'Electronics']);

        $response = $this->actingAs($user, 'sanctum')
            ->postJson('/api/categories', [
                'name' => 'Mobile',
                'parent_id' => $parent->id
            ]);

        $response->assertStatus(201);

        $childrenResponse = $this->getJson("/api/categories/{$parent->id}/children");
        $childrenResponse->assertOk();
    }
}
