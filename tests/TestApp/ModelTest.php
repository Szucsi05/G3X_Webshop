<?php

namespace Tests\TestApp;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Tests\TestCase;

class ModelTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->createCategoryProductSchema();
    }

    // Verifies that the model exposes the expected mass-assignable fields.
    public function test_category_has_expected_fillable_attributes(): void
    {
        $category = new Category();

        $this->assertSame(['name', 'description'], $category->getFillable());
    }

    // Verifies that the products relation points to Product through HasMany.
    public function test_products_relation_is_a_has_many_relation_to_products(): void
    {
        $relation = (new Category())->products();

        $this->assertInstanceOf(HasMany::class, $relation);
        $this->assertInstanceOf(Product::class, $relation->getRelated());
    }

    // Verifies that a category can retrieve all products assigned to it.
    public function test_category_can_load_its_related_products(): void
    {
        $category = Category::create([
            'name' => 'Action',
            'description' => 'Fast paced games',
        ]);

        $firstProduct = Product::create([
            'name' => 'Game One',
            'category_id' => $category->id,
        ]);

        $secondProduct = Product::create([
            'name' => 'Game Two',
            'category_id' => $category->id,
        ]);

        $loadedCategory = $category->fresh('products');

        $this->assertCount(2, $loadedCategory->products);
        $this->assertEqualsCanonicalizing(
            [$firstProduct->id, $secondProduct->id],
            $loadedCategory->products->pluck('id')->all()
        );
    }
}