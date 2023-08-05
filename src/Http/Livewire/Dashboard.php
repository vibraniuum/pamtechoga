<?php

namespace Vibraniuum\Pamtechoga\Http\Livewire;

use Helix\Lego\Http\Livewire\Models\Index as BaseIndex;
use Vibraniuum\Pamtechoga\Models\DepotOrder;
use Vibraniuum\Pamtechoga\Traits\DateFilter;

class Dashboard extends BaseIndex
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
        return DepotOrder::class;
    }

    public function columns(): array
    {
        return [
            'depot_name' => 'Depot Name',
            'product' => 'Product',
            'volume' => 'Volume (Litres)',
            'status' => 'Status',
            'unit_price' => 'Unit Price (NGN)',
            'updated_at' => 'Last updated',
        ];
    }

    public function mainSearchColumn(): string|false
    {
        return 'depot_name';
    }

    public function render()
    {
        if($this->canResetDate) {
            $this->resetDates();
        }
        $this->applyFilter();
        return view('pamtechoga::models.dashboard.index', [
            'models' => $this->getModels(),
        ])->extends('lego::layouts.lego')->section('content');
    }
}
