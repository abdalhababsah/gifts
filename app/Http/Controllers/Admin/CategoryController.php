<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Services\Admin\CategoryService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class CategoryController extends Controller
{
    public function __construct(
        private CategoryService $categoryService
    ) {
       
    }

    /**
     * Display a listing of categories.
     */
    public function index(Request $request): View
    {
        $categories = $this->categoryService->getPaginated($request);
        $statistics = $this->categoryService->getStatistics();

        return view('admin.categories.index', compact('categories', 'statistics'));
    }

    /**
     * Store a newly created category.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name_en' => 'required|string|max:150|unique:categories,name_en',
                'name_ar' => 'required|string|max:150|unique:categories,name_ar',
                'is_active' => 'boolean',
            ]);

            $validated['is_active'] = $validated['is_active'] ?? true;

            $category = $this->categoryService->create($validated);

            

            return redirect()->route('admin.categories.index')
                ->with('success', 'Category created successfully.');

        } catch (\Exception $e) {

            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to create category: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified category.
     */
    public function show(Category $category): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $category
        ]);
    }

    /**
     * Update the specified category.
     */
    public function update(Request $request, Category $category)
    {
        try {
            $validated = $request->validate([
                'name_en' => 'required|string|max:150|unique:categories,name_en,' . $category->id,
                'name_ar' => 'required|string|max:150|unique:categories,name_ar,' . $category->id,
                'is_active' => 'boolean',
            ]);

            $category = $this->categoryService->update($category, $validated);

            return redirect()->route('admin.categories.index')
                ->with('success', 'Category updated successfully.');

        } catch (\Exception $e) {

            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to update category: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified category.
     */
    public function destroy(Request $request, Category $category)
    {
        try {
            $this->categoryService->delete($category);

            

            return redirect()->route('admin.categories.index')
                ->with('success', 'Category deleted successfully.');

        } catch (\Exception $e) {
            return redirect()->route('admin.categories.index')
                ->with('error', 'Failed to delete category: ' . $e->getMessage());
        }
    }

    /**
     * Toggle category status.
     */
    public function toggleStatus(Request $request, Category $category)
    {
        try {
            $category = $this->categoryService->toggleStatus($category);
            $newStatus = $category->is_active ? 'active' : 'inactive';
            
            return redirect()->route('admin.categories.index')
                ->with('success', "Category '{$category->name_en}' is now {$newStatus}.");
                
        } catch (\Exception $e) {
            return redirect()->route('admin.categories.index')
                ->with('error', 'Failed to update category status: ' . $e->getMessage());
        }
    }

    /**
     * Get categories for dropdown/select.
     */
    public function getForSelect(): JsonResponse
    {
        $categories = $this->categoryService->getForSelect();

        return response()->json([
            'success' => true,
            'data' => $categories
        ]);
    }
}
