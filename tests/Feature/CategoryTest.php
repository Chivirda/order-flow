<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_category_can_be_created(): void
    {
        Category::factory()->create([
            'name' => 'Смартфоны',
        ]);

        $this->assertDatabaseHas('categories', [
            'name' => 'Смартфоны',
        ]);
    }

    public function test_category_with_products_cannot_be_deleted(): void
    {
        $product = Product::factory()->create();

        $category = $product->category;

        $this->expectException(QueryException::class);

        Category::destroy($category->id);
    }

    public function test_category_without_products_can_be_deleted(): void
    {
        $category = Category::factory()->create(['name' => 'for_destroy']);

        Category::destroy($category->id);

        $this->assertDatabaseMissing('categories', [
            'id' => $category->id,
        ]);
    }
}
