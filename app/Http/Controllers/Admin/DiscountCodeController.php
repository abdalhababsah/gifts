<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DiscountCode;
use App\Services\Admin\DiscountCodeService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class DiscountCodeController extends Controller
{
    public function __construct(
        private DiscountCodeService $discountCodeService
    ) {
    }

    /**
     * Display a listing of discount codes.
     */
    public function index(Request $request): View
    {
        $discountCodes = $this->discountCodeService->getPaginated($request);
        $statistics = $this->discountCodeService->getStatistics();

        return view('admin.discount-codes.index', compact('discountCodes', 'statistics'));
    }

    /**
     * Store a newly created discount code.
     */
    public function store(Request $request): RedirectResponse
    {
        try {
            $validated = $request->validate([
                'code' => 'required|string|max:50|unique:discount_codes,code',
                'description' => 'nullable|string|max:255',
                'type' => 'required|string|in:percent,fixed',
                'value' => 'required|numeric|min:0',
                'min_order_total' => 'nullable|numeric|min:0',
                'usage_limit' => 'nullable|integer|min:1',
                'per_user_limit' => 'nullable|integer|min:1',
                'start_date' => 'nullable|date',
                'end_date' => 'nullable|date|after_or_equal:start_date',
                'is_active' => 'boolean',
            ]);

            // Additional validation for percentage type
            if ($validated['type'] === 'percent' && $validated['value'] > 100) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['value' => 'Percentage value cannot exceed 100%.']);
            }

            $validated['is_active'] = $request->has('is_active');

            // Validate dates
            $dateErrors = $this->discountCodeService->validateDates($validated);
            if (!empty($dateErrors)) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['date_error' => $dateErrors[0]]);
            }

            $discountCode = $this->discountCodeService->create($validated);

            return redirect()->route('admin.discount-codes.index')
                ->with('success', 'Discount code created successfully.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Failed to create discount code: ' . $e->getMessage()]);
        }
    }

    /**
     * Show the form for editing the specified discount code.
     */
    public function show(DiscountCode $discountCode): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $discountCode
        ]);
    }

    /**
     * Update the specified discount code.
     */
    public function update(Request $request, DiscountCode $discountCode): RedirectResponse
    {
        try {
            $validated = $request->validate([
                'code' => 'required|string|max:50|unique:discount_codes,code,' . $discountCode->id,
                'description' => 'nullable|string|max:255',
                'type' => 'required|string|in:percent,fixed',
                'value' => 'required|numeric|min:0',
                'min_order_total' => 'nullable|numeric|min:0',
                'usage_limit' => 'nullable|integer|min:1',
                'per_user_limit' => 'nullable|integer|min:1',
                'start_date' => 'nullable|date',
                'end_date' => 'nullable|date|after_or_equal:start_date',
                'is_active' => 'boolean',
            ]);

            // Additional validation for percentage type
            if ($validated['type'] === 'percent' && $validated['value'] > 100) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['value' => 'Percentage value cannot exceed 100%.']);
            }

            $validated['is_active'] = $request->has('is_active');

            // Validate dates
            $dateErrors = $this->discountCodeService->validateDates($validated);
            if (!empty($dateErrors)) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['date_error' => $dateErrors[0]]);
            }

            $discountCode = $this->discountCodeService->update($discountCode, $validated);

            return redirect()->route('admin.discount-codes.index')
                ->with('success', 'Discount code updated successfully.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Failed to update discount code: ' . $e->getMessage()]);
        }
    }

    /**
     * Remove the specified discount code.
     */
    public function destroy(DiscountCode $discountCode): RedirectResponse
    {
        try {
            $this->discountCodeService->delete($discountCode);

            return redirect()->route('admin.discount-codes.index')
                ->with('success', 'Discount code deleted successfully.');

        } catch (\Exception $e) {
            return redirect()->route('admin.discount-codes.index')
                ->with('error', 'Failed to delete discount code: ' . $e->getMessage());
        }
    }

    /**
     * Toggle discount code status.
     */
    public function toggleStatus(DiscountCode $discountCode): RedirectResponse
    {
        try {
            $discountCode = $this->discountCodeService->toggleStatus($discountCode);
            $newStatus = $discountCode->is_active ? 'active' : 'inactive';
            
            return redirect()->route('admin.discount-codes.index')
                ->with('success', "Discount code '{$discountCode->code}' is now {$newStatus}.");
                
        } catch (\Exception $e) {
            return redirect()->route('admin.discount-codes.index')
                ->with('error', 'Failed to update discount code status: ' . $e->getMessage());
        }
    }

    /**
     * Get discount codes for dropdown/select.
     */
    public function getForSelect(): JsonResponse
    {
        $discountCodes = $this->discountCodeService->getForSelect();

        return response()->json([
            'success' => true,
            'data' => $discountCodes
        ]);
    }
}
