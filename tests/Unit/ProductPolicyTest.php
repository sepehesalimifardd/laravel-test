<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductPolicyTest extends TestCase
{

    public function test_user_can_only_view_own_products()
    {
        // ایجاد کاربران و محصولات
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $product = Product::factory()->create(['user_id' => $user1->id]);

        // تست Policy
        $this->assertTrue($user1->can('view', $product));
        $this->assertFalse($user2->can('view', $product));
    }
}
