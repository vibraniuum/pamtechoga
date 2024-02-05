<?php

namespace Vibraniuum\Pamtechoga\Http\Livewire;

use Helix\Lego\Http\Livewire\Models\Index as BaseIndex;
use Vibraniuum\Pamtechoga\Models\DepotPickup;

class DepotPickupsIndex extends BaseIndex
{

    public function model(): string
    {
        return DepotPickup::class;
    }

    public function columns(): array
    {
        return [
            'depot_name' => 'Depot Name',
            'volume' => 'Assigned Volume (Litres)',
            'status' => 'Status',
            'unit_price' => 'Unit Price (NGN)',
            'driver' => 'Driver',
            'loaded_datetime' => 'Date Loaded',
        ];
    }

    public function mainSearchColumn(): string|false
    {
        return 'volume_assigned';
    }

    public function render()
    {
        return view('pamtechoga::models.depot-pickups.index', [
            'models' => $this->getModels(),
        ])->extends('lego::layouts.lego')->section('content');
    }
}
