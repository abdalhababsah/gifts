<?php
namespace App\Services\Api;

use App\Models\User;
use Illuminate\Http\Request;

class GuestDataMigrationService
{
    public function __construct(
        private CartService $cartService,
        private WishlistService $wishlistService
    ) {}

    /**
     * Migrate all guest data to user account
     */
    public function migrateGuestDataToUser(Request $request, User $user): array
    {
        $cartMigration = $this->cartService->migrateGuestCartToUser($request, $user);
        $wishlistMigration = $this->wishlistService->migrateGuestWishlistToUser($request, $user);

        return [
            'success' => true,
            'message' => 'Guest data migrated successfully',
            'cart' => $cartMigration,
            'wishlist' => $wishlistMigration,
            'total_migrated_items' => $cartMigration['migrated_items'] + $wishlistMigration['migrated_items']
        ];
    }
}
