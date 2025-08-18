<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\ProductIndexRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ProductController extends ApiController
{
    /**
     * Display a listing of the products with filtering, sorting, and search.
     */
    public function index(ProductIndexRequest $request): AnonymousResourceCollection|JsonResponse
    {
        try {
            $query = Product::with(['brand', 'category', 'images'])
                ->active()
                ->inStock();
            
            if ($request->has('search') && $request->search) {
                $searchTerm = $request->search;
                $query->where(function (Builder $query) use ($searchTerm) {
                    $query->where('name_en', 'LIKE', "%{$searchTerm}%")
                         ->orWhere('name_ar', 'LIKE', "%{$searchTerm}%");
                });
            }
            
            // Apply brand filter
            if ($request->has('brand_id') && $request->brand_id) {
                $query->where('brand_id', $request->brand_id);
            }
            
            // Apply category filter
            if ($request->has('category_id') && $request->category_id) {
                $query->where('category_id', $request->category_id);
            }
            
            // Apply sorting
            if ($request->has('sort')) {
                switch ($request->sort) {
                    case 'price_asc':
                        $query->orderBy('price', 'asc');
                        break;
                    case 'price_desc':
                        $query->orderBy('price', 'desc');
                        break;
                    default:
                        $query->latest();
                }
            } else {
                $query->latest();
            }
            
            // Paginate results
            $perPage = $request->get('per_page', 16);
            $products = $query->paginate($perPage);
            
            return ProductResource::collection($products);
        } catch (\Exception $e) {
            $messages = [
                'en' => 'An error occurred while fetching products',
                'ar' => 'حدث خطأ أثناء جلب المنتجات',
            ];
            
            return response()->json([
                'success' => false,
                'message' => $this->getLocalizedMessage($messages),
            ], 500);
        }
    }

    /**
     * Display the specified product.
     */
    public function show(int $id): ProductResource|JsonResponse
    {
        try {
            $product = Product::with(['brand', 'category', 'images'])
                ->where('id', $id)
                ->active()
                ->first();
            
            if (!$product) {
                $messages = [
                    'en' => 'Product not found',
                    'ar' => 'المنتج غير موجود',
                ];
                
                return response()->json([
                    'success' => false,
                    'message' => $this->getLocalizedMessage($messages),
                ], 404);
            }
            
            return new ProductResource($product);
        } catch (\Exception $e) {
            $messages = [
                'en' => 'An error occurred while fetching the product',
                'ar' => 'حدث خطأ أثناء جلب المنتج',
            ];
            
            return response()->json([
                'success' => false,
                'message' => $this->getLocalizedMessage($messages),
            ], 500);
        }
    }

    /**
     * Display featured products (max 9).
     */
    public function featured(): AnonymousResourceCollection|JsonResponse
    {
        try {
            $products = Product::with(['brand', 'category', 'images'])
                ->active()
                ->inStock()
                ->featured()
                ->latest()
                ->limit(9)
                ->get();
            
            return ProductResource::collection($products);
        } catch (\Exception $e) {
            $messages = [
                'en' => 'An error occurred while fetching featured products',
                'ar' => 'حدث خطأ أثناء جلب المنتجات المميزة',
            ];
            
            return response()->json([
                'success' => false,
                'message' => $this->getLocalizedMessage($messages),
            ], 500);
        }
    }
}