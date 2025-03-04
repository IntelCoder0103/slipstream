<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Customer;
use App\Models\Category;
use Inertia\Testing\AssertableInertia as Assert;

class CustomerTest extends TestCase
{
    use RefreshDatabase; // Ensures database is reset for each test

    public function setUp(): void
    {
        parent::setUp();

        // Seed sample categories
        $this->category1 = Category::create(['name' => 'Gold_1']);
        $this->category2 = Category::create(['name' => 'Silver_1']);

        // Seed sample customers
        Customer::create([
            'name' => 'John Doe',
            'reference' => 'CUST-001',
            'category_id' => $this->category1->id,
            'start_date' => now(),
        ]);

        Customer::create([
            'name' => 'Jane Smith',
            'reference' => 'CUST-002',
            'category_id' => $this->category2->id,
            'start_date' => now(),
        ]);
    }
    /** @test */
    public function it_renders_customers_index_page()
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->get('/customers');

        $response->assertStatus(200)
                 ->assertInertia(fn (Assert $page) => $page
                    ->component('Customers/Index')
                    ->has('customers', 2)
                    ->has('categories')
                 );
    }

    /** @test */
    public function it_filters_customers_by_search_query()
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->get('/customers?search=John');

        $response->assertStatus(200)
                 ->assertInertia(fn (Assert $page) => $page
                    ->component('Customers/Index')
                    ->has('customers', 1, fn (Assert $customer) => $customer
                        ->where('name', 'John Doe')
                        ->etc()
                    )
                 );
    }

    /** @test */
    public function it_filters_customers_by_category()
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->get('/customers?category_id=' . $this->category1->id);

        $response->assertStatus(200)
                 ->assertInertia(fn (Assert $page) => $page
                    ->component('Customers/Index')
                    ->has('customers', 1, fn (Assert $customer) => $customer
                        ->where('category_id',$this->category1->id)
                        ->etc()
                    )
                 );
    }
}
