<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $brands = [
            [
                'name_en' => 'Elegant Gifts',
                'name_ar' => 'هدايا أنيقة',
                'image_path' => 'brands/elegant-gifts.png',
            ],
            [
                'name_en' => 'Luxury Presents',
                'name_ar' => 'هدايا فاخرة',
                'image_path' => 'brands/luxury-presents.png',
            ],
            [
                'name_en' => 'Artisan Crafts',
                'name_ar' => 'حرف يدوية',
                'image_path' => 'brands/artisan-crafts.png',
            ],
            [
                'name_en' => 'Memory Makers',
                'name_ar' => 'صانعي الذكريات',
                'image_path' => 'brands/memory-makers.png',
            ],
            [
                'name_en' => 'Gift Haven',
                'name_ar' => 'ملاذ الهدايا',
                'image_path' => 'brands/gift-haven.png',
            ],
            [
                'name_en' => 'Thoughtful Treasures',
                'name_ar' => 'كنوز مدروسة',
                'image_path' => 'brands/thoughtful-treasures.png',
            ],
            [
                'name_en' => 'Precious Moments',
                'name_ar' => 'لحظات ثمينة',
                'image_path' => 'brands/precious-moments.png',
            ],
            [
                'name_en' => 'Gift Express',
                'name_ar' => 'هدايا إكسبريس',
                'image_path' => 'brands/gift-express.png',
            ],
        ];

        foreach ($brands as $brand) {
            Brand::create([
                'name_en' => $brand['name_en'],
                'name_ar' => $brand['name_ar'],
                'slug' => Str::slug($brand['name_en']),
                'image_path' => $brand['image_path'],
                'is_active' => true,
            ]);
        }
    }
}
