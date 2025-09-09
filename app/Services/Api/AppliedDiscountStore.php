<?php

namespace App\Services\Api;

use Illuminate\Contracts\Cache\Repository as Cache;

class AppliedDiscountStore
{
    public function __construct(private Cache $cache) {}

    private function key(int $userId): string
    {
        return "checkout:applied_discount:user:{$userId}";
    }

    /**
     * Save minimal payload. TTL default 12 hours.
     */
    public function put(int $userId, array $payload, int $minutes = 720): void
    {
        $this->cache->put($this->key($userId), $payload, now()->addMinutes($minutes));
    }

    public function get(int $userId): ?array
    {
        return $this->cache->get($this->key($userId));
    }

    public function forget(int $userId): void
    {
        $this->cache->forget($this->key($userId));
    }
}
