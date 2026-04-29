<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Category;
use App\Models\Product;

class ProductApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void {
         parent::setUp();

         // Создаём тестовые данные
        $categories = Category::factory(3)->create();

        // Создаём продукты с разными параметрами
        Product::factory()->create([
            'name' => 'iPhone 14 Pro',
            'price' => 999.99,
            'category_id' => $categories[0]->id,
            'in_stock' => true,
            'rating' => 4.8,
            'created_at' => now()->subDays(5),
        ]);

        Product::factory()->create([
            'name' => 'Samsung Galaxy S23',
            'price' => 849.99,
            'category_id' => $categories[0]->id,
            'in_stock' => true,
            'rating' => 4.5,
            'created_at' => now()->subDays(10),
        ]);
        
        Product::factory()->create([
            'name' => 'Xiaomi Mi 11',
            'price' => 499.99,
            'category_id' => $categories[1]->id,
            'in_stock' => false,
            'rating' => 4.2,
            'created_at' => now()->subDays(3),
        ]);
    }

    public function filter_by_category() {
        $category = Category::first();
        $response = $this->getJson('/api/products?category_id=' . $category->id);

        $response->assertStatus(200);

        $categories = collect($response->json('data'))->pluck('category_id');
        $this->assertTrue($categories->every(fn($c) => $c == $category->id));
    }
}
