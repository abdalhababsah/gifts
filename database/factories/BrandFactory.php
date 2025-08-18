<?php

namespace Database\Factories;

use App\Models\Brand;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Brand>
 */
class BrandFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Brand::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name_en = $this->faker->unique()->company();
        $name_ar = 'شركة '.$this->faker->unique()->word();

        return [
            'name_en' => $name_en,
            'name_ar' => $name_ar,
            'image_path' => null,
            'is_active' => true,
            'slug' => Str::slug($name_en),
        ];
    }
}
