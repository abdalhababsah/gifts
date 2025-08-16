<?php

namespace App\Services\Admin;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Pagination\LengthAwarePaginator;

class CategoryService
{
    /**
     * Get paginated categories with filters.
     */
    public function getPaginated(Request $request): LengthAwarePaginator
    {
        $query = Category::query();

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

        // Apply sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortDirection = $request->get('sort_direction', 'desc');
        
        if (in_array($sortBy, ['name_en', 'name_ar', 'slug', 'is_active', 'created_at'])) {
            $query->orderBy($sortBy, $sortDirection);
        }

        return $query->paginate($request->get('per_page', 15))
                    ->withQueryString();
    }

    /**
     * Create a new category.
     */
    public function create(array $data): Category
    {
        $data['slug'] = $this->generateUniqueSlug($data['name_en']);
        
        return Category::create($data);
    }

    /**
     * Update an existing category.
     */
    public function update(Category $category, array $data): Category
    {
        // Update slug only if name_en changed
        if (isset($data['name_en']) && $data['name_en'] !== $category->name_en) {
            $data['slug'] = $this->generateUniqueSlug($data['name_en'], $category->id);
        }

        $category->update($data);
        
        return $category->refresh();
    }

    /**
     * Delete a category.
     */
    public function delete(Category $category): bool
    {
        // Check if category has products
        if ($category->products()->exists()) {
            throw new \Exception('Cannot delete category that has products assigned to it.');
        }

        return $category->delete();
    }

    /**
     * Toggle category status.
     */
    public function toggleStatus(Category $category): Category
    {
        $category->update(['is_active' => !$category->is_active]);
        
        return $category->refresh();
    }

    /**
     * Get categories for dropdown/select options.
     */
    public function getForSelect(): \Illuminate\Database\Eloquent\Collection
    {
        return Category::active()
                      ->orderBy('name_en')
                      ->get(['id', 'name_en', 'name_ar']);
    }

    /**
     * Generate unique slug for category.
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
        $query = Category::where('slug', $slug);
        
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
            'total' => Category::count(),
            'active' => Category::where('is_active', true)->count(),
            'inactive' => Category::where('is_active', false)->count(),
            'with_products' => Category::has('products')->count(),
        ];
    }
}
