<?php

declare(strict_types=1);

namespace App\Services\Api;

use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Models\User;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class WishlistService
{
    private string $guestWishlistCookie = 'guest_wishlist';
    private int $cookieLifetimeMinutes = 60 * 24 * 30; // 30 days

    /**
     * Resolve current user from Sanctum token OR session (guard-aware).
     */
    private function resolveUser(Request $request): ?User
    {
        if ($u = $request->user('sanctum')) {
            return $u;
        }
        if ($u = $request->user()) {
            return $u;
        }
        if ($u = auth('sanctum')->user()) {
            return $u;
        }
        return null;
    }

    /**
     * Add product to wishlist (guest via cookie, auth via DB)
     */
    public function addToWishlist(Request $request, int $productId): array
    {
        $product = Product::with(['brand', 'category', 'images'])
            ->where('id', $productId)
            ->active()
            ->first();

        if (!$product) {
            return [
                'success' => false,
                'message' => 'Product not found or inactive',
                'error_code' => 'PRODUCT_NOT_FOUND',
            ];
        }

        if ($user = $this->resolveUser($request)) {
            return $this->addToUserWishlist($user, $product);
        }

        return $this->addToGuestWishlist($request, $product);
    }

    /**
     * Add to authenticated user's wishlist (idempotent).
     */
    private function addToUserWishlist(User $user, Product $product): array
    {
        // Optional: transaction to be safe with unique constraints
        return DB::transaction(function () use ($user, $product) {
            $exists = Wishlist::where('user_id', $user->id)
                ->where('product_id', $product->id)
                ->exists();

            if ($exists) {
                return [
                    'success' => false,
                    'message' => 'Product already in wishlist',
                    'error_code' => 'ALREADY_IN_WISHLIST',
                ];
            }

            $item = Wishlist::create([
                'user_id'    => $user->id,
                'product_id' => $product->id,
                'added_at'   => now(),
            ]);

            return [
                'success' => true,
                'message' => 'Product added to wishlist successfully',
                'wishlist_item' => [
                    'id'       => $item->id,
                    'product'  => ProductResource::make($product),
                    'added_at' => $item->added_at,
                    'in_stock' => $product->stock > 0,
                ],
            ];
        });
    }

    /**
     * Add to guest wishlist (cookie-based)
     */
    private function addToGuestWishlist(Request $request, Product $product): array
    {
        $guestWishlist = $this->getGuestWishlistFromCookie($request);
        $pid = $product->id;

        if (isset($guestWishlist[$pid])) {
            return [
                'success' => false,
                'message' => 'Product already in wishlist',
                'error_code' => 'ALREADY_IN_WISHLIST',
            ];
        }

        $guestWishlist[$pid] = [
            'product_id' => $pid,
            'added_at'   => now()->toISOString(),
        ];

        $this->setGuestWishlistCookie($guestWishlist);

        return [
            'success' => true,
            'message' => 'Product added to wishlist successfully',
            'wishlist_item' => [
                'id'       => "guest_{$pid}",
                'product'  => ProductResource::make($product),
                'added_at' => $guestWishlist[$pid]['added_at'],
                'in_stock' => $product->stock > 0,
            ],
        ];
    }

    /**
     * Get wishlist items (auth vs guest)
     */
    public function getWishlistItems(Request $request): array
    {
        if ($user = $this->resolveUser($request)) {
            return $this->getUserWishlistItems($user);
        }
        return $this->getGuestWishlistItems($request);
    }

    /**
     * Authenticated user's wishlist
     */
    private function getUserWishlistItems(User $user): array
    {
        $items = Wishlist::with(['product.brand', 'product.category', 'product.images'])
            ->where('user_id', $user->id)
            ->orderBy('added_at', 'desc')
            ->get();

        $validated = [];

        foreach ($items as $wi) {
            $product = $wi->product;

            if (!$product || !$product->is_active) {
                $wi->delete();
                continue;
            }

            $validated[] = [
                'id'       => $wi->id,
                'product'  => ProductResource::make($product),
                'added_at' => $wi->added_at,
                'in_stock' => $product->stock > 0,
            ];
        }

        return [
            'success'        => true,
            'wishlist_items' => $validated,
            'total_items'    => count($validated),
        ];
    }

    /**
     * Guest wishlist from cookie
     */
    private function getGuestWishlistItems(Request $request): array
    {
        $guestWishlist = $this->getGuestWishlistFromCookie($request);
        $validated = [];
        $changed = false;

        foreach ($guestWishlist as $productId => $row) {
            $product = Product::with(['brand', 'category', 'images'])
                ->where('id', (int) $productId)
                ->active()
                ->first();

            if (!$product) {
                unset($guestWishlist[$productId]);
                $changed = true;
                continue;
            }

            $validated[] = [
                'id'       => "guest_{$productId}",
                'product'  => ProductResource::make($product),
                'added_at' => $row['added_at'] ?? null,
                'in_stock' => $product->stock > 0,
            ];
        }

        if ($changed) {
            $this->setGuestWishlistCookie($guestWishlist);
        }

        return [
            'success'        => true,
            'wishlist_items' => $validated,
            'total_items'    => count($validated),
        ];
    }

    /**
     * Remove from wishlist
     */
    public function removeFromWishlist(Request $request, int $productId): array
    {
        if ($user = $this->resolveUser($request)) {
            $wi = Wishlist::where('user_id', $user->id)
                ->where('product_id', $productId)
                ->first();

            if (!$wi) {
                return [
                    'success' => false,
                    'message' => 'Product not found in wishlist',
                    'error_code' => 'ITEM_NOT_IN_WISHLIST',
                ];
            }

            $wi->delete();

            return [
                'success' => true,
                'message' => 'Product removed from wishlist successfully',
            ];
        }

        // guest
        $guestWishlist = $this->getGuestWishlistFromCookie($request);

        if (!isset($guestWishlist[$productId])) {
            return [
                'success' => false,
                'message' => 'Product not found in wishlist',
                'error_code' => 'ITEM_NOT_IN_WISHLIST',
            ];
        }

        unset($guestWishlist[$productId]);
        $this->setGuestWishlistCookie($guestWishlist);

        return [
            'success' => true,
            'message' => 'Product removed from wishlist successfully',
        ];
    }

    /**
     * Clear entire wishlist
     */
    public function clearWishlist(Request $request): array
    {
        if ($user = $this->resolveUser($request)) {
            Wishlist::where('user_id', $user->id)->delete();

            return [
                'success' => true,
                'message' => 'Wishlist cleared successfully',
            ];
        }

        $this->setGuestWishlistCookie([]); // clear cookie

        return [
            'success' => true,
            'message' => 'Wishlist cleared successfully',
        ];
    }

    /**
     * Get wishlist item count (distinct products)
     */
    public function getWishlistCount(Request $request): int
    {
        if ($user = $this->resolveUser($request)) {
            return (int) Wishlist::where('user_id', $user->id)->count();
        }

        $guestWishlist = $this->getGuestWishlistFromCookie($request);
        return (int) count($guestWishlist);
    }

    /**
     * Check if product is in wishlist
     */
    public function isInWishlist(Request $request, int $productId): bool
    {
        if ($user = $this->resolveUser($request)) {
            return Wishlist::where('user_id', $user->id)
                ->where('product_id', $productId)
                ->exists();
        }

        $guestWishlist = $this->getGuestWishlistFromCookie($request);
        return isset($guestWishlist[$productId]);
    }

    /**
     * Migrate guest wishlist to user wishlist on login/registration
     */
    public function migrateGuestWishlistToUser(Request $request, User $user): array
    {
        $guestWishlist = $this->getGuestWishlistFromCookie($request);

        if (empty($guestWishlist)) {
            return [
                'success' => true,
                'message' => 'No guest wishlist to migrate',
                'migrated_items' => 0,
            ];
        }

        $migrated = 0;
        $errors = [];

        DB::transaction(function () use ($guestWishlist, $user, &$migrated, &$errors) {
            foreach (array_keys($guestWishlist) as $productId) {
                try {
                    $product = Product::where('id', (int) $productId)->active()->first();
                    if (!$product) {
                        $errors[] = "Product ID {$productId} not found";
                        continue;
                    }

                    $exists = Wishlist::where('user_id', $user->id)
                        ->where('product_id', (int) $productId)
                        ->exists();

                    if (!$exists) {
                        Wishlist::create([
                            'user_id'    => $user->id,
                            'product_id' => (int) $productId,
                            'added_at'   => now(),
                        ]);
                        $migrated++;
                    }
                } catch (\Throwable $e) {
                    $errors[] = "Error migrating product ID {$productId}: {$e->getMessage()}";
                }
            }
        });

        // clear cookie after migration
        $this->setGuestWishlistCookie([]);

        return [
            'success' => true,
            'message' => 'Guest wishlist migrated successfully',
            'migrated_items' => $migrated,
            'errors' => $errors,
        ];
    }

    // -------------------
    // Cookie helpers
    // -------------------

    private function getGuestWishlistFromCookie(Request $request): array
    {
        $raw = $request->cookie($this->guestWishlistCookie);
        if (!$raw) {
            return [];
        }

        try {
            $decoded = json_decode($raw, true, 512, JSON_THROW_ON_ERROR);
            return is_array($decoded) ? $decoded : [];
        } catch (\Throwable $e) {
            Log::warning('guest_wishlist_cookie_decode_failed', ['error' => $e->getMessage()]);
            return [];
        }
    }

    private function setGuestWishlistCookie(array $wishlist): void
    {
        $secure   = (bool) config('session.secure', false);
        $sameSite = config('session.same_site', 'lax') ?: 'lax';
        $domain   = config('session.domain'); // null by default
        $path     = '/';
        $httpOnly = false; // allow mobile client inspection if needed

        Cookie::queue(
            $this->guestWishlistCookie,
            json_encode($wishlist, JSON_UNESCAPED_UNICODE),
            $this->cookieLifetimeMinutes,
            $path,
            $domain,
            $secure,
            $httpOnly,
            false,
            $sameSite
        );
    }
}
