<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();
        
        // Get all brand and category IDs
        $brandIds = Brand::pluck('id')->toArray();
        $categoryIds = Category::pluck('id')->toArray();
        
        // Gift product names
        $giftNames = [
            'Personalized Photo Frame',
            'Handcrafted Wooden Box',
            'Luxury Scented Candle Set',
            'Custom Name Necklace',
            'Engraved Watch',
            'Crystal Vase',
            'Leather Wallet',
            'Gourmet Chocolate Box',
            'Premium Tea Collection',
            'Aromatherapy Diffuser',
            'Customized Star Map',
            'Handmade Soap Collection',
            'Monogrammed Towel Set',
            'Artisan Coffee Sampler',
            'Bluetooth Speaker',
            'Digital Photo Frame',
            'Silk Scarf',
            'Personalized Cutting Board',
            'Leather Journal',
            'Wireless Earbuds',
            'Luxury Pen Set',
            'Cashmere Throw Blanket',
            'Wine Gift Set',
            'Decorative Succulent Planter',
            'Gourmet Snack Basket',
            'Essential Oil Set',
            'Personalized Calendar',
            'Handcrafted Jewelry Box',
            'Luxury Bath Set',
            'Smart Water Bottle',
            'Customized Puzzle',
            'Bonsai Tree Kit',
            'Artisan Cheese Board',
            'Monogrammed Robe',
            'Bluetooth Turntable',
            'Decorative Wall Art',
            'Luxury Skincare Set',
            'Personalized Doormat',
            'Gourmet Coffee Maker',
            'Wireless Charging Pad',
        ];
        
        // Arabic gift product names
        $giftNamesAr = [
            'إطار صور مخصص',
            'صندوق خشبي مصنوع يدويًا',
            'مجموعة شموع معطرة فاخرة',
            'قلادة اسم مخصصة',
            'ساعة منقوشة',
            'مزهرية كريستال',
            'محفظة جلدية',
            'صندوق شوكولاتة فاخرة',
            'مجموعة شاي متميزة',
            'ناشر للعلاج بالروائح',
            'خريطة نجوم مخصصة',
            'مجموعة صابون يدوي الصنع',
            'مجموعة مناشف بحروف مطرزة',
            'عينات قهوة حرفية',
            'مكبر صوت بلوتوث',
            'إطار صور رقمي',
            'وشاح حريري',
            'لوح تقطيع مخصص',
            'مذكرة جلدية',
            'سماعات لاسلكية',
            'مجموعة أقلام فاخرة',
            'بطانية كشمير',
            'مجموعة هدايا النبيذ',
            'مزارع نباتات عصارية',
            'سلة وجبات خفيفة فاخرة',
            'مجموعة زيوت أساسية',
            'تقويم مخصص',
            'صندوق مجوهرات مصنوع يدويًا',
            'مجموعة حمام فاخرة',
            'زجاجة مياه ذكية',
            'لغز مخصص',
            'مجموعة شجرة بونساي',
            'لوح جبن حرفي',
            'رداء بحروف مطرزة',
            'جهاز تشغيل اسطوانات بلوتوث',
            'فن جداري زخرفي',
            'مجموعة عناية بالبشرة فاخرة',
            'ممسحة أرجل مخصصة',
            'صانع قهوة فاخر',
            'لوحة شحن لاسلكية',
        ];
        
        // Gift descriptions
        $giftDescriptions = [
            'Perfect for capturing special memories.',
            'Handcrafted with attention to detail.',
            'Luxurious gift for any occasion.',
            'Personalized gift that will be cherished forever.',
            'Elegant and thoughtful present.',
            'Premium quality gift for your loved ones.',
            'Unique gift that stands out from the rest.',
            'Beautifully designed and carefully crafted.',
            'A gift that creates lasting memories.',
            'Sophisticated present for special occasions.',
            'Customized gift that shows you care.',
            'Artisanal quality with modern design.',
            'Luxury gift for the discerning recipient.',
            'Thoughtfully created with premium materials.',
            'Perfect gift for celebrating important moments.',
            'Handmade with love and attention to detail.',
            'Elegant gift that impresses every time.',
            'Personalized touch for a meaningful present.',
            'High-quality gift for any celebration.',
            'Unique present that will be remembered.',
        ];
        
        // Arabic gift descriptions
        $giftDescriptionsAr = [
            'مثالية لالتقاط الذكريات الخاصة.',
            'مصنوعة يدويًا مع الاهتمام بالتفاصيل.',
            'هدية فاخرة لأي مناسبة.',
            'هدية شخصية ستبقى عزيزة إلى الأبد.',
            'هدية أنيقة ومدروسة.',
            'هدية ذات جودة ممتازة لأحبائك.',
            'هدية فريدة تتميز عن البقية.',
            'مصممة بشكل جميل ومصنوعة بعناية.',
            'هدية تخلق ذكريات دائمة.',
            'هدية راقية للمناسبات الخاصة.',
            'هدية مخصصة تظهر اهتمامك.',
            'جودة حرفية بتصميم عصري.',
            'هدية فاخرة للمتلقي المتميز.',
            'صنعت بعناية من مواد ممتازة.',
            'هدية مثالية للاحتفال باللحظات المهمة.',
            'مصنوعة يدويًا بالحب والاهتمام بالتفاصيل.',
            'هدية أنيقة تبهر في كل مرة.',
            'لمسة شخصية لهدية ذات معنى.',
            'هدية عالية الجودة لأي احتفال.',
            'هدية فريدة ستبقى في الذاكرة.',
        ];
        
        // Create 100 products
        for ($i = 0; $i < 100; $i++) {
            // Select a random name and description
            $nameIndex = $faker->numberBetween(0, count($giftNames) - 1);
            $descIndex = $faker->numberBetween(0, count($giftDescriptions) - 1);
            
            // Add a suffix to make names unique
            $nameSuffix = $faker->randomElement([' - Premium', ' - Deluxe', ' - Classic', ' - Special', ' - Elite', ' - Signature', ' - Gold', ' - Silver', ' - Platinum', ' - Diamond']);
            $name = $giftNames[$nameIndex] . $nameSuffix;
            $nameAr = $giftNamesAr[$nameIndex] . ' - ' . $faker->randomElement(['متميز', 'فاخر', 'كلاسيكي', 'خاص', 'نخبة', 'مميز', 'ذهبي', 'فضي', 'بلاتيني', 'ماسي']);
            
            // Generate a unique slug by adding a random string
            $slug = Str::slug($name) . '-' . Str::lower(Str::random(4));
            
            // Set as featured for the first 10 products
            $isFeatured = $i < 10;
            
            // Create the product
            $product = Product::create([
                'brand_id' => $faker->randomElement($brandIds),
                'category_id' => $faker->randomElement($categoryIds),
                'name_en' => $name,
                'name_ar' => $nameAr,
                'description_en' => $giftDescriptions[$descIndex] . ' ' . $faker->paragraph(2),
                'description_ar' => $giftDescriptionsAr[$descIndex] . ' ' . $faker->realText(200, 2),
                'price' => $faker->randomFloat(2, 19.99, 499.99),
                'stock' => $faker->numberBetween(5, 100),
                'cover_image_path' => $faker->imageUrl(800, 600, 'gift', true),
                'is_active' => true,
                'is_featured' => $isFeatured,
                'sku' => 'GIFT-' . strtoupper(Str::random(6)),
                'slug' => $slug,
            ]);
            
            // Create 2-5 product images for each product
            $imageCount = $faker->numberBetween(2, 5);
            for ($j = 0; $j < $imageCount; $j++) {
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $faker->imageUrl(800, 600, 'gift', true),
                    'is_primary' => $j === 0, // First image is primary
                ]);
            }
        }
    }
}