<?php

declare(strict_types=1);

namespace App\Services\Api;

use App\Http\Resources\ProductResource;
use App\Models\Cart;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CartService
{
    private string $guestCartCookie = 'guest_cart';
    // 30 days
    private int $cookieLifetimeMinutes = 60 * 24 * 30;

    /**
     * Resolve the current user from Sanctum token OR session (without requiring middleware).
     */
    private function resolveUser(Request $request): ?User
    {
        // Prefer PAT/Bearer token via Sanctum
        if ($u = $request->user('sanctum')) {
            return $u;
        }
        // Fallback to session-authenticated user (web guard)
        if ($u = $request->user()) {
            return $u;
        }
        // Last-chance check (helps in some edge stacks)
        if ($u = auth('sanctum')->user()) {
            return $u;
        }
        return null;
    }

    /**
     * Add product to cart (guest via cookie, auth via DB)
     */
    public function addToCart(Request $request, int $productId, int $quantity = 1): array
    {
        $product = Product::with(['brand', 'category'])
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

        if ($product->stock < $quantity) {
            return [
                'success' => false,
                'message' => "Only {$product->stock} items available in stock",
                'error_code' => 'INSUFFICIENT_STOCK',
                'available_stock' => $product->stock,
            ];
        }

        if ($user = $this->resolveUser($request)) {
            return $this->addToUserCart($user, $product, $quantity);
        }

        return $this->addToGuestCart($request, $product, $quantity);
    }

    /**
     * Add to authenticated user's cart (transactional, stock-safe).
     */
    private function addToUserCart(User $user, Product $product, int $quantity): array
    {
        return DB::transaction(function () use ($user, $product, $quantity) {
            // Lock the row to avoid race conditions on quantity math
            $existing = Cart::where('user_id', $user->id)
                ->where('product_id', $product->id)
                ->lockForUpdate()
                ->first();

            if ($existing) {
                $newQty = $existing->quantity + $quantity;

                if ($newQty > $product->stock) {
                    $maxAddable = $product->stock - $existing->quantity;
                    if ($maxAddable <= 0) {
                        return [
                            'success' => false,
                            'message' => 'Product already in cart with maximum available quantity',
                            'error_code' => 'MAX_QUANTITY_IN_CART',
                            'current_cart_quantity' => $existing->quantity,
                            'available_stock' => $product->stock,
                        ];
                    }

                    return [
                        'success' => false,
                        'message' => "You can only add {$maxAddable} more items to cart",
                        'error_code' => 'QUANTITY_EXCEEDS_STOCK',
                        'max_addable' => $maxAddable,
                        'current_cart_quantity' => $existing->quantity,
                        'available_stock' => $product->stock,
                    ];
                }

                $existing->update([
                    'quantity' => $newQty,
                    'added_at' => now(),
                ]);

                return [
                    'success' => true,
                    'message' => 'Cart updated successfully',
                    'action' => 'updated',
                    'cart_item' => [
                        'id' => $existing->id,
                        'product' => ProductResource::make($product->load(['brand', 'category'])),
                        'quantity' => $newQty,
                        'added_at' => $existing->added_at,
                        'total_price' => $newQty * $product->price,
                    ],
                ];
            }

            $cartItem = Cart::create([
                'user_id' => $user->id,
                'product_id' => $product->id,
                'quantity' => $quantity,
                'added_at' => now(),
            ]);

            return [
                'success' => true,
                'message' => 'Product added to cart successfully',
                'action' => 'added',
                'cart_item' => [
                    'id' => $cartItem->id,
                    'product' => ProductResource::make($product->load(['brand', 'category'])),
                    'quantity' => $quantity,
                    'added_at' => $cartItem->added_at,
                    'total_price' => $quantity * $product->price,
                ],
            ];
        });
    }

    /**
     * Add to guest cart (cookie-based)
     */
    private function addToGuestCart(Request $request, Product $product, int $quantity): array
    {
        $guestCart = $this->getGuestCartFromCookie($request);
        $pid = $product->id;

        if (isset($guestCart[$pid])) {
            $newQty = (int) $guestCart[$pid]['quantity'] + $quantity;

            if ($newQty > $product->stock) {
                $maxAddable = $product->stock - (int) $guestCart[$pid]['quantity'];
                if ($maxAddable <= 0) {
                    return [
                        'success' => false,
                        'message' => 'Product already in cart with maximum available quantity',
                        'error_code' => 'MAX_QUANTITY_IN_CART',
                        'current_cart_quantity' => (int) $guestCart[$pid]['quantity'],
                        'available_stock' => $product->stock,
                    ];
                }

                return [
                    'success' => false,
                    'message' => "You can only add {$maxAddable} more items to cart",
                    'error_code' => 'QUANTITY_EXCEEDS_STOCK',
                    'max_addable' => $maxAddable,
                    'current_cart_quantity' => (int) $guestCart[$pid]['quantity'],
                    'available_stock' => $product->stock,
                ];
            }

            $guestCart[$pid]['quantity'] = $newQty;
            $guestCart[$pid]['added_at'] = now()->toISOString();
            $finalQty = $newQty;
            $action = 'updated';
        } else {
            $guestCart[$pid] = [
                'product_id' => $pid,
                'quantity' => $quantity,
                'added_at' => now()->toISOString(),
            ];
            $finalQty = $quantity;
            $action = 'added';
        }

        $this->setGuestCartCookie($guestCart);

        return [
            'success' => true,
            'message' => $action === 'added' ? 'Product added to cart successfully' : 'Cart updated successfully',
            'action' => $action,
            'cart_item' => [
                'id' => "guest_{$pid}",
                'product' => ProductResource::make($product->load(['brand', 'category'])),
                'quantity' => $finalQty,
                'added_at' => $guestCart[$pid]['added_at'],
                'total_price' => $finalQty * $product->price,
            ],
        ];
    }

    /**
     * Get cart items (auth vs guest)
     */
    public function getCartItems(Request $request): array
    {
        if ($user = $this->resolveUser($request)) {
            return $this->getUserCartItems($user);
        }
        return $this->getGuestCartItems($request);
    }

    /**
     * Authenticated user's cart
     */
    private function getUserCartItems(User $user): array
    {
        $cartItems = Cart::with(['product.brand', 'product.category', 'product.images'])
            ->where('user_id', $user->id)
            ->orderBy('added_at', 'desc')
            ->get();

        $validated = [];
        $hasStockIssues = false;

        foreach ($cartItems as $ci) {
            $product = $ci->product;

            if (!$product || !$product->is_active) {
                $ci->delete();
                $hasStockIssues = true;
                continue;
            }

            $stockIssue = null;
            if ($ci->quantity > $product->stock) {
                if ($product->stock > 0) {
                    $ci->update(['quantity' => $product->stock]);
                    $stockIssue = 'quantity_reduced';
                } else {
                    $ci->delete();
                    $hasStockIssues = true;
                    continue;
                }
            }

            $validated[] = [
                'id' => $ci->id,
                'product' => ProductResource::make($product),
                'quantity' => $ci->quantity,
                'added_at' => $ci->added_at,
                'total_price' => $ci->quantity * $product->price,
                'stock_issue' => $stockIssue,
            ];
        }

        $totalAmount = collect($validated)->sum('total_price');
        $totalItems = collect($validated)->sum('quantity');

        return [
            'success' => true,
            'cart_items' => $validated,
            'total_items' => (int) $totalItems,
            'total_amount' => (float) $totalAmount,
            'has_stock_issues' => $hasStockIssues,
            'currency' => 'JOD',
        ];
    }

    /**
     * Guest cart from cookie
     */
    private function getGuestCartItems(Request $request): array
    {
        $guestCart = $this->getGuestCartFromCookie($request);
        $validated = [];
        $hasStockIssues = false;

        foreach ($guestCart as $productId => $cartData) {
            $product = Product::with(['brand', 'category', 'images'])
                ->where('id', (int) $productId)
                ->active()
                ->first();

            if (!$product) {
                unset($guestCart[$productId]);
                $hasStockIssues = true;
                continue;
            }

            $qty = (int) ($cartData['quantity'] ?? 0);
            $stockIssue = null;

            if ($qty > $product->stock) {
                if ($product->stock > 0) {
                    $guestCart[$productId]['quantity'] = $product->stock;
                    $qty = $product->stock;
                    $stockIssue = 'quantity_reduced';
                } else {
                    unset($guestCart[$productId]);
                    $hasStockIssues = true;
                    continue;
                }
            }

            $validated[] = [
                'id' => "guest_{$productId}",
                'product' => ProductResource::make($product),
                'quantity' => $qty,
                'added_at' => $cartData['added_at'] ?? null,
                'total_price' => $qty * $product->price,
                'stock_issue' => $stockIssue,
            ];
        }

        if ($hasStockIssues) {
            $this->setGuestCartCookie($guestCart);
        }

        $totalAmount = collect($validated)->sum('total_price');
        $totalItems = collect($validated)->sum('quantity');

        return [
            'success' => true,
            'cart_items' => $validated,
            'total_items' => (int) $totalItems,
            'total_amount' => (float) $totalAmount,
            'has_stock_issues' => $hasStockIssues,
            'currency' => 'JOD',
        ];
    }

    /**
     * Update cart item
     */
    public function updateCartItem(Request $request, int $productId, int $quantity): array
    {
        $product = Product::where('id', $productId)->active()->first();

        if (!$product) {
            return [
                'success' => false,
                'message' => 'Product not found',
                'error_code' => 'PRODUCT_NOT_FOUND',
            ];
        }

        if ($quantity > $product->stock) {
            return [
                'success' => false,
                'message' => "Only {$product->stock} items available in stock",
                'error_code' => 'INSUFFICIENT_STOCK',
                'available_stock' => $product->stock,
            ];
        }

        if ($user = $this->resolveUser($request)) {
            return $this->updateUserCartItem($user, $product, $quantity);
        }

        return $this->updateGuestCartItem($request, $product, $quantity);
    }

    private function updateUserCartItem(User $user, Product $product, int $quantity): array
    {
        $ci = Cart::where('user_id', $user->id)
            ->where('product_id', $product->id)
            ->first();

        if (!$ci) {
            return [
                'success' => false,
                'message' => 'Product not found in cart',
                'error_code' => 'ITEM_NOT_IN_CART',
            ];
        }

        if ($quantity <= 0) {
            $ci->delete();
            return [
                'success' => true,
                'message' => 'Product removed from cart',
                'action' => 'removed',
            ];
        }

        $ci->update(['quantity' => $quantity]);

        return [
            'success' => true,
            'message' => 'Cart updated successfully',
            'action' => 'updated',
            'cart_item' => [
                'id' => $ci->id,
                'product' => ProductResource::make($product->load(['brand', 'category'])),
                'quantity' => $quantity,
                'added_at' => $ci->added_at,
                'total_price' => $quantity * $product->price,
            ],
        ];
    }

    private function updateGuestCartItem(Request $request, Product $product, int $quantity): array
    {
        $guestCart = $this->getGuestCartFromCookie($request);
        $pid = $product->id;

        if (!isset($guestCart[$pid])) {
            return [
                'success' => false,
                'message' => 'Product not found in cart',
                'error_code' => 'ITEM_NOT_IN_CART',
            ];
        }

        if ($quantity <= 0) {
            unset($guestCart[$pid]);
            $this->setGuestCartCookie($guestCart);

            return [
                'success' => true,
                'message' => 'Product removed from cart',
                'action' => 'removed',
            ];
        }

        $guestCart[$pid]['quantity'] = $quantity;
        $this->setGuestCartCookie($guestCart);

        return [
            'success' => true,
            'message' => 'Cart updated successfully',
            'action' => 'updated',
            'cart_item' => [
                'id' => "guest_{$pid}",
                'product' => ProductResource::make($product->load(['brand', 'category'])),
                'quantity' => $quantity,
                'added_at' => $guestCart[$pid]['added_at'] ?? null,
                'total_price' => $quantity * $product->price,
            ],
        ];
    }

    /**
     * Remove from cart
     */
    public function removeFromCart(Request $request, int $productId): array
    {
        if ($user = $this->resolveUser($request)) {
            $ci = Cart::where('user_id', $user->id)
                ->where('product_id', $productId)
                ->first();

            if (!$ci) {
                return [
                    'success' => false,
                    'message' => 'Product not found in cart',
                    'error_code' => 'ITEM_NOT_IN_CART',
                ];
            }

            $ci->delete();

            return [
                'success' => true,
                'message' => 'Product removed from cart successfully',
            ];
        }

        // guest
        $guestCart = $this->getGuestCartFromCookie($request);
        if (!isset($guestCart[$productId])) {
            return [
                'success' => false,
                'message' => 'Product not found in cart',
                'error_code' => 'ITEM_NOT_IN_CART',
            ];
        }

        unset($guestCart[$productId]);
        $this->setGuestCartCookie($guestCart);

        return [
            'success' => true,
            'message' => 'Product removed from cart successfully',
        ];
    }

    /**
     * Clear cart
     */
    public function clearCart(Request $request): array
    {
        if ($user = $this->resolveUser($request)) {
            Cart::where('user_id', $user->id)->delete();

            return [
                'success' => true,
                'message' => 'Cart cleared successfully',
            ];
        }

        // guest
        $this->setGuestCartCookie([]); // empty cookie

        return [
            'success' => true,
            'message' => 'Cart cleared successfully',
        ];
    }

    /**
     * Count items
     */
    public function getCartCount(Request $request): int
    {
        if ($user = $this->resolveUser($request)) {
            // Count rows (one row per product), ignore any accidental zero-qty rows
            return (int) Cart::where('user_id', $user->id)
                ->where('quantity', '>', 0)
                ->count();
        }
    
        // Guest: count cookie entries with qty > 0
        $guestCart = $this->getGuestCartFromCookie($request);
    
        return (int) collect($guestCart)
            ->filter(fn ($row) => (int) ($row['quantity'] ?? 0) > 0)
            ->count();
    }
    

    /**
     * Helpers — cookie read/write
     */
    private function getGuestCartFromCookie(Request $request): array
    {
        $raw = $request->cookie($this->guestCartCookie);
        if (!$raw) {
            return [];
        }

        try {
            $decoded = json_decode($raw, true, 512, JSON_THROW_ON_ERROR);
            return is_array($decoded) ? $decoded : [];
        } catch (\Throwable $e) {
            Log::warning('guest_cart_cookie_decode_failed', ['error' => $e->getMessage()]);
            return [];
        }
    }

    private function setGuestCartCookie(array $cartData): void
    {
        // Use app/session config for secure/samesite defaults
        $secure = (bool) config('session.secure', false);
        $sameSite = config('session.same_site', 'lax') ?: 'lax';
        $domain = config('session.domain'); // null by default
        $path = '/';
        $httpOnly = false; // allow mobile app to inspect if needed

        // Laravel Cookie::queue signature: name, value, minutes, path, domain, secure, httpOnly, raw, sameSite
        Cookie::queue(
            $this->guestCartCookie,
            json_encode($cartData, JSON_UNESCAPED_UNICODE),
            $this->cookieLifetimeMinutes,
            $path,
            $domain,
            $secure,
            $httpOnly,
            false,
            $sameSite
        );
    }

    /**
     * (Optional) Migrate guest cart to user on login/registration
     */
    public function migrateGuestCartToUser(Request $request, User $user): array
    {
        $guestCart = $this->getGuestCartFromCookie($request);

        if (empty($guestCart)) {
            return [
                'success' => true,
                'message' => 'No guest cart to migrate',
                'migrated_items' => 0,
            ];
        }

        $migratedItems = 0;
        $errors = [];

        DB::transaction(function () use ($guestCart, $user, &$migratedItems, &$errors) {
            foreach ($guestCart as $productId => $row) {
                try {
                    $product = Product::where('id', (int) $productId)->active()->first();
                    if (!$product) {
                        $errors[] = "Product ID {$productId} not found";
                        continue;
                    }

                    $quantity = (int) ($row['quantity'] ?? 0);
                    if ($quantity > $product->stock) {
                        $quantity = $product->stock;
                        if ($quantity <= 0) {
                            $errors[] = "Product '{$product->name_en}' is out of stock";
                            continue;
                        }
                    }

                    $existing = Cart::where('user_id', $user->id)
                        ->where('product_id', (int) $productId)
                        ->lockForUpdate()
                        ->first();

                    if ($existing) {
                        $newQty = min($existing->quantity + $quantity, $product->stock);
                        $existing->update(['quantity' => $newQty, 'added_at' => now()]);
                    } else {
                        Cart::create([
                            'user_id' => $user->id,
                            'product_id' => (int) $productId,
                            'quantity' => $quantity,
                            'added_at' => now(),
                        ]);
                    }

                    $migratedItems++;
                } catch (\Throwable $e) {
                    $errors[] = "Error migrating product ID {$productId}: {$e->getMessage()}";
                }
            }
        });

        // Clear cookie after migration
        $this->setGuestCartCookie([]);

        return [
            'success' => true,
            'message' => 'Guest cart migrated successfully',
            'migrated_items' => $migratedItems,
            'errors' => $errors,
        ];
    }
}
