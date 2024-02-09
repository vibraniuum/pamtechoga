<?php

namespace Vibraniuum\Pamtechoga\Http\Controllers\Api;

use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Vibraniuum\Pamtechoga\Models\FuelPrice;
use Vibraniuum\Pamtechoga\Models\Order;
use Vibraniuum\Pamtechoga\Models\OrganizationUser;
use Vibraniuum\Pamtechoga\Models\Payment;
use Vibraniuum\Pamtechoga\Models\Product;
use Vibraniuum\Pamtechoga\Models\Zone;

/**
 * @group Order management
 *
 * APIs for managing orders
 */
class DashboardController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @return JsonResponse
     */
    public function dashboard(Request $request): JsonResponse
    {
        $user = auth()->user();

        $userOrganization = OrganizationUser::where('user_id', $user->id)->first();

        $organization = $userOrganization->organization;

        $all_time = (bool)$request->query('all_time');

        if(!$all_time) {
            $startDate = Carbon::createFromFormat('Y-m-d', $request->query('start_date'))->startOfDay();
            $endDate = Carbon::createFromFormat('Y-m-d', $request->query('end_date'))->endOfDay();

            $orders = Order::where('organization_id', $organization->id)
                ->where('status', '<>', 'CANCELED')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->count();

            $ordersBreakDownByProduct = Order::select( 'pamtechoga_customer_orders.product_id', 'pamtechoga_products.type', DB::raw('COUNT(pamtechoga_customer_orders.product_id) as total'))
                ->join('pamtechoga_products','pamtechoga_products.id', '=', 'pamtechoga_customer_orders.product_id')
                ->where('organization_id', $organization->id)
                ->where('status', '<>', 'CANCELED')
                ->whereBetween('pamtechoga_customer_orders.created_at', [$startDate, $endDate])
                ->groupBy('product_id')
                ->get();

            $totalOrderAmount = Order::where('organization_id', $organization->id)
                ->where('status', '<>', 'CANCELED')
                ->whereBetween('pamtechoga_customer_orders.created_at', [$startDate, $endDate])
                ->select(DB::raw('SUM(volume * unit_price) AS total'))
                ->first();

            $totalPayments = Payment::where('organization_id', $organization->id)
                ->where('status', 'CONFIRMED')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->sum('amount');
        } else {
            $orders = Order::where('organization_id', $organization->id)
                ->where('status', '<>', 'CANCELED')
                ->count();

            $ordersBreakDownByProduct = Order::select( 'pamtechoga_customer_orders.product_id', 'pamtechoga_products.type', DB::raw('COUNT(pamtechoga_customer_orders.product_id) as total'))
                ->join('pamtechoga_products','pamtechoga_products.id', '=', 'pamtechoga_customer_orders.product_id')
                ->where('organization_id', $organization->id)
                ->where('status', '<>', 'CANCELED')
                ->groupBy('product_id')
                ->get();

            $totalOrderAmount = Order::where('organization_id', $organization->id)
                ->where('status', '<>', 'CANCELED')
                ->select(DB::raw('SUM(volume * unit_price) AS total'))
                ->first();

            $totalPayments = Payment::where('organization_id', $organization->id)
                ->where('status', 'CONFIRMED')
                ->sum('amount');
        }



        return response()->json([
            'status' => true,
            'data' => [
                "total_payments" => $totalPayments,
                "total_debt" => $totalOrderAmount?->total - $totalPayments <= 0 ? 0 : $totalOrderAmount?->total - $totalPayments,
                "total_orders" => $orders,
                "order_breakdown_by_product" => $ordersBreakDownByProduct
            ],
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @return JsonResponse
     */
    public function fuelPrices(): JsonResponse
    {
        $fuelPrices = FuelPrice::orderBy('petrol', 'desc')->paginate(50);

        return response()->json([
            'status' => true,
            'data' => $fuelPrices,
        ]);
    }

    public function zonesAndStations(): JsonResponse
    {
        $stations = Zone::with('stations')->get();

        return response()->json([
            'status' => true,
            'data' => $stations,
        ]);
    }
}
