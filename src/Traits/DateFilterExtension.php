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

trait DateFilterExtension
{
    // breakdown
    public $payments;
    public $unverifiedPayments;
    public $bfDebt = 0.0;
    public $totalPaymentsWithinRange = 0.0;
    public $totalPaymentsMade = 0.0;
    public $balance = 0.0;
    public $totalDebtOwed = 0.0;
    public $orders;
    public $ordersAmountTotal = 0.0;
    public $ordersVolumeTotal = 0.0;

    public function applyFilterExtension()
    {
        // Sales breakdown for given organization
        $organization = Organization::where('id', $this->organization)->first(); // figure this out for the dashboard

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

        $this->unverifiedPayments = Payment::where('organization_id', $organization->id)
            ->whereBetween('payment_date', [$this->startDate, $this->endDate])
            ->where('status', '<>', $status)
            ->orderBy('created_at', 'desc')
            ->with('organization')
            ->get();
        // end
    }
}
