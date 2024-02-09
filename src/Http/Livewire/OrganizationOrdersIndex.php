<?php

namespace Vibraniuum\Pamtechoga\Http\Livewire;

use Carbon\Carbon;
use Helix\Lego\Http\Livewire\Models\Index as BaseIndex;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Vibraniuum\Pamtechoga\Models\DepotOrder;
use Vibraniuum\Pamtechoga\Models\Order;
use Vibraniuum\Pamtechoga\Models\Organization;
use Vibraniuum\Pamtechoga\Models\OrganizationUser;
use Vibraniuum\Pamtechoga\Models\Payment;
use Vibraniuum\Pamtechoga\Traits\DateFilter;
use Vibraniuum\Pamtechoga\Traits\DateFilterExtension;

class OrganizationOrdersIndex extends BaseIndex
{
    use DateFilter;
    use DateFilterExtension;

    public $organization;

    protected $listeners = ['filterApplied'];

    public function filterApplied($filterData)
    {
        $this->startDate = $filterData['startDate'];
        $this->endDate = $filterData['endDate'];
        $this->mount();
    }

    public function model(): string
    {
        return Order::class;
    }

    public function columns(): array
    {
        return [
//            'organization_name' => 'Organization Name',

            'date' => 'Order Date',
            'product' => 'Product Type',
            'volume' => 'Volume (Litres)',
            'unit_price' => 'Unit Price (NGN)',
            'amount' => 'Amount (NGN)',
            'profit' => 'Profit (NGN)',
            'driver' => 'Driver',
            'status' => 'Status',
            'action' => 'Action',
        ];
    }

    public function mainSearchColumn(): string|false
    {
        return 'product';
    }

    public function render()
    {
        if($this->canResetDate) {
            $this->resetDates();
        }
        $this->applyFilterExtension();

//        $this->processBreakdown();

        return view('pamtechoga::models.organizationOrders.index', [
//            'models' => Order::where('organization_id', $this->organization)->paginate(10),
//            'models' => $this->getModels(),
            'models' => $this->getModelsModified(),
        ])->extends('lego::layouts.lego')->section('content');
    }

    protected function getModelsModified() : LengthAwarePaginator
    {
        $customOrderMethod = 'orderBy' . Str::studly($this->sortColumn);
        $hasCustomOrderMethod = method_exists($this, $customOrderMethod);

        $query = $this->model()::query()
            ->when($hasCustomOrderMethod, fn ($query) => $this->$customOrderMethod($query, $this->sortDirection))
            ->when(! $hasCustomOrderMethod && $this->sortColumn && $this->canSortColumn($this->sortColumn), fn ($query) => $query->orderBy($this->sortColumn, $this->sortDirection))
            ->where('organization_id', $this->organization)
            ->where('status', '<>', 'CANCELED');

        // Query main search column.
        if (! blank($this->searchQuery) && $this->canQueryMainSearchColumn()) {
            $mainSearchColumnQueryScopeMethod = $this->getMainSearchColumnQueryScopeMethod();
            $this->$mainSearchColumnQueryScopeMethod($query, $this->mainSearchColumn(), $this->searchQuery);
        }

        foreach ($this->columnFilters as $columnKey => $value) {
            if (blank($value)) {
                continue;
            }

            $customScopeMethod = 'scope' . Str::studly($columnKey);

            if (method_exists($this, $customScopeMethod)) {
                $this->$customScopeMethod($query, $value, $columnKey);

                continue;
            }

            $cast = $this->getCast($columnKey);
            $method = 'scopeQuery' . Str::ucfirst($cast);

            if (method_exists($this, $method)) {
                $this->$method($query, $columnKey, $value);
            }
        }

        return $query->paginate($this->perPage);
    }

//    public function processBreakdown()
//    {
//        $organization = Organization::where('id', $this->organization)->first();
//
//        $status = 'CONFIRMED';
//
//        /**
//         * BF-Debt (brought froward balance) = (SUM(orders amount before start date) - SUM(payments before start date))
//         * payments = records within range of start date and end date
//         * total = SUM(payments)
//         * balance = BF - total
//         */
//
//        $startDate = $this->startDate->startOfDay();
//        $endDate = $this->endDate->endOfDay();
//
////        $endDate = Carbon::createFromFormat('Y-m-d', $this->endDate)->endOfDay();
//
//        // -----------------
//        $sumOfOrdersAmountBeforeStartDate = Order::where('organization_id', $organization->id)
//            ->where('status', '<>', 'CANCELED')
//            ->where('pamtechoga_customer_orders.created_at', '<', $startDate)
//            ->select(DB::raw('SUM(volume * unit_price) AS total'))
//            ->first();
//
//        $sumOfPaymentsBeforeStartDate = Payment::where('organization_id', $organization->id)
//            ->where('status', $status)
//            ->where('created_at', '<', $startDate)
//            ->sum('amount');
//
//        $this->bfDebt = max($sumOfOrdersAmountBeforeStartDate?->total - $sumOfPaymentsBeforeStartDate, 0);
//
//        $this->totalPaymentsWithinRange = Payment::where('organization_id', $organization->id)
//            ->whereBetween('created_at', [$startDate, $endDate])
//            ->where('status', $status)
//            ->sum('amount');
//
//        $this->balance = max($this->bfDebt - $this->totalPaymentsWithinRange, 0);
//        // -----------------
//
//        $this->payments = Payment::where('organization_id', $organization->id)
//            ->whereBetween('created_at', [$startDate, $endDate])
//            ->where('status', $status)
//            ->orderBy('created_at', 'desc')
//            ->with('organization')
//            ->get();
//
//        $this->unverifiedPayments = Payment::where('organization_id', $organization->id)
//            ->whereBetween('created_at', [$startDate, $endDate])
//            ->where('status', '<>', $status)
//            ->orderBy('created_at', 'desc')
//            ->with('organization')
//            ->get();
//
//
////        return response()->json([
////            'status' => true,
////            'data' => $payments,
////            'bf_debt' => $bfDebt ?? 0.0,
////            'total' => $totalPaymentsWithinRange ?? 0.0,
////            'balance' => $balance ?? 0.0,
////        ]);
//    }

