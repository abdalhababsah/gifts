<?php
namespace App\Http\Controllers\Api;

use App\Services\Api\CartService;
use App\Services\Api\WishlistService;
use App\Traits\HasGuestCartWishlist;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ShoppingDataController extends ApiController
{
    use HasGuestCartWishlist;

    public function __construct(
        private CartService $cartService,
        private WishlistService $wishlistService
    ) {}

    /**
     * Get combined cart and wishlist data
     */
    public function summary(Request $request): JsonResponse
    {
        $data = $this->getCombinedData($request, $this->cartService, $this->wishlistService);
        return response()->json($data);
    }
}
