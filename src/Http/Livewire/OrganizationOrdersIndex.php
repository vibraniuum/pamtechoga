<?php

namespace Vibraniuum\Pamtechoga\Http\Livewire;

use Carbon\Carbon;
use Helix\Lego\Http\Livewire\Models\Index as BaseIndex;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Vibraniuum\Pamtechoga\Models\Order;
use Vibraniuum\Pamtechoga\Models\Organization;
use Vibraniuum\Pamtechoga\Models\OrganizationUser;
use Vibraniuum\Pamtechoga\Models\Payment;
use Vibraniuum\Pamtechoga\Traits\DateFilter;

class OrganizationOrdersIndex extends BaseIndex
{
    use DateFilter;

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

            'date' => 'Date',
            'product' => 'Product Type',
            'volume' => 'Volume (Litres)',
            'unit_price' => 'Unit Price (NGN)',
            'amount' => 'Amount (NGN)',
            'driver' => 'Driver',
            'status' => 'Status',
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
        $this->applyFilter();

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

    public function processBreakdown()
    {
        $organization = Organization::where('id', $this->organization)->first();

        $status = 'CONFIRMED';

        /**
         * BF-Debt (brought froward balance) = (SUM(orders amount before start date) - SUM(payments before start date))
         * payments = records within range of start date and end date
         * total = SUM(payments)
         * balance = BF - total
         */

        $startDate = $this->startDate->startOfDay();
        $endDate = $this->endDate->endOfDay();

//        $endDate = Carbon::createFromFormat('Y-m-d', $this->endDate)->endOfDay();

        // -----------------
        $sumOfOrdersAmountBeforeStartDate = Order::where('organization_id', $organization->id)
            ->where('status', '<>', 'CANCELED')
            ->where('pamtechoga_customer_orders.created_at', '<', $startDate)
            ->select(DB::raw('SUM(volume * unit_price) AS total'))
            ->first();

        $sumOfPaymentsBeforeStartDate = Payment::where('organization_id', $organization->id)
            ->where('status', $status)
            ->where('created_at', '<', $startDate)
            ->sum('amount');

        $this->bfDebt = max($sumOfOrdersAmountBeforeStartDate?->total - $sumOfPaymentsBeforeStartDate, 0);

        $this->totalPaymentsWithinRange = Payment::where('organization_id', $organization->id)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('status', $status)
            ->sum('amount');

        $this->balance = max($this->bfDebt - $this->totalPaymentsWithinRange, 0);
        // -----------------

        $this->payments = Payment::where('organization_id', $organization->id)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('status', $status)
            ->orderBy('created_at', 'desc')
            ->with('organization')
            ->get();


//        return response()->json([
//            'status' => true,
//            'data' => $payments,
//            'bf_debt' => $bfDebt ?? 0.0,
//            'total' => $totalPaymentsWithinRange ?? 0.0,
//            'balance' => $balance ?? 0.0,
//        ]);
    }

}
