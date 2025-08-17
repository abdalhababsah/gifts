<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Services\Admin\BrandService;
use App\Services\Admin\CategoryService;
use App\Services\Admin\ProductService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function __construct(
        private ProductService $productService,
        private BrandService $brandService,
        private CategoryService $categoryService
    ) {}

    /**
     * Display a listing of products.
     */
    public function index(Request $request): View
    {
        $products = $this->productService->getPaginated($request);
        $statistics = $this->productService->getStatistics();
        $categories = $this->categoryService->getForSelect();
        $brands = $this->brandService->getForSelect();

        return view('admin.products.index', compact('products', 'statistics', 'categories', 'brands'));
    }

    /**
     * Show the form for creating a new product.
     */
    public function create(): View
    {
        $categories = $this->categoryService->getForSelect();
        $brands = $this->brandService->getForSelect();

        return view('admin.products.create', compact('categories', 'brands'));
    }

    /**
     * Store a newly created product.
     */
    public function store(Request $request)
    {
        // Check if this is an AJAX request
        if ($request->ajax()) {
            try {
                $validated = $request->validate([
                    'name_en' => 'required|string|max:255',
                    'name_ar' => 'required|string|max:255',
                    'description_en' => 'nullable|string',
                    'description_ar' => 'nullable|string',
                    'price' => 'required|numeric|min:0',
                    'stock' => 'required|integer|min:0',
                    'sku' => 'nullable|string|max:100|unique:products,sku',
                    'category_id' => 'required|exists:categories,id',
                    'brand_id' => 'required|exists:brands,id',
                    'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:10240',
                    'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:10240',
                    'is_active' => 'boolean',
                    'is_featured' => 'boolean',
                ]);

                $validated['is_active'] = $request->has('is_active') || $request->input('is_active') == '1';
                $validated['is_featured'] = $request->has('is_featured') || $request->input('is_featured') == '1';

                if ($request->hasFile('cover_image')) {
                    $validated['cover_image'] = $request->file('cover_image');
                }

                if ($request->hasFile('images')) {
                    $validated['images'] = $request->file('images');
                }

                $product = $this->productService->create($validated);

                return response()->json([
                    'success' => true,
                    'message' => 'Product created successfully',
                    'redirect' => route('admin.products.index'),
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to create product: '.$e->getMessage(),
                ], 422);
            }
        }

        try {
            $validated = $request->validate([
                'name_en' => 'required|string|max:255',
                'name_ar' => 'required|string|max:255',
                'description_en' => 'nullable|string',
                'description_ar' => 'nullable|string',
                'price' => 'required|numeric|min:0',
                'stock' => 'required|integer|min:0',
                'sku' => 'nullable|string|max:100|unique:products,sku',
                'category_id' => 'required|exists:categories,id',
                'brand_id' => 'required|exists:brands,id',
                'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:10240',
                'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:10240',
                'is_active' => 'boolean',
                'is_featured' => 'boolean',
            ]);

            $validated['is_active'] = $request->has('is_active') || $request->input('is_active') == '1';
            $validated['is_featured'] = $request->has('is_featured') || $request->input('is_featured') == '1';

            if ($request->hasFile('cover_image')) {
                $validated['cover_image'] = $request->file('cover_image');
            }

            if ($request->hasFile('images')) {
                $validated['images'] = $request->file('images');
            }

            $product = $this->productService->create($validated);

            return redirect()->route('admin.products.index')
                ->with('success', 'Product created successfully.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Failed to create product: '.$e->getMessage()]);
        }
    }

    /**
     * Display the specified product.
     */
    public function show(Product $product): RedirectResponse
    {
        return redirect()->route('admin.products.index');
    }

    /**
     * Show the form for editing the specified product.
     */
    public function edit(Product $product): View
    {
        $categories = $this->categoryService->getForSelect();
        $brands = $this->brandService->getForSelect();

        return view('admin.products.edit', compact('product', 'categories', 'brands'));
    }

    /**
     * Update the specified product.
     */
    public function update(Request $request, Product $product)
    {
        // Check if this is an AJAX request
        if ($request->ajax()) {
            try {
                $validated = $request->validate([
                    'name_en' => 'required|string|max:255',
                    'name_ar' => 'required|string|max:255',
                    'description_en' => 'nullable|string',
                    'description_ar' => 'nullable|string',
                    'price' => 'required|numeric|min:0',
                    'stock' => 'required|integer|min:0',
                    'sku' => 'nullable|string|max:100|unique:products,sku,'.$product->id,
                    'category_id' => 'required|exists:categories,id',
                    'brand_id' => 'required|exists:brands,id',
                    'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:10240',
                    'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:10240',
                    'is_active' => 'boolean',
                    'is_featured' => 'boolean',
                ]);

                $validated['is_active'] = $request->has('is_active') || $request->input('is_active') == '1';
                $validated['is_featured'] = $request->has('is_featured') || $request->input('is_featured') == '1';

                if ($request->hasFile('cover_image')) {
                    $validated['cover_image'] = $request->file('cover_image');
                }

                if ($request->hasFile('images')) {
                    $validated['images'] = $request->file('images');
                }

                // Handle image deletion
                if ($request->has('delete_images')) {
                    $validated['delete_images'] = $request->input('delete_images');
                }

                $product = $this->productService->update($product, $validated);

                return response()->json([
                    'success' => true,
                    'message' => 'Product updated successfully',
                    'redirect' => route('admin.products.index'),
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to update product: '.$e->getMessage(),
                ], 422);
            }
        }

        // Debug to see if images are reaching the backend

        try {
            $validated = $request->validate([
                'name_en' => 'required|string|max:255',
                'name_ar' => 'required|string|max:255',
                'description_en' => 'nullable|string',
                'description_ar' => 'nullable|string',
                'price' => 'required|numeric|min:0',
                'stock' => 'required|integer|min:0',
                'sku' => 'nullable|string|max:100|unique:products,sku,'.$product->id,
                'category_id' => 'required|exists:categories,id',
                'brand_id' => 'required|exists:brands,id',
                'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:10240',
                'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:10240',
                'is_active' => 'boolean',
                'is_featured' => 'boolean',
            ]);
            $validated['is_active'] = $request->has('is_active') || $request->input('is_active') == '1';
            $validated['is_featured'] = $request->has('is_featured') || $request->input('is_featured') == '1';

            if ($request->hasFile('cover_image')) {
                $validated['cover_image'] = $request->file('cover_image');
            }

            if ($request->hasFile('images')) {
                $validated['images'] = $request->file('images');
            }

            $product = $this->productService->update($product, $validated);

            return redirect()->route('admin.products.index')
                ->with('success', 'Product updated successfully.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Failed to update product: '.$e->getMessage()]);
        }
    }

    /**
     * Remove the specified product.
     */
    public function destroy(Product $product): RedirectResponse
    {
        try {
            $this->productService->delete($product);

            return redirect()->route('admin.products.index')
                ->with('success', 'Product deleted successfully.');

        } catch (\Exception $e) {
            return redirect()->route('admin.products.index')
                ->with('error', 'Failed to delete product: '.$e->getMessage());
        }
    }

    /**
     * Toggle product status.
     */
    public function toggleStatus(Product $product): RedirectResponse
    {
        try {
            $product = $this->productService->toggleStatus($product);
            $newStatus = $product->is_active ? 'active' : 'inactive';

            return redirect()->route('admin.products.index')
                ->with('success', "Product '{$product->name_en}' is now {$newStatus}.");

        } catch (\Exception $e) {
            return redirect()->route('admin.products.index')
                ->with('error', 'Failed to update product status: '.$e->getMessage());
        }
    }

    /**
     * Toggle product featured status.
     */
    public function toggleFeatured(Product $product): RedirectResponse
    {
        try {
            $product = $this->productService->toggleFeatured($product);
            $newStatus = $product->is_featured ? 'featured' : 'unfeatured';

            return redirect()->route('admin.products.index')
                ->with('success', "Product '{$product->name_en}' is now {$newStatus}.");

        } catch (\Exception $e) {
            return redirect()->route('admin.products.index')
                ->with('error', 'Failed to update product featured status: '.$e->getMessage());
        }
    }

    /**
     * Get products for dropdown/select.
     */
    public function getForSelect(): JsonResponse
    {
        $products = $this->productService->getForSelect();

        return response()->json([
            'success' => true,
            'data' => $products,
        ]);
    }
}
