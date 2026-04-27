<?php

namespace Tests\TestApp;

use App\Models\Category;
use Tests\TestCase;

class ControllerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->createCategoryProductSchema();
    }

    // Verifies that the index endpoint filters categories by the search query.
    public function test_index_filters_categories_by_search_term(): void
    {
        Category::create(['name' => 'Action', 'description' => 'Action games']);
        Category::create(['name' => 'Puzzle', 'description' => 'Puzzle games']);
        Category::create(['name' => 'Sports', 'description' => 'Sports games']);

        $response = $this->getJson('/api/v1/categories?search=act&per_page=10');

        $response
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.name', 'Action');
    }

    // Verifies that the store endpoint persists a new category and returns 201.
    public function test_store_creates_a_category_and_returns_created_status(): void
    {
        $response = $this->postJson('/api/v1/categories', [
            'name' => 'Strategy',
            'description' => 'Strategy games',
        ]);

        $response
            ->assertCreated()
            ->assertJsonFragment([
                'name' => 'Strategy',
                'description' => 'Strategy games',
            ]);

        $this->assertDatabaseHas('categories', [
            'name' => 'Strategy',
            'description' => 'Strategy games',
        ]);
    }

    // Verifies that the update endpoint changes an existing category record.
    public function test_update_modifies_an_existing_category(): void
    {
        $category = Category::create([
            'name' => 'Indie',
            'description' => 'Independent games',
        ]);

        $response = $this->putJson('/api/v1/categories/'.$category->id, [
            'name' => 'Adventure',
            'description' => 'Story driven games',
        ]);

        $response
            ->assertOk()
            ->assertJsonFragment([
                'id' => $category->id,
                'name' => 'Adventure',
                'description' => 'Story driven games',
            ]);

        $this->assertDatabaseHas('categories', [
            'id' => $category->id,
            'name' => 'Adventure',
            'description' => 'Story driven games',
        ]);
    }
}