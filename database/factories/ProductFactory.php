<?php

namespace Database\Factories;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name_en = $this->faker->unique()->words(3, true);
        $name_ar = 'منتج '.$this->faker->unique()->word();

        return [
            'brand_id' => Brand::factory(),
            'category_id' => Category::factory(),
            'name_en' => $name_en,
            'name_ar' => $name_ar,
            'description_en' => $this->faker->paragraph(),
            'description_ar' => 'وصف '.$this->faker->paragraph(),
            'price' => $this->faker->randomFloat(2, 10, 1000),
            'stock' => $this->faker->numberBetween(0, 100),
            'cover_image_path' => null,
            'is_active' => $this->faker->boolean(80),
            'is_featured' => $this->faker->boolean(20),
            'sku' => $this->faker->unique()->regexify('[A-Z]{3}[0-9]{5}'),
            'slug' => Str::slug($name_en),
        ];
    }
}
