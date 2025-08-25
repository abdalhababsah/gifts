<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\AddToCartRequest;
use App\Http\Requests\Api\UpdateCartRequest;
use App\Services\Api\CartService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CartController extends ApiController
{
    public function __construct(private CartService $cartService)
    {
    }

    /**
     * Add product to cart (guest via cookie, auth via DB)
     */
    public function store(AddToCartRequest $request): JsonResponse
    {
        $result = $this->cartService->addToCart(
            $request,
            (int) $request->validated('product_id'),
            (int) $request->validated('quantity', 1)
        );

        return response()->json($result, $result['success'] ? 201 : 422);
    }

    /**
     * Get cart items
     */
    public function index(Request $request): JsonResponse
    {
        $result = $this->cartService->getCartItems($request);
        return response()->json($result);
    }

    /**
     * Update cart item quantity
     */
    public function update(UpdateCartRequest $request, int $productId): JsonResponse
    {
        $result = $this->cartService->updateCartItem(
            $request,
            $productId,
            (int) $request->validated('quantity')
        );

        return response()->json($result, $result['success'] ? 200 : 422);
    }

    /**
     * Remove item from cart
     */
    public function destroy(Request $request, int $productId): JsonResponse
    {
        $result = $this->cartService->removeFromCart($request, $productId);
        return response()->json($result, $result['success'] ? 200 : 422);
    }

    /**
     * Clear entire cart
     */
    public function clear(Request $request): JsonResponse
    {
        $result = $this->cartService->clearCart($request);
        return response()->json($result);
    }

    /**
     * Get cart item count
     */
    public function count(Request $request): JsonResponse
    {
        $count = $this->cartService->getCartCount($request);

        return response()->json([
            'success' => true,
            'count'   => $count,
        ]);
    }
}
