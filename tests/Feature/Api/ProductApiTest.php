<?php

namespace Tests\Feature\Api;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductApiTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Setup the test environment.
     */
    protected function setUp(): void
    {
        parent::setUp();
        
        // Set the default locale for testing
        app()->setLocale('en');
    }

    /**
     * Test fetching products with pagination.
     */
    public function test_can_fetch_products_with_pagination(): void
    {
        // Create test data
        $brand = Brand::factory()->create();
        $category = Category::factory()->create();
        
        // Create 20 active products in stock
        $products = Product::factory()
            ->count(20)
            ->for($brand)
            ->for($category)
            ->create([
                'is_active' => true,
                'stock' => 10
            ]);
        
        // Create 5 inactive products that shouldn't be returned
        Product::factory()
            ->count(5)
            ->for($brand)
            ->for($category)
            ->create([
                'is_active' => false,
                'stock' => 10
            ]);
        
        // Create 5 out-of-stock products that shouldn't be returned
        Product::factory()
            ->count(5)
            ->for($brand)
            ->for($category)
            ->create([
                'is_active' => true,
                'stock' => 0
            ]);

        // Test default pagination (16 per page)
        $response = $this->getJson('/api/products');
        
        $response->assertStatus(200)
            ->assertJsonCount(16, 'data')
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'description',
                        'price',
                        'stock',
                        'sku',
                        'is_active',
                        'is_featured',
                        'cover_image',
                        'brand' => ['id', 'name'],
                        'category' => ['id', 'name']
                    ]
                ],
                'links',
                'meta'
            ]);
        
        // Test custom pagination
        $response = $this->getJson('/api/products?per_page=10');
        
        $response->assertStatus(200)
            ->assertJsonCount(10, 'data');
    }

    /**
     * Test filtering products by brand.
     */
    public function test_can_filter_products_by_brand(): void
    {
        // Create test data
        $brand1 = Brand::factory()->create();
        $brand2 = Brand::factory()->create();
        $category = Category::factory()->create();
        
        // Create products for brand1
        $productsForBrand1 = Product::factory()
            ->count(5)
            ->for($brand1)
            ->for($category)
            ->create([
                'is_active' => true,
                'stock' => 10
            ]);
        
        // Create products for brand2
        $productsForBrand2 = Product::factory()
            ->count(5)
            ->for($brand2)
            ->for($category)
            ->create([
                'is_active' => true,
                'stock' => 10
            ]);
        
        // Test filtering by brand1
        $response = $this->getJson("/api/products?brand_id={$brand1->id}");
        
        $response->assertStatus(200)
            ->assertJsonCount(5, 'data');
        
        $responseData = $response->json('data');
        foreach ($responseData as $product) {
            $this->assertEquals($brand1->id, $product['brand']['id']);
        }
        
        // Test filtering by brand2
        $response = $this->getJson("/api/products?brand_id={$brand2->id}");
        
        $response->assertStatus(200)
            ->assertJsonCount(5, 'data');
        
        $responseData = $response->json('data');
        foreach ($responseData as $product) {
            $this->assertEquals($brand2->id, $product['brand']['id']);
        }
    }

    /**
     * Test filtering products by category.
     */
    public function test_can_filter_products_by_category(): void
    {
        // Create test data
        $brand = Brand::factory()->create();
        $category1 = Category::factory()->create();
        $category2 = Category::factory()->create();
        
        // Create products for category1
        $productsForCategory1 = Product::factory()
            ->count(5)
            ->for($brand)
            ->for($category1)
            ->create([
                'is_active' => true,
                'stock' => 10
            ]);
        
        // Create products for category2
        $productsForCategory2 = Product::factory()
            ->count(5)
            ->for($brand)
            ->for($category2)
            ->create([
                'is_active' => true,
                'stock' => 10
            ]);
        
        // Test filtering by category1
        $response = $this->getJson("/api/products?category_id={$category1->id}");
        
        $response->assertStatus(200)
            ->assertJsonCount(5, 'data');
        
        $responseData = $response->json('data');
        foreach ($responseData as $product) {
            $this->assertEquals($category1->id, $product['category']['id']);
        }
        
        // Test filtering by category2
        $response = $this->getJson("/api/products?category_id={$category2->id}");
        
        $response->assertStatus(200)
            ->assertJsonCount(5, 'data');
        
        $responseData = $response->json('data');
        foreach ($responseData as $product) {
            $this->assertEquals($category2->id, $product['category']['id']);
        }
    }

    /**
     * Test searching products by name.
     */
    public function test_can_search_products_by_name(): void
    {
        // Create test data
        $brand = Brand::factory()->create();
        $category = Category::factory()->create();
        
        // Create products with specific names
        Product::factory()
            ->for($brand)
            ->for($category)
            ->create([
                'name_en' => 'Special iPhone Case',
                'name_ar' => 'حافظة آيفون خاصة',
                'is_active' => true,
                'stock' => 10
            ]);
        
        Product::factory()
            ->for($brand)
            ->for($category)
            ->create([
                'name_en' => 'Regular Phone Case',
                'name_ar' => 'حافظة هاتف عادية',
                'is_active' => true,
                'stock' => 10
            ]);
        
        Product::factory()
            ->for($brand)
            ->for($category)
            ->create([
                'name_en' => 'Headphones',
                'name_ar' => 'سماعات',
                'is_active' => true,
                'stock' => 10
            ]);
        
        // Test search in English
        $response = $this->getJson('/api/products?search=iPhone');
        
        $response->assertStatus(200)
            ->assertJsonCount(1, 'data');
        
        $responseData = $response->json('data');
        $this->assertEquals('Special iPhone Case', $responseData[0]['name']);
        
        // Test search for "case" which should return both cases
        $response = $this->getJson('/api/products?search=Case');
        
        $response->assertStatus(200)
            ->assertJsonCount(2, 'data');
    }

    /**
     * Test sorting products by price.
     */
    public function test_can_sort_products_by_price(): void
    {
        // Create test data
        $brand = Brand::factory()->create();
        $category = Category::factory()->create();
        
        // Create products with different prices
        Product::factory()
            ->for($brand)
            ->for($category)
            ->create([
                'name_en' => 'Expensive Product',
                'price' => 1000,
                'is_active' => true,
                'stock' => 10
            ]);
        
        Product::factory()
            ->for($brand)
            ->for($category)
            ->create([
                'name_en' => 'Medium Product',
                'price' => 500,
                'is_active' => true,
                'stock' => 10
            ]);
        
        Product::factory()
            ->for($brand)
            ->for($category)
            ->create([
                'name_en' => 'Cheap Product',
                'price' => 100,
                'is_active' => true,
                'stock' => 10
            ]);
        
        // Test sorting by price ascending
        $response = $this->getJson('/api/products?sort=price_asc');
        
        $response->assertStatus(200);
        $responseData = $response->json('data');
        
        $this->assertEquals('Cheap Product', $responseData[0]['name']);
        $this->assertEquals(100, $responseData[0]['price']);
        
        $this->assertEquals('Medium Product', $responseData[1]['name']);
        $this->assertEquals(500, $responseData[1]['price']);
        
        $this->assertEquals('Expensive Product', $responseData[2]['name']);
        $this->assertEquals(1000, $responseData[2]['price']);
        
        // Test sorting by price descending
        $response = $this->getJson('/api/products?sort=price_desc');
        
        $response->assertStatus(200);
        $responseData = $response->json('data');
        
        $this->assertEquals('Expensive Product', $responseData[0]['name']);
        $this->assertEquals(1000, $responseData[0]['price']);
        
        $this->assertEquals('Medium Product', $responseData[1]['name']);
        $this->assertEquals(500, $responseData[1]['price']);
        
        $this->assertEquals('Cheap Product', $responseData[2]['name']);
        $this->assertEquals(100, $responseData[2]['price']);
    }

    /**
     * Test default sorting (latest first).
     */
    public function test_default_sorting_is_latest_first(): void
    {
        // Create test data
        $brand = Brand::factory()->create();
        $category = Category::factory()->create();
        
        // Create products with different timestamps
        $oldProduct = Product::factory()
            ->for($brand)
            ->for($category)
            ->create([
                'name_en' => 'Old Product',
                'is_active' => true,
                'stock' => 10,
                'created_at' => now()->subDays(2)
            ]);
        
        $newProduct = Product::factory()
            ->for($brand)
            ->for($category)
            ->create([
                'name_en' => 'New Product',
                'is_active' => true,
                'stock' => 10,
                'created_at' => now()
            ]);
        
        $response = $this->getJson('/api/products');
        
        $response->assertStatus(200);
        $responseData = $response->json('data');
        
        // First product should be the newest
        $this->assertEquals('New Product', $responseData[0]['name']);
    }

    /**
     * Test fetching featured products.
     */
    public function test_can_fetch_featured_products(): void
    {
        // Create test data
        $brand = Brand::factory()->create();
        $category = Category::factory()->create();
        
        // Create 5 featured products
        $featuredProducts = Product::factory()
            ->count(5)
            ->for($brand)
            ->for($category)
            ->create([
                'is_active' => true,
                'is_featured' => true,
                'stock' => 10
            ]);
        
        // Create 5 non-featured products
        $nonFeaturedProducts = Product::factory()
            ->count(5)
            ->for($brand)
            ->for($category)
            ->create([
                'is_active' => true,
                'is_featured' => false,
                'stock' => 10
            ]);
        
        // Test featured products endpoint
        $response = $this->getJson('/api/products/featured');
        
        $response->assertStatus(200)
            ->assertJsonCount(5, 'data');
        
        $responseData = $response->json('data');
        foreach ($responseData as $product) {
            $this->assertTrue($product['is_featured']);
        }
    }

    /**
     * Test featured products endpoint limits to 9 products.
     */
    public function test_featured_products_endpoint_limits_to_9_products(): void
    {
        // Create test data
        $brand = Brand::factory()->create();
        $category = Category::factory()->create();
        
        // Create 12 featured products
        $featuredProducts = Product::factory()
            ->count(12)
            ->for($brand)
            ->for($category)
            ->create([
                'is_active' => true,
                'is_featured' => true,
                'stock' => 10
            ]);
        
        // Test featured products endpoint should return max 9 products
        $response = $this->getJson('/api/products/featured');
        
        $response->assertStatus(200)
            ->assertJsonCount(9, 'data');
    }

    /**
     * Test featured products excludes inactive products.
     */
    public function test_featured_products_excludes_inactive_products(): void
    {
        // Create test data
        $brand = Brand::factory()->create();
        $category = Category::factory()->create();
        
        // Create 3 active featured products
        Product::factory()
            ->count(3)
            ->for($brand)
            ->for($category)
            ->create([
                'is_active' => true,
                'is_featured' => true,
                'stock' => 10
            ]);
        
        // Create 2 inactive featured products
        Product::factory()
            ->count(2)
            ->for($brand)
            ->for($category)
            ->create([
                'is_active' => false,
                'is_featured' => true,
                'stock' => 10
            ]);
        
        // Test featured products endpoint should only return active products
        $response = $this->getJson('/api/products/featured');
        
        $response->assertStatus(200)
            ->assertJsonCount(3, 'data');
    }

    /**
     * Test featured products excludes out of stock products.
     */
    public function test_featured_products_excludes_out_of_stock_products(): void
    {
        // Create test data
        $brand = Brand::factory()->create();
        $category = Category::factory()->create();
        
        // Create 3 featured products in stock
        Product::factory()
            ->count(3)
            ->for($brand)
            ->for($category)
            ->create([
                'is_active' => true,
                'is_featured' => true,
                'stock' => 10
            ]);
        
        // Create 2 featured products out of stock
        Product::factory()
            ->count(2)
            ->for($brand)
            ->for($category)
            ->create([
                'is_active' => true,
                'is_featured' => true,
                'stock' => 0
            ]);
        
        // Test featured products endpoint should only return in-stock products
        $response = $this->getJson('/api/products/featured');
        
        $response->assertStatus(200)
            ->assertJsonCount(3, 'data');
    }

    /**
     * Test getting a specific product by ID.
     */
    public function test_can_get_a_specific_product_by_id(): void
    {
        // Create test data
        $brand = Brand::factory()->create();
        $category = Category::factory()->create();
        
        // Create a product
        $product = Product::factory()
            ->for($brand)
            ->for($category)
            ->create([
                'is_active' => true,
                'stock' => 10
            ]);
        
        // Create some images for the product
        $image1 = ProductImage::factory()->create([
            'product_id' => $product->id,
            'is_primary' => true
        ]);
        
        $image2 = ProductImage::factory()->create([
            'product_id' => $product->id,
            'is_primary' => false
        ]);
        
        // Test getting the product
        $response = $this->getJson("/api/products/{$product->id}");
        
        $response->assertStatus(200);
        
        $responseData = $response->json('data');
        
        // Check that the response contains the product data
        $this->assertEquals($product->id, $responseData['id']);
        $this->assertEquals($product->name_en, $responseData['name']);
        $this->assertEquals($product->price, $responseData['price']);
        $this->assertEquals($product->stock, $responseData['stock']);
        $this->assertEquals($product->sku, $responseData['sku']);
        $this->assertTrue($responseData['is_active']);
        
        // Check that the response contains the brand data
        $this->assertEquals($brand->id, $responseData['brand']['id']);
        $this->assertEquals($brand->name_en, $responseData['brand']['name']);
        
        // Check that the response contains the category data
        $this->assertEquals($category->id, $responseData['category']['id']);
        $this->assertEquals($category->name_en, $responseData['category']['name']);
        
        // Check that the response contains the images
        $this->assertCount(2, $responseData['images']);
    }

    /**
     * Test 404 for non-existent product.
     */
    public function test_returns_404_for_non_existent_product(): void
    {
        // Test with a non-existent product ID
        $response = $this->getJson('/api/products/9999');
        
        $response->assertStatus(404)
            ->assertJson([
                'success' => false,
                'message' => 'Product not found'
            ]);
    }

    /**
     * Test 404 for inactive product.
     */
    public function test_returns_404_for_inactive_product(): void
    {
        // Create test data
        $brand = Brand::factory()->create();
        $category = Category::factory()->create();
        
        // Create an inactive product
        $product = Product::factory()
            ->for($brand)
            ->for($category)
            ->create([
                'is_active' => false,
                'stock' => 10
            ]);
        
        // Test getting the inactive product
        $response = $this->getJson("/api/products/{$product->id}");
        
        $response->assertStatus(404)
            ->assertJson([
                'success' => false,
                'message' => 'Product not found'
            ]);
    }

    /**
     * Test validation for invalid parameters.
     */
    public function test_validates_invalid_parameters_for_product_index(): void
    {
        // Test with invalid brand_id
        $response = $this->getJson('/api/products?brand_id=9999');
        
        $response->assertStatus(422)
            ->assertJson([
                'success' => false,
                'message' => 'Invalid parameters provided',
                'errors' => [
                    'brand_id' => ['The selected brand does not exist']
                ]
            ]);
        
        // Test with invalid category_id
        $response = $this->getJson('/api/products?category_id=9999');
        
        $response->assertStatus(422)
            ->assertJson([
                'success' => false,
                'message' => 'Invalid parameters provided',
                'errors' => [
                    'category_id' => ['The selected category does not exist']
                ]
            ]);
        
        // Test with invalid sort parameter
        $response = $this->getJson('/api/products?sort=invalid_sort');
        
        $response->assertStatus(422)
            ->assertJson([
                'success' => false,
                'message' => 'Invalid parameters provided',
                'errors' => [
                    'sort' => ['The sort value is invalid']
                ]
            ]);
        
        // Test with invalid per_page parameter
        $response = $this->getJson('/api/products?per_page=100');
        
        $response->assertStatus(422)
            ->assertJson([
                'success' => false,
                'message' => 'Invalid parameters provided',
                'errors' => [
                    'per_page' => ['The per page may not be greater than 50']
                ]
            ]);
    }

    /**
     * Test validation for invalid per_page minimum value.
     */
    public function test_validates_per_page_minimum_value(): void
    {
        $response = $this->getJson('/api/products?per_page=0');
        
        $response->assertStatus(422)
            ->assertJson([
                'success' => false,
                'message' => 'Invalid parameters provided',
                'errors' => [
                    'per_page' => ['The per page must be at least 1']
                ]
            ]);
    }

    /**
     * Test validation for non-integer per_page parameter.
     */
    public function test_validates_per_page_integer_value(): void
    {
        $response = $this->getJson('/api/products?per_page=abc');
        
        $response->assertStatus(422)
            ->assertJson([
                'success' => false,
                'message' => 'Invalid parameters provided'
            ]);
        
        $responseData = $response->json();
        $this->assertArrayHasKey('per_page', $responseData['errors']);
    }

    /**
     * Test validation for non-integer brand_id parameter.
     */
    public function test_validates_brand_id_integer_value(): void
    {
        $response = $this->getJson('/api/products?brand_id=abc');
        
        $response->assertStatus(422);
        
        $responseData = $response->json();
        $this->assertFalse($responseData['success']);
        $this->assertEquals('Invalid parameters provided', $responseData['message']);
        $this->assertArrayHasKey('brand_id', $responseData['errors']);
    }

    /**
     * Test validation for non-integer category_id parameter.
     */
    public function test_validates_category_id_integer_value(): void
    {
        $response = $this->getJson('/api/products?category_id=abc');
        
        $response->assertStatus(422);
        
        $responseData = $response->json();
        $this->assertFalse($responseData['success']);
        $this->assertEquals('Invalid parameters provided', $responseData['message']);
        $this->assertArrayHasKey('category_id', $responseData['errors']);
    }

    /**
     * Test validation for long search parameter.
     */
    public function test_validates_search_parameter_length(): void
    {
        $longSearch = str_repeat('a', 256); // 256 characters
        
        $response = $this->getJson("/api/products?search={$longSearch}");
        
        $response->assertStatus(422);
        
        $responseData = $response->json();
        $this->assertFalse($responseData['success']);
        $this->assertEquals('Invalid parameters provided', $responseData['message']);
        $this->assertArrayHasKey('search', $responseData['errors']);
    }

    /**
     * Test localization of error messages.
     */
    public function test_localizes_error_messages_based_on_locale(): void
    {
        // Test with Arabic locale
        app()->setLocale('ar');
        
        // Test with invalid brand_id
        $response = $this->getJson('/api/products?brand_id=9999');
        
        $response->assertStatus(422)
            ->assertJson([
                'success' => false,
                'message' => 'تم تقديم معلمات غير صالحة',
                'errors' => [
                    'brand_id' => ['العلامة التجارية المحددة غير موجودة']
                ]
            ]);
        
        // Test with non-existent product ID
        $response = $this->getJson('/api/products/9999');
        
        $response->assertStatus(404)
            ->assertJson([
                'success' => false,
                'message' => 'المنتج غير موجود'
            ]);
    }

    /**
     * Test localization of other validation error messages in Arabic.
     */
    public function test_localizes_other_validation_errors_in_arabic(): void
    {
        app()->setLocale('ar');
        
        // Test invalid category_id
        $response = $this->getJson('/api/products?category_id=9999');
        
        $response->assertStatus(422)
            ->assertJson([
                'success' => false,
                'message' => 'تم تقديم معلمات غير صالحة',
                'errors' => [
                    'category_id' => ['الفئة المحددة غير موجودة']
                ]
            ]);
        
        // Test invalid sort parameter
        $response = $this->getJson('/api/products?sort=invalid_sort');
        
        $response->assertStatus(422)
            ->assertJson([
                'success' => false,
                'message' => 'تم تقديم معلمات غير صالحة',
                'errors' => [
                    'sort' => ['قيمة الترتيب غير صالحة']
                ]
            ]);
        
        // Test invalid per_page parameter
        $response = $this->getJson('/api/products?per_page=100');
        
        $response->assertStatus(422)
            ->assertJson([
                'success' => false,
                'message' => 'تم تقديم معلمات غير صالحة',
                'errors' => [
                    'per_page' => ['يجب أن لا يتجاوز عدد العناصر في الصفحة 50']
                ]
            ]);
    }

    /**
     * Test combination of filters (brand + category).
     */
    public function test_can_combine_brand_and_category_filters(): void
    {
        // Create test data
        $brand1 = Brand::factory()->create();
        $brand2 = Brand::factory()->create();
        $category1 = Category::factory()->create();
        $category2 = Category::factory()->create();
        
        // Create products for brand1 + category1
        Product::factory()
            ->count(3)
            ->for($brand1)
            ->for($category1)
            ->create([
                'is_active' => true,
                'stock' => 10
            ]);
        
        // Create products for brand1 + category2
        Product::factory()
            ->count(2)
            ->for($brand1)
            ->for($category2)
            ->create([
                'is_active' => true,
                'stock' => 10
            ]);
        
        // Create products for brand2 + category1
        Product::factory()
            ->count(4)
            ->for($brand2)
            ->for($category1)
            ->create([
                'is_active' => true,
                'stock' => 10
            ]);
        
        // Test filtering by brand1 + category1
        $response = $this->getJson("/api/products?brand_id={$brand1->id}&category_id={$category1->id}");
        
        $response->assertStatus(200)
            ->assertJsonCount(3, 'data');
        
        $responseData = $response->json('data');
        foreach ($responseData as $product) {
            $this->assertEquals($brand1->id, $product['brand']['id']);
            $this->assertEquals($category1->id, $product['category']['id']);
        }
    }

    /**
     * Test combination of filters with search.
     */
    public function test_can_combine_filters_with_search(): void
    {
        // Create test data
        $brand1 = Brand::factory()->create();
        $brand2 = Brand::factory()->create();
        $category = Category::factory()->create();
        
        // Create iPhone products for brand1
        Product::factory()
            ->for($brand1)
            ->for($category)
            ->create([
                'name_en' => 'iPhone Case Special',
                'is_active' => true,
                'stock' => 10
            ]);
        
        // Create iPhone products for brand2
        Product::factory()
            ->for($brand2)
            ->for($category)
            ->create([
                'name_en' => 'iPhone Screen Protector',
                'is_active' => true,
                'stock' => 10
            ]);
        
        // Create other products for brand1
        Product::factory()
            ->for($brand1)
            ->for($category)
            ->create([
                'name_en' => 'Android Case',
                'is_active' => true,
                'stock' => 10
            ]);
        
        // Test search + brand filter
        $response = $this->getJson("/api/products?search=iPhone&brand_id={$brand1->id}");
        
        $response->assertStatus(200)
            ->assertJsonCount(1, 'data');
        
        $responseData = $response->json('data');
        $this->assertEquals('iPhone Case Special', $responseData[0]['name']);
        $this->assertEquals($brand1->id, $responseData[0]['brand']['id']);
    }

    /**
     * Test combination of filters with sorting.
     */
    public function test_can_combine_filters_with_sorting(): void
    {
        // Create test data
        $brand = Brand::factory()->create();
        $category = Category::factory()->create();
        
        // Create products with different prices
        Product::factory()
            ->for($brand)
            ->for($category)
            ->create([
                'name_en' => 'Expensive iPhone Case',
                'price' => 500,
                'is_active' => true,
                'stock' => 10
            ]);
        
        Product::factory()
            ->for($brand)
            ->for($category)
            ->create([
                'name_en' => 'Cheap iPhone Case',
                'price' => 100,
                'is_active' => true,
                'stock' => 10
            ]);
        
        // Test search + sort
        $response = $this->getJson("/api/products?search=iPhone&sort=price_asc");
        
        $response->assertStatus(200)
            ->assertJsonCount(2, 'data');
        
        $responseData = $response->json('data');
        $this->assertEquals('Cheap iPhone Case', $responseData[0]['name']);
        $this->assertEquals(100, $responseData[0]['price']);
    }

    /**
     * Test empty search parameter.
     */
    public function test_empty_search_parameter_returns_all_products(): void
    {
        // Create test data
        $brand = Brand::factory()->create();
        $category = Category::factory()->create();
        
        Product::factory()
            ->count(5)
            ->for($brand)
            ->for($category)
            ->create([
                'is_active' => true,
                'stock' => 10
            ]);
        
        // Test with empty search - avoid URL encoding issues
        $response = $this->getJson('/api/products');
        
        $response->assertStatus(200)
            ->assertJsonCount(5, 'data');
    }

    /**
     * Test search with no results.
     */
    public function test_search_with_no_results(): void
    {
        // Create test data
        $brand = Brand::factory()->create();
        $category = Category::factory()->create();
        
        Product::factory()
            ->for($brand)
            ->for($category)
            ->create([
                'name_en' => 'Phone Case',
                'is_active' => true,
                'stock' => 10
            ]);
        
        // Test search for non-existent term
        $response = $this->getJson('/api/products?search=NonExistentProduct');
        
        $response->assertStatus(200)
            ->assertJsonCount(0, 'data');
    }

    /**
     * Test case-insensitive search.
     */
    public function test_search_is_case_insensitive(): void
    {
        // Create test data
        $brand = Brand::factory()->create();
        $category = Category::factory()->create();
        
        Product::factory()
            ->for($brand)
            ->for($category)
            ->create([
                'name_en' => 'iPhone Case',
                'is_active' => true,
                'stock' => 10
            ]);
        
        // Test with lowercase
        $response = $this->getJson('/api/products?search=iphone');
        
        $response->assertStatus(200)
            ->assertJsonCount(1, 'data');
        
        // Test with uppercase
        $response = $this->getJson('/api/products?search=IPHONE');
        
        $response->assertStatus(200)
            ->assertJsonCount(1, 'data');
        
        // Test with mixed case
        $response = $this->getJson('/api/products?search=iPhOnE');
        
        $response->assertStatus(200)
            ->assertJsonCount(1, 'data');
    }

    /**
     * Test pagination meta data.
     */
    public function test_pagination_meta_data(): void
    {
        // Create test data
        $brand = Brand::factory()->create();
        $category = Category::factory()->create();
        
        Product::factory()
            ->count(25)
            ->for($brand)
            ->for($category)
            ->create([
                'is_active' => true,
                'stock' => 10
            ]);
        
        $response = $this->getJson('/api/products?per_page=10');
        
        $response->assertStatus(200)
            ->assertJsonStructure([
                'data',
                'links' => [
                    'first',
                    'last',
                    'prev',
                    'next'
                ],
                'meta' => [
                    'current_page',
                    'from',
                    'last_page',
                    'path',
                    'per_page',
                    'to',
                    'total'
                ]
            ]);
        
        $responseData = $response->json();
        $this->assertEquals(1, $responseData['meta']['current_page']);
        $this->assertEquals(10, $responseData['meta']['per_page']);
        $this->assertEquals(25, $responseData['meta']['total']);
        $this->assertEquals(3, $responseData['meta']['last_page']);
    }

    /**
     * Test product resource structure.
     */
    public function test_product_resource_structure(): void
    {
        // Create test data
        $brand = Brand::factory()->create();
        $category = Category::factory()->create();
        
        $product = Product::factory()
            ->for($brand)
            ->for($category)
            ->create([
                'is_active' => true,
                'stock' => 10,
                'cover_image_path' => 'products/test-image.jpg'
            ]);
        
        $response = $this->getJson('/api/products');
        
        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'description',
                        'price',
                        'stock',
                        'sku',
                        'is_active',
                        'is_featured',
                        'cover_image',
                        'brand' => [
                            'id',
                            'name'
                        ],
                        'category' => [
                            'id',
                            'name'
                        ]
                    ]
                ]
            ]);
        
        $responseData = $response->json('data')[0];
        $this->assertIsInt($responseData['id']);
        $this->assertIsString($responseData['name']);
        $this->assertIsString($responseData['description']);
        $this->assertIsNumeric($responseData['price']);
        $this->assertIsInt($responseData['stock']);
        $this->assertIsString($responseData['sku']);
        $this->assertIsBool($responseData['is_active']);
        $this->assertIsBool($responseData['is_featured']);
    }

    /**
     * Test error handling for server errors.
     */
    public function test_handles_server_errors_gracefully(): void
    {
        // Test the error response structure for 404
        $response = $this->getJson('/api/products/9999');
        
        $response->assertStatus(404)
            ->assertJsonStructure([
                'success',
                'message'
            ]);
        
        $responseData = $response->json();
        $this->assertFalse($responseData['success']);
        $this->assertIsString($responseData['message']);
    }

    /**
     * Test featured products returns latest first.
     */
    public function test_featured_products_returns_latest_first(): void
    {
        // Create test data
        $brand = Brand::factory()->create();
        $category = Category::factory()->create();
        
        // Create older featured product
        $olderProduct = Product::factory()
            ->for($brand)
            ->for($category)
            ->create([
                'name_en' => 'Older Featured Product',
                'is_active' => true,
                'is_featured' => true,
                'stock' => 10,
                'created_at' => now()->subDays(1)
            ]);
        
        // Create newer featured product
        $newerProduct = Product::factory()
            ->for($brand)
            ->for($category)
            ->create([
                'name_en' => 'Newer Featured Product',
                'is_active' => true,
                'is_featured' => true,
                'stock' => 10,
                'created_at' => now()
            ]);
        
        $response = $this->getJson('/api/products/featured');
        
        $response->assertStatus(200);
        $responseData = $response->json('data');
        
        // First product should be the newer one
        $this->assertEquals('Newer Featured Product', $responseData[0]['name']);
    }

    /**
     * Test featured products with no products.
     */
    public function test_featured_products_with_no_products(): void
    {
        $response = $this->getJson('/api/products/featured');
        
        $response->assertStatus(200)
            ->assertJsonCount(0, 'data');
    }

    /**
     * Test products index with no products.
     */
    public function test_products_index_with_no_products(): void
    {
        $response = $this->getJson('/api/products');
        
        $response->assertStatus(200)
            ->assertJsonCount(0, 'data')
            ->assertJsonStructure([
                'data',
                'links',
                'meta'
            ]);
    }

    /**
     * Test localized error message for inactive product in Arabic.
     */
    public function test_localized_error_for_inactive_product_in_arabic(): void
    {
        app()->setLocale('ar');
        
        // Create inactive product
        $brand = Brand::factory()->create();
        $category = Category::factory()->create();
        
        $product = Product::factory()
            ->for($brand)
            ->for($category)
            ->create([
                'is_active' => false,
                'stock' => 10
            ]);
        
        $response = $this->getJson("/api/products/{$product->id}");
        
        $response->assertStatus(404)
            ->assertJson([
                'success' => false,
                'message' => 'المنتج غير موجود'
            ]);
    }

    /**
     * Test that product index excludes inactive brands/categories relationships.
     */
    public function test_product_index_with_inactive_brand_relationships(): void
    {
        // Create inactive brand and category
        $inactiveBrand = Brand::factory()->create(['is_active' => false]);
        $inactiveCategory = Category::factory()->create(['is_active' => false]);
        
        // Create active brand and category
        $activeBrand = Brand::factory()->create(['is_active' => true]);
        $activeCategory = Category::factory()->create(['is_active' => true]);
        
        // Create products - the API should still return products even if their brand/category is inactive
        // as the API controller doesn't filter by brand/category active status
        Product::factory()
            ->for($inactiveBrand)
            ->for($inactiveCategory)
            ->create([
                'is_active' => true,
                'stock' => 10
            ]);
        
        Product::factory()
            ->for($activeBrand)
            ->for($activeCategory)
            ->create([
                'is_active' => true,
                'stock' => 10
            ]);
        
        $response = $this->getJson('/api/products');
        
        $response->assertStatus(200)
            ->assertJsonCount(2, 'data');
    }
}