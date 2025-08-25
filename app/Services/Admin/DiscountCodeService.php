<?php

namespace App\Services\Admin;

use App\Models\DiscountCode;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Carbon\Carbon;

class DiscountCodeService
{
    /**
     * Get paginated discount codes with filters.
     */
    public function getPaginated(Request $request): LengthAwarePaginator
    {
        $query = DiscountCode::withCount('orderDiscounts');

        // Apply search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('code', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Apply status filter
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        // Apply type filter
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Default ordering
        $query->orderBy('created_at', 'desc');

        return $query->paginate($request->get('per_page', 15))
                    ->withQueryString();
    }

    /**
     * Create a new discount code.
     */
    public function create(array $data): DiscountCode
    {
        // Convert date strings to Carbon instances
        if (isset($data['start_date']) && $data['start_date']) {
            $data['start_date'] = Carbon::parse($data['start_date']);
        }
        
        if (isset($data['end_date']) && $data['end_date']) {
            $data['end_date'] = Carbon::parse($data['end_date']);
        }

        // Convert usage limits to integers or null
        if (isset($data['usage_limit']) && $data['usage_limit'] !== '') {
            $data['usage_limit'] = (int) $data['usage_limit'];
        } else {
            $data['usage_limit'] = null;
        }

        if (isset($data['per_user_limit']) && $data['per_user_limit'] !== '') {
            $data['per_user_limit'] = (int) $data['per_user_limit'];
        } else {
            $data['per_user_limit'] = null;
        }

        return DiscountCode::create($data);
    }

    /**
     * Update an existing discount code.
     */
    public function update(DiscountCode $discountCode, array $data): DiscountCode
    {
        // Convert date strings to Carbon instances
        if (isset($data['start_date']) && $data['start_date']) {
            $data['start_date'] = Carbon::parse($data['start_date']);
        } elseif (isset($data['start_date']) && $data['start_date'] === '') {
            $data['start_date'] = null;
        }
        
        if (isset($data['end_date']) && $data['end_date']) {
            $data['end_date'] = Carbon::parse($data['end_date']);
        } elseif (isset($data['end_date']) && $data['end_date'] === '') {
            $data['end_date'] = null;
        }

        // Convert usage limits to integers or null
        if (isset($data['usage_limit']) && $data['usage_limit'] !== '') {
            $data['usage_limit'] = (int) $data['usage_limit'];
        } else {
            $data['usage_limit'] = null;
        }

        if (isset($data['per_user_limit']) && $data['per_user_limit'] !== '') {
            $data['per_user_limit'] = (int) $data['per_user_limit'];
        } else {
            $data['per_user_limit'] = null;
        }

        $discountCode->update($data);
        
        return $discountCode->refresh();
    }

    /**
     * Delete a discount code.
     */
    public function delete(DiscountCode $discountCode): bool
    {
        // Check if discount code has been used
        if ($discountCode->orderDiscounts()->exists()) {
            throw new \Exception('Cannot delete discount code that has been used in orders.');
        }

        return $discountCode->delete();
    }

    /**
     * Toggle discount code status.
     */
    public function toggleStatus(DiscountCode $discountCode): DiscountCode
    {
        $discountCode->update(['is_active' => !$discountCode->is_active]);
        
        return $discountCode->refresh();
    }

    /**
     * Get discount codes for dropdown/select options.
     */
    public function getForSelect(): \Illuminate\Database\Eloquent\Collection
    {
        return DiscountCode::active()
                   ->orderBy('code')
                   ->get(['id', 'code', 'description', 'type', 'value']);
    }

    /**
     * Get statistics for dashboard.
     */
    public function getStatistics(): array
    {
        return [
            'total' => DiscountCode::count(),
            'active' => DiscountCode::where('is_active', true)->count(),
            'inactive' => DiscountCode::where('is_active', false)->count(),
            'used' => DiscountCode::has('orderDiscounts')->count(),
            'expired' => DiscountCode::where('end_date', '<', now())->count(),
            'upcoming' => DiscountCode::where('start_date', '>', now())->count(),
        ];
    }

    /**
     * Validate discount code dates.
     */
    public function validateDates(array $data): array
    {
        $errors = [];

        if (isset($data['start_date']) && isset($data['end_date']) && 
            $data['start_date'] && $data['end_date']) {
            
            $startDate = Carbon::parse($data['start_date']);
            $endDate = Carbon::parse($data['end_date']);

            if ($startDate->isAfter($endDate)) {
                $errors[] = 'Start date cannot be after end date.';
            }
        }

        return $errors;
    }
}
