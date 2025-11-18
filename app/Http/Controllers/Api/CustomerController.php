<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CustomerResource;
use App\Models\Customer;
use App\Models\CustomerSegment;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\ResourceCollection;

class CustomerController extends Controller
{
    /**
     * Display a listing of customers
     */
    public function index(Request $request): JsonResponse
    {
        $query = Customer::query()->with(['segments']);

        // Filter by tier
        if ($request->has('tier')) {
            $query->where('tier', $request->tier);
        }

        // Filter by segment
        if ($request->has('segment_id')) {
            $query->whereHas('segments', function ($q) use ($request) {
                $q->where('customer_segments.id', $request->segment_id);
            });
        }

        // Filter VIP
        if ($request->boolean('vip_only')) {
            $query->vip();
        }

        // Filter at risk
        if ($request->boolean('at_risk_only')) {
            $query->atRisk();
        }

        // Search
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $customers = $query->paginate($request->per_page ?? 15);

        return CustomerResource::collection($customers);
    }

    /**
     * Display the specified customer
     */
    public function show(Request $request, Customer $customer): JsonResponse
    {
        $customer->load(['visits' => function ($query) {
            $query->latest('visited_at')->limit(10);
        }, 'segments']);

        $resource = new CustomerResource($customer);

        return response()->json([
            'data' => $resource,
            'stats' => [
                'rfm_score' => $customer->getRFMScore(),
                'average_spend' => $customer->average_spend,
                'is_vip' => $customer->is_vip,
                'at_risk' => $customer->isAtRiskOfChurn(),
                'is_birthday' => $customer->isBirthday(),
            ],
        ]);
    }

    /**
     * Store a newly created customer
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'email' => 'required|email|unique:customers,email',
            'phone' => 'nullable|string|max:20',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'birth_date' => 'nullable|date',
            'preferences' => 'nullable|array',
            'tags' => 'nullable|array',
            'language' => 'nullable|in:fr,en,es,it',
            'notes' => 'nullable|string',
        ]);

        $customer = Customer::create($validated);

        return response()->json([
            'message' => 'Customer created successfully',
            'data' => new CustomerResource($customer),
        ], 201);
    }

    /**
     * Update the specified customer
     */
    public function update(Request $request, Customer $customer): JsonResponse
    {
        $validated = $request->validate([
            'email' => 'email|unique:customers,email,' . $customer->id,
            'phone' => 'nullable|string|max:20',
            'first_name' => 'string|max:255',
            'last_name' => 'string|max:255',
            'birth_date' => 'nullable|date',
            'preferences' => 'nullable|array',
            'tags' => 'nullable|array',
            'tier' => 'in:regular,vip,super_vip',
            'language' => 'in:fr,en,es,it',
            'notes' => 'nullable|string',
        ]);

        $customer->update($validated);

        return response()->json([
            'message' => 'Customer updated successfully',
            'data' => new CustomerResource($customer->fresh()),
        ]);
    }

    /**
     * Remove the specified customer
     */
    public function destroy(Customer $customer): JsonResponse
    {
        $customer->delete();

        return response()->json([
            'message' => 'Customer deleted successfully',
        ], 204);
    }

    /**
     * Get customer segments
     */
    public function segments(): JsonResponse
    {
        $segments = CustomerSegment::withCount('customers')->get();

        return response()->json(['data' => $segments]);
    }

    /**
     * Get customers at risk
     */
    public function atRisk(): JsonResponse
    {
        $customers = Customer::atRisk()
            ->with('segments')
            ->orderBy('last_visit_at', 'asc')
            ->paginate(20);

        return CustomerResource::collection($customers);
    }

    /**
     * Get VIP customers
     */
    public function vips(): JsonResponse
    {
        $customers = Customer::vip()
            ->with('segments')
            ->orderBy('lifetime_value', 'desc')
            ->paginate(20);

        return CustomerResource::collection($customers);
    }

    /**
     * Get customers with birthdays this month
     */
    public function birthdays(): JsonResponse
    {
        $customers = Customer::birthdayThisMonth()
            ->with('segments')
            ->orderBy('birth_date', 'asc')
            ->get();

        return response()->json(['data' => CustomerResource::collection($customers)]);
    }
}
