<?php

namespace Vibraniuum\Pamtechoga\Http\Livewire;

use Helix\Lego\Http\Livewire\Models\Index as BaseIndex;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;
use Vibraniuum\Pamtechoga\Models\Payment;

class OrganizationPaymentsIndex extends BaseIndex
{
    public $organization;

    public function model(): string
    {
        return Payment::class;
    }

    public function columns(): array
    {
        return [
            'amount' => 'Amount Paid (NGN)',
            'status' => 'Payment Status',
            'type' => 'Payment Type',
            'organization' => 'Organization',
            'payment_date' => 'Payment Made On',
            'updated_at' => 'Last updated',
        ];
    }

    public function mainSearchColumn(): string|false
    {
        return 'amount';
    }

    public function render()
    {
        return view('pamtechoga::models.payments.index', [
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
            ->where('organization_id', $this->organization);

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

}
