<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProductControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
    }

    public function test_index()
    {
        //factory(Product::class, 5)->create();
        // de la siguiente manera se hace en Laravel 8
        $product = Product::factory(5)->create();

        $response = $this->json('GET', '/api/products');

        $response
            ->assertSuccessful()
            ->assertJsonCount(5);
    }

    public function test_create_new_product()
    {
        $data = [
            'name' => 'Hola',
            'price' => 1000,
        ];
        $response = $this->json('POST', '/api/products', $data);

        $response
            ->assertSuccessful();

        $this->assertDatabaseHas('products', $data);
    }

    public function test_update_product()
    {
        /** @var Product $product */
        $product = Product::factory()->create();

        $data = [
            'name' => 'Update Product',
            'price' => 20000,
        ];

        $response = $this->json('PATCH', "/api/products/{$product->getKey()}", $data);

        $response
            ->assertSuccessful();
    }

    public function test_show_product()
    {
        /** @var Product $product */
        $product = Product::factory()->create();;

        $response = $this->json('GET', "/api/products/{$product->getKey()}");

        $response
            ->assertSuccessful();
    }

    public function test_delete_product()
    {
        /** @var Product $product */
        $product = Product::factory()->create();

        $response = $this->deleteJson("/api/products/{$product->getKey()}");

        $response
            ->assertSuccessful();

        $this->assertDeleted($product);
    }
}
