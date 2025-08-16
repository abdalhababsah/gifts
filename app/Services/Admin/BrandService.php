<?php

namespace App\Services\Admin;

use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\LengthAwarePaginator;

class BrandService
{
    /**
     * Get paginated brands with filters.
     */
    public function getPaginated(Request $request): LengthAwarePaginator
    {
        $query = Brand::withCount('products');

        // Apply search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name_en', 'like', "%{$search}%")
                  ->orWhere('name_ar', 'like', "%{$search}%")
                  ->orWhere('slug', 'like', "%{$search}%");
            });
        }

        // Apply status filter
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        // Default ordering
        $query->orderBy('created_at', 'desc');

        return $query->paginate($request->get('per_page', 15))
                    ->withQueryString();
    }

    /**
     * Create a new brand.
     */
    public function create(array $data): Brand
    {
        $data['slug'] = $this->generateUniqueSlug($data['name_en']);
        
        // Handle image upload
        if (isset($data['image']) && $data['image'] instanceof UploadedFile) {
            $data['image_path'] = $this->storeImage($data['image']);
            unset($data['image']);
        }
        
        return Brand::create($data);
    }

    /**
     * Update an existing brand.
     */
    public function update(Brand $brand, array $data): Brand
    {
        // Update slug only if name_en changed
        if (isset($data['name_en']) && $data['name_en'] !== $brand->name_en) {
            $data['slug'] = $this->generateUniqueSlug($data['name_en'], $brand->id);
        }

        // Handle image upload
        if (isset($data['image']) && $data['image'] instanceof UploadedFile) {
            // Delete old image
            if ($brand->image_path) {
                $this->deleteImage($brand->image_path);
            }
            
            $data['image_path'] = $this->storeImage($data['image']);
            unset($data['image']);
        }

        $brand->update($data);
        
        return $brand->refresh();
    }

    /**
     * Delete a brand.
     */
    public function delete(Brand $brand): bool
    {
        // Check if brand has products
        if ($brand->products()->exists()) {
            throw new \Exception('Cannot delete brand that has products assigned to it.');
        }

        // Delete brand image
        if ($brand->image_path) {
            $this->deleteImage($brand->image_path);
        }

        return $brand->delete();
    }

    /**
     * Toggle brand status.
     */
    public function toggleStatus(Brand $brand): Brand
    {
        $brand->update(['is_active' => !$brand->is_active]);
        
        return $brand->refresh();
    }

    /**
     * Get brands for dropdown/select options.
     */
    public function getForSelect(): \Illuminate\Database\Eloquent\Collection
    {
        return Brand::active()
                   ->orderBy('name_en')
                   ->get(['id', 'name_en', 'name_ar', 'image_path']);
    }

    /**
     * Store uploaded image.
     */
    private function storeImage(UploadedFile $image): string
    {
        return $image->store('brands', 'public');
    }

    /**
     * Delete stored image.
     */
    private function deleteImage(string $imagePath): bool
    {
        return Storage::disk('public')->delete($imagePath);
    }

    /**
     * Generate unique slug for brand.
     */
    private function generateUniqueSlug(string $name, ?int $excludeId = null): string
    {
        $slug = Str::slug($name);
        $originalSlug = $slug;
        $counter = 1;

        while ($this->slugExists($slug, $excludeId)) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    /**
     * Check if slug exists.
     */
    private function slugExists(string $slug, ?int $excludeId = null): bool
    {
        $query = Brand::where('slug', $slug);
        
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
            'total' => Brand::count(),
            'active' => Brand::where('is_active', true)->count(),
            'inactive' => Brand::where('is_active', false)->count(),
            'with_products' => Brand::has('products')->count(),
        ];
    }
}