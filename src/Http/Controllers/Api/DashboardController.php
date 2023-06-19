<?php

namespace Vibraniuum\Pamtechoga\Http\Controllers\Api;

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
    public function dashboard(): JsonResponse
    {
        $user = auth()->user();

        $userOrganization = OrganizationUser::where('user_id', $user->id)->first();

        $organization = $userOrganization->organization;

        $orders = Order::where('organization_id', $organization->id)->count();

        $ordersBreakDownByProduct = Order::select( 'pamtechoga_customer_orders.product_id', 'pamtechoga_products.type', DB::raw('COUNT(pamtechoga_customer_orders.product_id) as total'))
            ->join('pamtechoga_products','pamtechoga_products.id', '=', 'pamtechoga_customer_orders.product_id')
            ->where('organization_id', $organization->id)
            ->groupBy('product_id')
            ->get();

        $totalOrderAmount = Order::where('organization_id', $organization->id)
                                ->select(DB::raw('SUM(volume * unit_price) AS total'))
                                ->first();

        $totalPayments = Payment::where('organization_id', $organization->id)->sum('amount');

        return response()->json([
            'status' => true,
            'data' => [
                "total_payments" => $totalPayments,
                "total_debt" => $totalOrderAmount?->total - $totalPayments,
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
}
