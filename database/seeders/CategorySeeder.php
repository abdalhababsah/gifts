<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name_en' => 'Birthday Gifts',
                'name_ar' => 'هدايا أعياد الميلاد',
            ],
            [
                'name_en' => 'Anniversary Gifts',
                'name_ar' => 'هدايا الذكرى السنوية',
            ],
            [
                'name_en' => 'Wedding Gifts',
                'name_ar' => 'هدايا الزفاف',
            ],
            [
                'name_en' => 'Graduation Gifts',
                'name_ar' => 'هدايا التخرج',
            ],
            [
                'name_en' => 'Corporate Gifts',
                'name_ar' => 'هدايا الشركات',
            ],
            [
                'name_en' => 'Personalized Gifts',
                'name_ar' => 'هدايا مخصصة',
            ],
            [
                'name_en' => 'Holiday Gifts',
                'name_ar' => 'هدايا العطلات',
            ],
            [
                'name_en' => 'Thank You Gifts',
                'name_ar' => 'هدايا الشكر',
            ],
        ];

        foreach ($categories as $category) {
            Category::create([
                'name_en' => $category['name_en'],
                'name_ar' => $category['name_ar'],
                'slug' => Str::slug($category['name_en']),
                'is_active' => true,
            ]);
        }
    }
}
