<?php

namespace Database\Seeders;

use App\Models\City;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cities = [
            // Capital Governorate
            ['name_en' => 'Amman', 'name_ar' => 'عمان'],
            
            // Irbid Governorate
            ['name_en' => 'Irbid', 'name_ar' => 'إربد'],
            ['name_en' => 'Ramtha', 'name_ar' => 'الرمثا'],
            ['name_en' => 'Mafraq', 'name_ar' => 'المفرق'],
            
            // Zarqa Governorate
            ['name_en' => 'Zarqa', 'name_ar' => 'الزرقاء'],
            ['name_en' => 'Russeifa', 'name_ar' => 'الرصيفة'],
            
            // Balqa Governorate
            ['name_en' => 'Salt', 'name_ar' => 'السلط'],
            ['name_en' => 'Fuheis', 'name_ar' => 'الفحيص'],
            
            // Madaba Governorate
            ['name_en' => 'Madaba', 'name_ar' => 'مادبا'],
            
            // Karak Governorate
            ['name_en' => 'Karak', 'name_ar' => 'الكرك'],
            
            // Tafilah Governorate
            ['name_en' => 'Tafilah', 'name_ar' => 'الطفيلة'],
            
            // Ma\'an Governorate
            ['name_en' => 'Ma\'an', 'name_ar' => 'معان'],
            ['name_en' => 'Petra', 'name_ar' => 'البتراء'],
            ['name_en' => 'Wadi Rum', 'name_ar' => 'وادي رم'],
            
            // Aqaba Governorate
            ['name_en' => 'Aqaba', 'name_ar' => 'العقبة'],
            
            // Jerash Governorate
            ['name_en' => 'Jerash', 'name_ar' => 'جرش'],
            
            // Ajloun Governorate
            ['name_en' => 'Ajloun', 'name_ar' => 'عجلون'],
            
            // Other major cities and areas
            ['name_en' => 'Dead Sea', 'name_ar' => 'البحر الميت'],
            ['name_en' => 'Azraq', 'name_ar' => 'الأزرق'],
            ['name_en' => 'Safawi', 'name_ar' => 'الصفاوي'],
            ['name_en' => 'Umm Qais', 'name_ar' => 'أم قيس'],
            ['name_en' => 'Pella', 'name_ar' => 'طبقة فحل'],
            ['name_en' => 'Deir Alla', 'name_ar' => 'دير علا'],
            ['name_en' => 'Shobak', 'name_ar' => 'الشوبك'],
            ['name_en' => 'Qadisiyah', 'name_ar' => 'القادسية'],
            ['name_en' => 'Sahab', 'name_ar' => 'سحاب'],
            ['name_en' => 'Marj Al Hamam', 'name_ar' => 'مرج الحمام'],
            ['name_en' => 'Wadi Sir', 'name_ar' => 'وادي السير'],
            ['name_en' => 'Na\'ur', 'name_ar' => 'ناعور'],
            ['name_en' => 'Mukhayyam', 'name_ar' => 'المخيم'],
            ['name_en' => 'Ain Al Basha', 'name_ar' => 'عين الباشا'],
            ['name_en' => 'Khirbet As Souq', 'name_ar' => 'خربة السوق'],
        ];

        foreach ($cities as $city) {
            City::firstOrCreate(
                ['name_en' => $city['name_en']],
                array_merge($city, ['is_active' => true])
            );
        }
    }
}
