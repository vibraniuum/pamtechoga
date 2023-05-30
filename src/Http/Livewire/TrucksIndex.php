<?php

namespace Vibraniuum\Pamtechoga\Http\Livewire;

use Helix\Lego\Http\Livewire\Models\Index as BaseIndex;
use Vibraniuum\Pamtechoga\Models\Truck;

class TrucksIndex extends BaseIndex
{

    public function model(): string
    {
        return Truck::class;
    }

    public function columns(): array
    {
        return [
            'plate_number' => 'Plate Number',
            'driver' => 'Driver',
            'volume_capacity' => 'Volume Capacity',
            'available_volume' => 'Available Volume',
            'updated_at' => 'Last updated',
        ];
    }

    public function mainSearchColumn(): string|false
    {
        return 'plate_number';
    }

    public function render()
    {
        return view('pamtechoga::models.trucks.index', [
            'models' => $this->getModels(),
        ])->extends('lego::layouts.lego')->section('content');
    }
}
