<?php

namespace Database\Seeders;

use App\Models\DeliveryTimeSlot;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DeliveryTimeSlotSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $timeSlots = [
            [
                'code' => 'morning',
                'name_en' => 'Morning Delivery',
                'name_ar' => 'توصيل صباحي',
                'window_start' => '08:00:00',
                'window_end' => '12:00:00',
                'crosses_midnight' => false,
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'code' => 'afternoon',
                'name_en' => 'Afternoon Delivery',
                'name_ar' => 'توصيل بعد الظهر',
                'window_start' => '12:00:00',
                'window_end' => '17:00:00',
                'crosses_midnight' => false,
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'code' => 'evening',
                'name_en' => 'Evening Delivery',
                'name_ar' => 'توصيل مسائي',
                'window_start' => '17:00:00',
                'window_end' => '21:00:00',
                'crosses_midnight' => false,
                'sort_order' => 3,
                'is_active' => true,
            ],
            [
                'code' => 'late_night',
                'name_en' => 'Late Night Delivery',
                'name_ar' => 'توصيل متأخر',
                'window_start' => '21:00:00',
                'window_end' => '01:00:00',
                'crosses_midnight' => true,
                'sort_order' => 4,
                'is_active' => true,
            ],
        ];

        foreach ($timeSlots as $timeSlot) {
            DeliveryTimeSlot::firstOrCreate(
                ['code' => $timeSlot['code']],
                $timeSlot
            );
        }
    }
}
