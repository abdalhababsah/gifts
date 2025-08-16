<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Services\Admin\BrandService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class BrandController extends Controller
{
    public function __construct(
        private BrandService $brandService
    ) {
    }

    /**
     * Display a listing of brands.
     */
    public function index(Request $request): View
    {
        $brands = $this->brandService->getPaginated($request);
        $statistics = $this->brandService->getStatistics();

        return view('admin.brands.index', compact('brands', 'statistics'));
    }

    /**
     * Store a newly created brand.
     */
    public function store(Request $request): RedirectResponse
    {
        try {
            $validated = $request->validate([
                'name_en' => 'required|string|max:150|unique:brands,name_en',
                'name_ar' => 'required|string|max:150|unique:brands,name_ar',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'is_active' => 'boolean',
            ]);

            $validated['is_active'] = $request->has('is_active');

            if ($request->hasFile('image')) {
                $validated['image'] = $request->file('image');
            }

            $brand = $this->brandService->create($validated);

            return redirect()->route('admin.brands.index')
                ->with('success', 'Brand created successfully.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Failed to create brand: ' . $e->getMessage()]);
        }
    }

    /**
     * Update the specified brand.
     */
    public function update(Request $request, Brand $brand): RedirectResponse
    {
        try {
            $validated = $request->validate([
                'name_en' => 'required|string|max:150|unique:brands,name_en,' . $brand->id,
                'name_ar' => 'required|string|max:150|unique:brands,name_ar,' . $brand->id,
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'is_active' => 'boolean',
            ]);

            $validated['is_active'] = $request->has('is_active');

            if ($request->hasFile('image')) {
                $validated['image'] = $request->file('image');
            }

            $brand = $this->brandService->update($brand, $validated);

            return redirect()->route('admin.brands.index')
                ->with('success', 'Brand updated successfully.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Failed to update brand: ' . $e->getMessage()]);
        }
    }

    /**
     * Remove the specified brand.
     */
    public function destroy(Brand $brand): RedirectResponse
    {
        if ($brand->products()->exists()) {
            return redirect()->route('admin.brands.index')
                ->with('error', 'Cannot delete brand that has associated products.');
        }

        try {
            $this->brandService->delete($brand);

            return redirect()->route('admin.brands.index')
                ->with('success', 'Brand deleted successfully.');

        } catch (\Exception $e) {
            return redirect()->route('admin.brands.index')
                ->with('error', 'Failed to delete brand: ' . $e->getMessage());
        }
    }

    /**
     * Toggle brand status.
     */
    public function toggleStatus(Brand $brand): RedirectResponse
    {
        try {
            $brand = $this->brandService->toggleStatus($brand);
            $newStatus = $brand->is_active ? 'active' : 'inactive';
            
            return redirect()->route('admin.brands.index')
                ->with('success', "Brand '{$brand->name_en}' is now {$newStatus}.");
                
        } catch (\Exception $e) {
            return redirect()->route('admin.brands.index')
                ->with('error', 'Failed to update brand status: ' . $e->getMessage());
        }
    }
}