<?php

namespace Tests\Feature;

use App\Models\Category;
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
}
