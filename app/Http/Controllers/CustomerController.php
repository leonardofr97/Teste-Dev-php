<?php

namespace App\Http\Controllers;

use App\Customer;
use App\Repositories\CustomerRepository;
use App\Services\ZipCodeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class CustomerController extends Controller
{
    private const CACHE_TTL_IN_SECONDS = 60;
    private $zip_code_service;
    private $repository;

    public function __construct(ZipCodeService $zip_code_service, CustomerRepository $customer_repository)
    {
        $this->zip_code_service = $zip_code_service;
        $this->repository = $customer_repository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $cache_key = base64_encode($request->fullUrl());
        if (Cache::has($cache_key)) {
            return Cache::get($cache_key);
        }

        $per_page = $request->input('per_page', 10);
        $page = $request->input('page', 1);
        $filters = $request->input('filters', []);

        $customers = $this->repository->find($per_page, $page, $filters);
        Cache::put($cache_key, $customers, self::CACHE_TTL_IN_SECONDS);

        return response()->json([
            'data' => $customers->items(),
            'metadata' => [
                'total' => $customers->total(),
                'per_page' => intval($per_page),
                'current_page' => $customers->currentPage(),
                'last_page' => $customers->lastPage()
            ],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|min:5|max:255',
                'document' => 'required|unique:customers|min:11|max:11',
                'email' => 'required|unique:customers',
                'zip_code' => 'required|min:8|max:8',
                'street' => 'required',
                'district' => 'required',
                'city' => 'required',
                'state' => 'required',
            ]);

            $address_data = $this->get_address_data($validated['zip_code']);
            if (!$address_data) {
                return response()->json([
                    'errors' => [
                        "zip_code" => ["The zip code field is invalid or doesn't exist"]
                    ]
                ], 422);
            }

            $customer = Customer::create([
                'name' => $validated['name'],
                'document' => $validated['document'],
                'email' => $validated['email'],
                'zip_code' => $validated['zip_code'],
                'street' => $address_data['street'],
                'district' => $address_data['district'],
                'city' => $address_data['city'],
                'state' => $address_data['state'],
            ]);

            return response()->json($customer);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'errors' => $e->errors()
            ], 422);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $validated = $request->validate([
                'name' => 'nullable|min:5|max:255',
                'document' => 'nullable|unique:customers|min:11|max:11',
                'email' => 'nullable|unique:customers',
                'zip_code' => 'nullable|min:8|max:8',
                'street' => 'nullable',
                'district' => 'nullable',
                'city' => 'nullable',
                'state' => 'nullable',
            ]);

            if (isset($validated) && isset($validated['zip_code'])) {
                $address_data = $this->get_address_data($validated['zip_code']);
                if (!$address_data) {
                    return response()->json([
                        'errors' => [
                            "zip_code" => ["The zip code field is invalid or doesn't exist"]
                        ]
                    ], 422);
                }
                $validated = [
                    ...$validated,
                    'street' => $address_data['street'],
                    'district' => $address_data['district'],
                    'city' => $address_data['city'],
                    'state' => $address_data['state'],
                ];
            }

            $customer_id = intval($id);

            return response()->json([$this->repository->update($customer_id, $validated)]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'errors' => $e->errors()
            ], 422);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->repository->delete($id);
        return response()->json(null, 204);
    }

    /**
     * Get address data from zip code.
     */
    private function get_address_data(string $zip_code): ?array
    {
        $cache_key = "zip_code-$zip_code";
        if (Cache::has($cache_key)) {
            return Cache::get($cache_key);
        }

        $address_data = $this->zip_code_service->handle($zip_code);
        if ($address_data) {
            Cache::put($cache_key, $address_data, self::CACHE_TTL_IN_SECONDS);
        }

        return $address_data;
    }
}
