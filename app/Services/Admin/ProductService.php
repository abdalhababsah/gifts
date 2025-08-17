<?php

namespace App\Services\Admin;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductService
{
    /**
     * Get paginated products with filters.
     */
    public function getPaginated(Request $request): LengthAwarePaginator
    {
        $query = Product::with(['brand', 'category']);

        // Apply search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name_en', 'like', "%{$search}%")
                    ->orWhere('name_ar', 'like', "%{$search}%")
                    ->orWhere('sku', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%");
            });
        }

        // Apply status filter
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        // Apply featured filter
        if ($request->filled('featured')) {
            $query->where('is_featured', $request->featured === 'yes');
        }

        // Apply stock filter
        if ($request->filled('stock_status')) {
            if ($request->stock_status === 'in_stock') {
                $query->where('stock', '>', 0);
            } elseif ($request->stock_status === 'out_of_stock') {
                $query->where('stock', '<=', 0);
            }
        }

        // Apply category filter
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Apply brand filter
        if ($request->filled('brand_id')) {
            $query->where('brand_id', $request->brand_id);
        }

        // Apply sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortDirection = $request->get('sort_direction', 'desc');

        if (in_array($sortBy, ['name_en', 'name_ar', 'sku', 'price', 'stock', 'is_active', 'is_featured', 'created_at'])) {
            $query->orderBy($sortBy, $sortDirection);
        }

        return $query->paginate($request->get('per_page', 15))
            ->withQueryString();
    }

    /**
     * Create a new product.
     */
    public function create(array $data): Product
    {
        $data['slug'] = $this->generateUniqueSlug($data['name_en']);
        $data['sku'] = $this->generateUniqueSku($data['sku'] ?? null);

        // Handle cover image upload
        if (isset($data['cover_image']) && $data['cover_image'] instanceof UploadedFile) {
            $data['cover_image_path'] = $this->storeCoverImage($data['cover_image']);
            unset($data['cover_image']);
        }

        // Handle multiple images
        $images = $data['images'] ?? [];
        unset($data['images']);

        $product = Product::create($data);

        // Store additional images
        if (! empty($images)) {
            $this->handleProductImages($product, $images);
        }

        return $product;
    }

    public function update(Product $product, array $data): Product
    {
        // Update slug only if name_en changed
        if (isset($data['name_en']) && $data['name_en'] !== $product->name_en) {
            $data['slug'] = $this->generateUniqueSlug($data['name_en'], $product->id);
        }

        // Update SKU only if changed
        if (isset($data['sku']) && $data['sku'] !== $product->sku) {
            $data['sku'] = $this->generateUniqueSku($data['sku'], $product->id);
        }

        // Handle cover image upload
        if (isset($data['cover_image']) && $data['cover_image'] instanceof UploadedFile) {
            // Delete old image
            if ($product->cover_image_path) {
                $this->deleteCoverImage($product->cover_image_path);
            }

            $data['cover_image_path'] = $this->storeCoverImage($data['cover_image']);
            unset($data['cover_image']);
        }

        // Handle image deletion
        if (isset($data['delete_images']) && ! empty($data['delete_images'])) {
            foreach ($data['delete_images'] as $imageId) {
                $image = $product->images()->find($imageId);
                if ($image) {
                    // Delete the image file
                    if ($image->image_path) {
                        Storage::disk('public')->delete($image->image_path);
                    }
                    // Delete the database record
                    $image->delete();
                }
            }
            unset($data['delete_images']);
        }

        // Handle multiple images
        $images = $data['images'] ?? [];
        unset($data['images']);

        $product->update($data);

        // Store additional images
        if (! empty($images)) {
            $this->handleProductImages($product, $images);
        }

        return $product->refresh();
    }

    /**
     * Delete a product.
     */
    public function delete(Product $product): bool
    {
        // Delete cover image
        if ($product->cover_image_path) {
            $this->deleteCoverImage($product->cover_image_path);
        }

        return $product->delete();
    }

    /**
     * Toggle product status.
     */
    public function toggleStatus(Product $product): Product
    {
        $product->update(['is_active' => ! $product->is_active]);

        return $product->refresh();
    }

    /**
     * Toggle product featured status.
     */
    public function toggleFeatured(Product $product): Product
    {
        $product->update(['is_featured' => ! $product->is_featured]);

        return $product->refresh();
    }

    /**
     * Get products for dropdown/select options.
     */
    public function getForSelect(): \Illuminate\Database\Eloquent\Collection
    {
        return Product::active()
            ->orderBy('name_en')
            ->get(['id', 'name_en', 'name_ar', 'sku']);
    }

    /**
     * Store uploaded cover image.
     */
    private function storeCoverImage(UploadedFile $image): string
    {
        return $image->store('products', 'public');
    }

    /**
     * Delete stored cover image.
     */
    private function deleteCoverImage(string $imagePath): bool
    {
        return Storage::disk('public')->delete($imagePath);
    }

    /**
     * Generate unique slug for product.
     */
    private function generateUniqueSlug(string $name, ?int $excludeId = null): string
    {
        $slug = Str::slug($name);
        $originalSlug = $slug;
        $counter = 1;

        while ($this->slugExists($slug, $excludeId)) {
            $slug = $originalSlug.'-'.$counter;
            $counter++;
        }

        return $slug;
    }

    /**
     * Check if slug exists.
     */
    private function slugExists(string $slug, ?int $excludeId = null): bool
    {
        $query = Product::where('slug', $slug);

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        return $query->exists();
    }

    /**
     * Generate unique SKU for product.
     */
    private function generateUniqueSku(?string $sku = null, ?int $excludeId = null): string
    {
        if (! $sku) {
            $sku = 'PRD-'.strtoupper(Str::random(6));
        }

        $originalSku = $sku;
        $counter = 1;

        while ($this->skuExists($sku, $excludeId)) {
            $sku = $originalSku.'-'.$counter;
            $counter++;
        }

        return $sku;
    }

    /**
     * Check if SKU exists.
     */
    private function skuExists(string $sku, ?int $excludeId = null): bool
    {
        $query = Product::where('sku', $sku);

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        return $query->exists();
    }

    /**
     * Get statistics for dashboard.
     */
    public function getStatistics(): array
    {
        return [
            'total' => Product::count(),
            'active' => Product::where('is_active', true)->count(),
            'inactive' => Product::where('is_active', false)->count(),
            'featured' => Product::where('is_featured', true)->count(),
            'in_stock' => Product::where('stock', '>', 0)->count(),
            'out_of_stock' => Product::where('stock', '<=', 0)->count(),
        ];
    }

    private function handleProductImages(Product $product, array $images): void
    {
        foreach ($images as $image) {
            if ($image instanceof UploadedFile) {
                $imagePath = $image->store('products/gallery', 'public');

                $product->images()->create([
                    'image_path' => $imagePath,
                    'is_primary' => false,
                ]);
            }
        }
    }
}
