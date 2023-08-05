<?php

namespace Vibraniuum\Pamtechoga\Http\Livewire;

use Helix\Lego\Http\Livewire\Models\Index as BaseIndex;
use Vibraniuum\Pamtechoga\Models\Order;
use Vibraniuum\Pamtechoga\Traits\DateFilter;

class SalesIndex extends BaseIndex
{
    use DateFilter;

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
            'organization_name' => 'Organization Name',
            'volume' => 'Volume (Litres)',
            'status' => 'Status',
            'unit_price' => 'Unit Price (NGN)',
            'updated_at' => 'Last updated',
        ];
    }

    public function mainSearchColumn(): string|false
    {
        return 'organization_name';
    }

    public function render()
    {
        if($this->canResetDate) {
            $this->resetDates();
        }
        $this->applyFilter();
        return view('pamtechoga::models.sales.index', [
            'models' => $this->getModels(),
        ])->extends('lego::layouts.lego')->section('content');
    }
}
