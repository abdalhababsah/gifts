<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\AddToWishlistRequest;
use App\Services\Api\WishlistService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WishlistController extends ApiController
{
    public function __construct(private WishlistService $wishlistService)
    {
    }

    /**
     * Add product to wishlist
     */
    public function store(AddToWishlistRequest $request): JsonResponse
    {
        $result = $this->wishlistService->addToWishlist(
            $request,
            (int) $request->validated('product_id')
        );

        return response()->json($result, $result['success'] ? 201 : 422);
    }

    /**
     * Get wishlist items
     */
    public function index(Request $request): JsonResponse
    {
        $result = $this->wishlistService->getWishlistItems($request);
        return response()->json($result);
    }

    /**
     * Remove item from wishlist
     */
    public function destroy(Request $request, int $productId): JsonResponse
    {
        $result = $this->wishlistService->removeFromWishlist($request, $productId);
        return response()->json($result, $result['success'] ? 200 : 422);
    }

    /**
     * Clear entire wishlist
     */
    public function clear(Request $request): JsonResponse
    {
        $result = $this->wishlistService->clearWishlist($request);
        return response()->json($result);
    }

    /**
     * Get wishlist item count
     */
    public function count(Request $request): JsonResponse
    {
        $count = $this->wishlistService->getWishlistCount($request);

        return response()->json([
            'success' => true,
            'count'   => $count,
        ]);
    }

    /**
     * Check if product is in wishlist
     */
    public function check(Request $request, int $productId): JsonResponse
    {
        $isInWishlist = $this->wishlistService->isInWishlist($request, $productId);

        return response()->json([
            'success'      => true,
            'in_wishlist'  => $isInWishlist,
        ]);
    }
}
