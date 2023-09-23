<?php

namespace Vibraniuum\Pamtechoga\Traits;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Vibraniuum\Pamtechoga\Models\Order;
use Vibraniuum\Pamtechoga\Models\DepotOrder;
use Vibraniuum\Pamtechoga\Models\DepotPickup;
use Vibraniuum\Pamtechoga\Models\Product;
use Vibraniuum\Pamtechoga\Models\Organization;
use Vibraniuum\Pamtechoga\Models\Driver;
use Vibraniuum\Pamtechoga\Models\Truck;
use Vibraniuum\Pamtechoga\Models\Payment;

trait DateFilter
{
    public $canResetDate = true;

    public $startDate;
    public $endDate;

    public $totalCustomerOrdersAmount;
    public $totalCustomerOrders;
    public $totalCustomerOrdersVolume;
    public $totalConfirmedPayment;
    public $totalDebt;
    public $totalCustomerOrdersDeliveredAmount;
    public $totalCustomerOrdersDelivered;
    public $totalCustomerOrdersDeliveredVolume;
    public $deliveredOrdersBreakDownByProduct;
    public $totalDepotOrdersAmount;
    public $totalDepotOrders;
    public $totalDepotOrdersVolume;
    public $ordersBreakDownByProduct;
    public $totalProducts;
    public $totalOrganizations;
    public $totalDrivers;
    public $totalTrucks;
    public $totalPMSDepotOrdersVolume;
    public $totalPMSDepotPickupsVolume;
    public $totalVolumeOfPMSAtDepot;
    public $totalAGODepotOrdersVolume;
    public $totalAGODepotPickupsVolume;
    public $totalVolumeOfAGOAtDepot;
    public $totalVolumeOfPMSInTrucks;
    public $deliveredOrdersVolumeByProduct;

    // breakdown
    public $payments;
    public $bfDebt = 0.0;
    public $totalPaymentsWithinRange = 0.0;
    public $totalPaymentsMade = 0.0;
    public $balance = 0.0;
    public $totalDebtOwed = 0.0;
    public $orders;
    public $ordersAmountTotal = 0.0;
    public $ordersVolumeTotal = 0.0;

    public function resetDates()
    {
        $this->startDate = Carbon::createFromFormat('Y-m-d', '2019-01-01')->startOfDay();
        $this->endDate = Carbon::now();
        $this->canResetDate = false;
    }