    public function calculateProfit($depot_order_id, $unit_price, $trucking_expense, $volume)
    {
        // Check if any of the input values are zero or null
        if (!$depot_order_id || !$unit_price || !$volume) {
            return 0;
        }

        // Retrieve the DepotOrder by its ID
        $depotOrder = DepotOrder::find($depot_order_id);

        // Calculate the cost price for the depot order
        $depotNewUnitPrice = $depotOrder->unit_price + $depotOrder->trucking_expense;
        $orderCostPrice = $volume * $depotNewUnitPrice;

        // Calculate the selling price for the order
        $orderSellingPrice = ($volume * $unit_price);

        // Calculate the profit
        return $orderSellingPrice - $orderCostPrice;
    }

    public function calculateOverallProfit()
    {
        $overallProfit = 0;

        foreach ($this->orders as $data) {
            // Check if any of the input values are zero or null
            if (!$data->depot_order_id || !$data->unit_price || !$data->volume) {
                continue;
            }

            // Retrieve the DepotOrder by its ID
            $depotOrder = DepotOrder::find($data->depot_order_id);

            // Calculate the cost price for the depot order
            $depotNewUnitPrice = $depotOrder->unit_price + $depotOrder->trucking_expense;
            $orderCostPrice = $data->volume * $depotNewUnitPrice;

            // Calculate the selling price for the order
            $orderSellingPrice = ($data->volume * $data->unit_price);

            // Calculate the profit for this order and add it to the overall profit
            $profit = $orderSellingPrice - $orderCostPrice;
            $overallProfit += $profit;
        }

        return $overallProfit;
    }

//    Export csv
    public function exportAsCSV()
    {
        $organization = Organization::where('id', $this->organization)->first()->name;

        // Generate your data to export (e.g., from a database query)
        $data = [
            [$organization . '\'s Orders'],
            ['Date', 'Product', 'Volume', 'Unit Price', 'Amount', 'Driver', 'status'],
        ];

        $filename = $organization. '-orders-breakdown.csv';

        $handle = fopen($filename, 'w');

        // Write the header row
        fputcsv($handle, $data[0]);
        fputcsv($handle, $data[1]);

        // Write the orders data rows
        foreach ($this->orders as $order) {
            $row = [];
            $row[] = $order->order_date?->format('Y-m-d');
            $row[] = $order->product->type;
            $row[] = number_format($order->volume);
            $row[] = number_format($order->unit_price);
            $row[] = number_format($order->unit_price * $order->volume);
            $row[] = $order->driver?->name ?? '';
            $row[] = $order->status;
            fputcsv($handle, $row);
        }

        // Write payments data rows
        $paymentsRow = [
            [' '], // Empty rows
            [$organization . '\'s Payments'],
            ['Payment Date', 'Amount', 'Status'], // Header row,
        ];

        fputcsv($handle, $paymentsRow[0]);
        fputcsv($handle, $paymentsRow[0]);
        fputcsv($handle, $paymentsRow[0]);
        fputcsv($handle, $paymentsRow[0]);
        fputcsv($handle, $paymentsRow[0]);
        fputcsv($handle, $paymentsRow[0]);
        fputcsv($handle, $paymentsRow[1]);
        fputcsv($handle, $paymentsRow[2]);

        foreach ($this->payments as $payment) {
            $row = [];
            $row[] = Carbon::parse($payment->payment_date)->format('Y-m-d');
            $row[] = number_format($payment->amount);
            $row[] = $order->status;

//            fputcsv($handle, $orders[$i]);
            fputcsv($handle, $row);
        }

        fclose($handle);

        return response()->stream(
            function () use ($filename) {
                readfile($filename);
            },
            200,
            [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            ]
        );
    }

}
