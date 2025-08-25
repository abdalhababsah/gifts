<?php

namespace App\Traits;

use App\Services\Api\CartService;
use App\Services\Api\WishlistService;
use Illuminate\Http\Request;

trait HasGuestCartWishlist
{
    /**
     * Get combined cart and wishlist data
     */
    public function getCombinedData(Request $request, CartService $cartService, WishlistService $wishlistService): array
    {
        $cartData = $cartService->getCartItems($request);
        $wishlistData = $wishlistService->getWishlistItems($request);
        
        return [
            'success' => true,
            'cart' => $cartData,
            'wishlist' => $wishlistData,
            'summary' => [
                'cart_items' => $cartData['total_items'] ?? 0,
                'wishlist_items' => $wishlistData['total_items'] ?? 0,
                'cart_total' => $cartData['total_amount'] ?? 0,
                'currency' => 'JOD'
            ]
        ];
    }
}