    public function applyFilter()
    {
        // Fetch the totalCustomerOrdersAmount based on startDate and endDate filters
        $this->totalCustomerOrdersAmount = Order::where('status', '<>', 'CANCELED')
            ->whereBetween('pamtechoga_customer_orders.created_at', [$this->startDate, $this->endDate])
            ->select(DB::raw('SUM(volume * unit_price) AS total'))
            ->first();

        $this->totalCustomerOrdersAmount = $this->totalCustomerOrdersAmount?->total ?? 0;

        // Fetch other data based on startDate and endDate filters...
        $this->totalCustomerOrders = Order::where('status', '<>', 'CANCELED')
            ->whereBetween('pamtechoga_customer_orders.created_at', [$this->startDate, $this->endDate])
            ->count();
        $this->totalCustomerOrdersVolume = Order::where('status', '<>', 'CANCELED')
            ->whereBetween('pamtechoga_customer_orders.created_at', [$this->startDate, $this->endDate])
            ->sum('volume');

        $this->totalConfirmedPayment = Payment::where('status', '=', 'CONFIRMED')
            ->whereBetween('created_at', [$this->startDate, $this->endDate])
            ->sum('amount');

        $this->totalDebt = $this->totalCustomerOrdersAmount - $this->totalConfirmedPayment;

        $this->totalCustomerOrdersDeliveredAmount = Order::where('status', '=', 'DELIVERED')
            ->whereBetween('pamtechoga_customer_orders.created_at', [$this->startDate, $this->endDate])
            ->select(DB::raw('SUM(volume * unit_price) AS total'))
            ->first();
        $this->totalCustomerOrdersDelivered = Order::where('status', '=', 'DELIVERED')
            ->whereBetween('pamtechoga_customer_orders.created_at', [$this->startDate, $this->endDate])
            ->count();
        $this->totalCustomerOrdersDeliveredVolume = Order::where('status', '=', 'DELIVERED')
            ->whereBetween('pamtechoga_customer_orders.created_at', [$this->startDate, $this->endDate])
            ->sum('volume');
        $this->deliveredOrdersBreakDownByProduct = Order::select('pamtechoga_customer_orders.product_id', 'pamtechoga_products.type', DB::raw('SUM(pamtechoga_customer_orders.volume) as total'))
            ->join('pamtechoga_products', 'pamtechoga_products.id', '=', 'pamtechoga_customer_orders.product_id')
            ->where('status', '=', 'DELIVERED')
            ->whereBetween('pamtechoga_customer_orders.created_at', [$this->startDate, $this->endDate])
            ->groupBy('product_id')
            ->get();

        $this->totalDepotOrdersAmount = DepotOrder::where('status', '<>', 'CANCELED')
            ->whereBetween('created_at', [$this->startDate, $this->endDate])
            ->select(DB::raw('SUM(volume * unit_price) AS total'))
            ->first();
        $this->totalDepotOrders = DepotOrder::where('status', '<>', 'CANCELED')
            ->whereBetween('created_at', [$this->startDate, $this->endDate])
            ->count();
        $this->totalDepotOrdersVolume = DepotOrder::where('status', '<>', 'CANCELED')
            ->whereBetween('created_at', [$this->startDate, $this->endDate])
            ->sum('volume');

        $this->ordersBreakDownByProduct = Order::select('pamtechoga_customer_orders.product_id', 'pamtechoga_products.type', DB::raw('SUM(pamtechoga_customer_orders.volume) as total'))
            ->join('pamtechoga_products', 'pamtechoga_products.id', '=', 'pamtechoga_customer_orders.product_id')
            ->where('status', '<>', 'CANCELED')
            ->whereBetween('pamtechoga_customer_orders.created_at', [$this->startDate, $this->endDate])
            ->groupBy('product_id')
            ->get();

        $this->totalProducts = Product::count();
        $this->totalOrganizations = Organization::count();
        $this->totalDrivers = Driver::count();
        $this->totalTrucks = Truck::count();

        $this->totalPMSDepotOrdersVolume = DepotOrder::join('pamtechoga_products', 'pamtechoga_products.id', '=', 'pamtechoga_depot_orders.product_id')
            ->where('pamtechoga_depot_orders.status', '<>', 'CANCELED')
            ->where('pamtechoga_products.type', 'PMS')
//            ->whereBetween('pamtechoga_depot_orders.created_at', [$this->startDate, $this->endDate])
            ->sum('pamtechoga_depot_orders.volume');

        $this->totalPMSDepotPickupsVolume = DepotPickup::join('pamtechoga_customer_orders', 'pamtechoga_customer_orders.id', '=', 'pamtechoga_depot_pickups.depot_order_id')
            ->join('pamtechoga_products', 'pamtechoga_products.id', '=', 'pamtechoga_customer_orders.product_id')
            ->where('pamtechoga_depot_pickups.status', '<>', 'CANCELED')
            ->where('pamtechoga_products.type', 'PMS')
//            ->whereBetween('pamtechoga_depot_pickups.created_at', [$this->startDate, $this->endDate])
            ->sum('pamtechoga_depot_pickups.volume_assigned');

        $this->totalVolumeOfPMSAtDepot = $this->totalPMSDepotOrdersVolume - $this->totalPMSDepotPickupsVolume;

        $this->totalAGODepotOrdersVolume = DepotOrder::join('pamtechoga_products', 'pamtechoga_products.id', '=', 'pamtechoga_depot_orders.product_id')
            ->where('pamtechoga_depot_orders.status', '<>', 'CANCELED')
            ->where('pamtechoga_products.type', 'AGO')
//            ->whereBetween('pamtechoga_products.created_at', [$this->startDate, $this->endDate])
            ->sum('pamtechoga_depot_orders.volume');

        $this->totalAGODepotPickupsVolume = DepotPickup::join('pamtechoga_customer_orders', 'pamtechoga_customer_orders.id', '=', 'pamtechoga_depot_pickups.depot_order_id')
            ->join('pamtechoga_products', 'pamtechoga_products.id', '=', 'pamtechoga_customer_orders.product_id')
            ->where('pamtechoga_depot_pickups.status', '<>', 'CANCELED')
            ->where('pamtechoga_products.type', 'AGO')
//            ->whereBetween('pamtechoga_products.created_at', [$this->startDate, $this->endDate])
            ->sum('pamtechoga_depot_pickups.volume_assigned');

        $this->totalVolumeOfAGOAtDepot = $this->totalAGODepotOrdersVolume - $this->totalAGODepotPickupsVolume;
        $this->totalVolumeOfPMSInTrucks = $this->totalAGODepotOrdersVolume - $this->totalAGODepotPickupsVolume;

        $this->deliveredOrdersVolumeByProduct = Order::select('pamtechoga_customer_orders.product_id', 'pamtechoga_products.type', DB::raw('SUM(pamtechoga_customer_orders.volume) as total'))
            ->join('pamtechoga_products', 'pamtechoga_products.id', '=', 'pamtechoga_customer_orders.product_id')
            ->where('status', '=', 'DELIVERED')
            ->orWhere('status', '=', 'DISPATCHED')
            ->whereBetween('pamtechoga_customer_orders.created_at', [$this->startDate, $this->endDate])
            ->groupBy('product_id')
            ->get();

        // Define the totalVolumeOfLoadedProductPickup function as a property
//        $this->totalVolumeOfLoadedProductPickup = $this->totalVolumeOfLoadedProductPickupFunction();

        // Sales breakdown for given organization
//        $organization = Organization::where('id', $this->organization)->first(); // figure this out for the dashboard

        $organization = Organization::first();
        $status = 'CONFIRMED';

        /**
         * BF-Debt (brought froward balance) = (SUM(orders amount before start date) - SUM(payments before start date))
         * payments = records within range of start date and end date
         * total = SUM(payments)
         * balance = BF - total
         */
        $this->orders = Order::where('organization_id', $organization->id)
            ->where('status', '<>', 'CANCELED')
            ->whereBetween('created_at', [$this->startDate, $this->endDate])
            ->get();

        $this->ordersAmountTotal = Order::where('organization_id', $organization->id)
            ->where('status', '<>', 'CANCELED')
            ->whereBetween('created_at', [$this->startDate, $this->endDate])
            ->select(DB::raw('SUM(volume * unit_price) AS total'))
            ->first();

        $this->ordersVolumeTotal = Order::where('organization_id', $organization->id)
            ->where('status', '<>', 'CANCELED')
            ->whereBetween('created_at', [$this->startDate, $this->endDate])
            ->sum('volume');

        // -----------------
        $sumOfOrdersAmountBeforeStartDate = Order::where('organization_id', $organization->id)
            ->where('status', '<>', 'CANCELED')
//            ->where('pamtechoga_customer_orders.created_at', '<', $this->startDate)
            ->select(DB::raw('SUM(volume * unit_price) AS total'))
            ->first();

        $sumOfPaymentsBeforeStartDate = Payment::where('organization_id', $organization->id)
            ->where('status', $status)
//            ->where('payment_date', '<', $this->startDate)
            ->sum('amount');

        $sumOfOrdersAmountBeforeStartDateForBF = Order::where('organization_id', $organization->id)
            ->where('status', '<>', 'CANCELED')
            ->where('pamtechoga_customer_orders.created_at', '<', $this->startDate)
            ->select(DB::raw('SUM(volume * unit_price) AS total'))
            ->first();

        $sumOfPaymentsBeforeStartDateForBF = Payment::where('organization_id', $organization->id)
            ->where('status', $status)
            ->where('payment_date', '<', $this->startDate)
            ->sum('amount');

        $this->bfDebt = max($sumOfOrdersAmountBeforeStartDateForBF?->total - $sumOfPaymentsBeforeStartDateForBF, 0);

        $this->totalPaymentsWithinRange = Payment::where('organization_id', $organization->id)
            ->whereBetween('payment_date', [$this->startDate, $this->endDate])
            ->where('status', $status)
            ->sum('amount');

//        dd($sumOfOrdersAmountBeforeStartDateForBF?->total, $this->totalPaymentsWithinRange);

        $this->totalPaymentsMade = Payment::where('organization_id', $organization->id)
            ->where('status', $status)
            ->sum('amount');

        $this->totalDebtOwed = max($sumOfOrdersAmountBeforeStartDate?->total - $sumOfPaymentsBeforeStartDate, 0);

        // Balance is wonky, Need to figure it out
        $this->balance = max($this->bfDebt - $this->totalPaymentsWithinRange, 0);
//        $this->balance = max($sumOfOrdersAmountBeforeStartDate?->total - $sumOfPaymentsBeforeStartDate, 0);
        // -----------------

        $this->payments = Payment::where('organization_id', $organization->id)
            ->whereBetween('payment_date', [$this->startDate, $this->endDate])
            ->where('status', $status)
            ->orderBy('created_at', 'desc')
            ->with('organization')
            ->get();
        // end
    }

    public function totalVolumeOfLoadedProductPickupFunction($type)
    {
        return DepotPickup::join('pamtechoga_customer_orders', 'pamtechoga_customer_orders.id', '=', 'pamtechoga_depot_pickups.depot_order_id')
            ->join('pamtechoga_products', 'pamtechoga_products.id', '=', 'pamtechoga_customer_orders.product_id')
            ->where('pamtechoga_depot_pickups.status', '<>', 'CANCELED')
            ->where('pamtechoga_depot_pickups.status', '=', 'LOADED') // Ask Pamtech if LOADED means it is already in the truck or still at the depot.
            ->where('pamtechoga_products.type', $type)
            ->sum('pamtechoga_depot_pickups.volume_assigned');
    }
}
