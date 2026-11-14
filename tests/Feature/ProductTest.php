<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    public function test_product_can_be_created(): void
    {
        $product = Product::factory()->create(['name' => 'Nokia N90', 'price' => 500, 'stock' => 3]);

        $this->assertDatabaseHas('products', [
            'id' => 1,
            'name' => 'Nokia N90'
        ]);

        $this->assertInstanceOf(Category::class, $product->category);
    }
}
