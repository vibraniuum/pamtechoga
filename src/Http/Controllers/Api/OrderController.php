<?php

namespace Vibraniuum\Pamtechoga\Http\Controllers\Api;

use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Vibraniuum\Pamtechoga\Models\Branch;
use Vibraniuum\Pamtechoga\Models\Driver;
use Vibraniuum\Pamtechoga\Models\Order;
use Vibraniuum\Pamtechoga\Models\Organization;
use Vibraniuum\Pamtechoga\Models\OrganizationUser;
use Vibraniuum\Pamtechoga\Models\Payment;

/**
 * @group Order management
 *
 * APIs for managing orders
 */
class OrderController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $user = auth()->user();

        $userOrganization = OrganizationUser::where('user_id', $user->id)->first();

        $organization = $userOrganization->organization;

        $all_time = (bool)$request->query('all_time');
        $status = $request->query('status');

        if(!$all_time) {
            $startDate = Carbon::createFromFormat('Y-m-d', $request->query('start_date'))->startOfDay();
            $endDate = Carbon::createFromFormat('Y-m-d', $request->query('end_date'))->endOfDay();

            $orders = Order::where('organization_id', $organization->id)
                ->where('status', $status)
                ->where('status', '<>', 'CANCELED')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->orderBy('created_at', 'desc')
                ->with('product', 'organization', 'branch', 'driver', 'driver.truck')
                ->orderBy('created_at', 'desc')
                ->paginate(50);

            $totalOrderAmount = Order::where('organization_id', $organization->id)
                ->where('status', '<>', 'CANCELED')
                ->whereBetween('pamtechoga_customer_orders.created_at', [$startDate, $endDate])
                ->select(DB::raw('SUM(volume * unit_price) AS total'))
                ->first();
        } else {
            $orders = Order::where('organization_id', $organization->id)
//                ->where('status', $status) all time should return regardless of status
                ->where('status', '<>', 'CANCELED')
                ->orderBy('created_at', 'desc')
                ->with('product', 'organization', 'branch', 'driver', 'driver.truck')
                ->orderBy('created_at', 'desc')
                ->paginate(50);

            $totalOrderAmount = Order::where('organization_id', $organization->id)
                ->where('status', '<>', 'CANCELED')
                ->select(DB::raw('SUM(volume * unit_price) AS total'))
                ->first();
        }

        return response()->json([
            'status' => true,
            'total_orders_amount_sum' => $totalOrderAmount?->total,
            'data' => $orders,
        ]);
    }


    /**
     * Display the specified resource.
     *
     * @return JsonResponse
     */
    public function show(): JsonResponse
    {
        $user = auth()->user();

        $organizationUser = OrganizationUser::where('user_id', $user->id)->first();

        $organization = $organizationUser->organization;
        $branches = $organization->branches;

        $organization['branches'] = $branches;

        return response()->json([
            'status' => true,
            'organization' => $organization
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validateData = Validator::make($request->all(),
            [
                'product_id' => 'required',
                'branch_id' => 'required',
                'volume' => 'required',
                'unit_price' => 'required',
            ]);

            if($validateData->fails()){
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateData->errors()
                ], 401);
            }

            $user = auth()->user();

            $organizationUser = OrganizationUser::where('user_id', $user->id)->first();

            $organization = $organizationUser->organization;
            $request['organization_id'] = $organization->id;

            $order = Order::create($request->all());
            $order = Order::where('id', $order->id)->first();

            return response()->json([
                'status' => true,
                'message' => "Order created successfully!",
                'data' => $order
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param  int  $id
     * @return JsonResponse
     */
    public function update(Request $request, int $id): JsonResponse
    {
        try {
            $validateData = Validator::make($request->all(),
            [
                'address' => 'nullable',
            ]);

            if($validateData->fails()){
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateData->errors()
                ], 401);
            }

            $branch = Branch::find($id);

            $branch->update($request->all());

            return response()->json([
                'status' => true,
                'message' => "Branch Updated successfully!",
                'branch' => $branch
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function driverOrderCount($driverId)
    {
        $driver = Driver::where('id', $driverId)->first();

        if(is_null($driver)) {
            return response()->json([
                'status' => false,
                'message' => 'Driver not found',
            ], 404);
        }

        $ordersCount = Order::where('driver_id', $driverId)->count();

        return response()->json([
            'status' => true,
            'data' => $ordersCount
        ]);
    }
}
