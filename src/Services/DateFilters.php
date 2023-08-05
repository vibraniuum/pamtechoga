<?php

use Illuminate\Support\Facades\DB;
use Vibraniuum\Pamtechoga\Models\Order;
use Vibraniuum\Pamtechoga\Models\DepotOrder;
use Vibraniuum\Pamtechoga\Models\DepotPickup;
use Vibraniuum\Pamtechoga\Models\Product;
use Vibraniuum\Pamtechoga\Models\Organization;
use Vibraniuum\Pamtechoga\Models\Driver;
use Vibraniuum\Pamtechoga\Models\Truck;
use Vibraniuum\Pamtechoga\Models\Payment;

$totalCustomerOrdersAmount = Order::where('status', '<>', 'CANCELED')
    //->whereBetween('pamtechoga_customer_orders.created_at', [$startDate, $endDate])
    ->select(DB::raw('SUM(volume * unit_price) AS total'))
    ->first();
$totalCustomerOrders = Order::where('status', '<>', 'CANCELED')->count();
$totalCustomerOrdersVolume = Order::where('status', '<>', 'CANCELED')->sum('volume');

$totalConfirmedPayment = Payment::where('status', '=', 'CONFIRMED')->sum('amount');

$totalDebt = $totalCustomerOrdersAmount->total - $totalConfirmedPayment;

$totalCustomerOrdersDeliveredAmount = Order::where('status', '=', 'DELIVERED')
    ->whereBetween('pamtechoga_customer_orders.created_at', [$this->startDate, $this->endDate])
    ->select(DB::raw('SUM(volume * unit_price) AS total'))
    ->first();
$totalCustomerOrdersDelivered = Order::where('status', '=', 'DELIVERED')->count();
$totalCustomerOrdersDeliveredVolume = Order::where('status', '=', 'DELIVERED')->sum('volume');
$deliveredOrdersBreakDownByProduct = Order::select( 'pamtechoga_customer_orders.product_id', 'pamtechoga_products.type', DB::raw('SUM(pamtechoga_customer_orders.volume) as total'))
    ->join('pamtechoga_products','pamtechoga_products.id', '=', 'pamtechoga_customer_orders.product_id')
    ->where('status', '=', 'DELIVERED')
    //->whereBetween('pamtechoga_customer_orders.created_at', [$startDate, $endDate])
    ->groupBy('product_id')
    ->get();

$totalDepotOrdersAmount = DepotOrder::where('status', '<>', 'CANCELED')
    ->whereBetween('pamtechoga_depot_orders.created_at', [$this->startDate, $this->endDate])
    ->select(DB::raw('SUM(volume * unit_price) AS total'))
    ->first();
$totalDepotOrders = DepotOrder::where('status', '<>', 'CANCELED')->count();
$totalDepotOrdersVolume = DepotOrder::where('status', '<>', 'CANCELED')->sum('volume');

$ordersBreakDownByProduct = Order::select( 'pamtechoga_customer_orders.product_id', 'pamtechoga_products.type', DB::raw('SUM(pamtechoga_customer_orders.volume) as total'))
    ->join('pamtechoga_products','pamtechoga_products.id', '=', 'pamtechoga_customer_orders.product_id')
    ->where('status', '<>', 'CANCELED')
    ->whereBetween('pamtechoga_customer_orders.created_at', [$this->startDate, $this->endDate])
    ->groupBy('product_id')
    ->get();

$totalProducts = Product::count();
$totalOrganizations = Organization::count();
$totalDrivers = Driver::count();
$totalTrucks = Truck::count();

$totalPMSDepotOrdersVolume = DepotOrder::join('pamtechoga_products','pamtechoga_products.id', '=', 'pamtechoga_depot_orders.product_id')
    ->where('pamtechoga_depot_orders.status', '<>', 'CANCELED')
    ->where('pamtechoga_products.type', 'PMS')
    ->sum('pamtechoga_depot_orders.volume');

$totalPMSDepotPickupsVolume = DepotPickup::join('pamtechoga_customer_orders','pamtechoga_customer_orders.id', '=', 'pamtechoga_depot_pickups.depot_order_id')
    ->join('pamtechoga_products','pamtechoga_products.id', '=', 'pamtechoga_customer_orders.product_id')
    ->where('pamtechoga_depot_pickups.status', '<>', 'CANCELED')
    ->where('pamtechoga_products.type', 'PMS')
    ->sum('pamtechoga_depot_pickups.volume_assigned');

$totalVolumeOfPMSAtDepot = $totalPMSDepotOrdersVolume - $totalPMSDepotPickupsVolume;

$totalAGODepotOrdersVolume = DepotOrder::join('pamtechoga_products','pamtechoga_products.id', '=', 'pamtechoga_depot_orders.product_id')
    ->where('pamtechoga_depot_orders.status', '<>', 'CANCELED')
    ->where('pamtechoga_products.type', 'AGO')
    ->sum('pamtechoga_depot_orders.volume');

$totalAGODepotPickupsVolume = DepotPickup::join('pamtechoga_customer_orders','pamtechoga_customer_orders.id', '=', 'pamtechoga_depot_pickups.depot_order_id')
    ->join('pamtechoga_products','pamtechoga_products.id', '=', 'pamtechoga_customer_orders.product_id')
    ->where('pamtechoga_depot_pickups.status', '<>', 'CANCELED')
    ->where('pamtechoga_products.type', 'AGO')
    ->sum('pamtechoga_depot_pickups.volume_assigned');

$totalVolumeOfAGOAtDepot = $totalAGODepotOrdersVolume - $totalAGODepotPickupsVolume;

$totalVolumeOfPMSInTrucks = $totalAGODepotOrdersVolume - $totalAGODepotPickupsVolume;

$deliveredOrdersVolumeByProduct = Order::select( 'pamtechoga_customer_orders.product_id', 'pamtechoga_products.type', DB::raw('SUM(pamtechoga_customer_orders.volume) as total'))
    ->join('pamtechoga_products','pamtechoga_products.id', '=', 'pamtechoga_customer_orders.product_id')
    ->where('status', '=', 'DELIVERED')
    ->orWhere('status', '=', 'DISPATCHED')
    ->whereBetween('pamtechoga_customer_orders.created_at', [$this->startDate, $this->endDate])
    ->groupBy('product_id')
    ->get();

$totalVolumeOfLoadedProductPickup = function ($type) {
    DepotPickup::join('pamtechoga_customer_orders','pamtechoga_customer_orders.id', '=', 'pamtechoga_depot_pickups.depot_order_id')
        ->join('pamtechoga_products','pamtechoga_products.id', '=', 'pamtechoga_customer_orders.product_id')
        ->where('pamtechoga_depot_pickups.status', '<>', 'CANCELED')
        ->where('pamtechoga_depot_pickups.status', '=', 'LOADED') // Ask Pamtech if LOADed means it is already in the truck or still at the depot.
        ->where('pamtechoga_products.type', $type)
        ->sum('pamtechoga_depot_pickups.volume_assigned');
};
