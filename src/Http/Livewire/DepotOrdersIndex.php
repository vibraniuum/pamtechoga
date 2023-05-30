<?php

namespace Vibraniuum\Pamtechoga\Http\Livewire;

use Helix\Lego\Http\Livewire\Models\Index as BaseIndex;
use Vibraniuum\Pamtechoga\Models\DepotOrder;

class DepotOrdersIndex extends BaseIndex
{

    public function model(): string
    {
        return DepotOrder::class;
    }

    public function columns(): array
    {
        return [
            'depot_name' => 'Depot Name',
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
        return view('pamtechoga::models.depot-orders.index', [
            'models' => $this->getModels(),
        ])->extends('lego::layouts.lego')->section('content');
    }
}
