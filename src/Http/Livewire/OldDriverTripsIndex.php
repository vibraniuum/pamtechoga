<?php

namespace Vibraniuum\Pamtechoga\Http\Livewire;

use Helix\Lego\Http\Livewire\Models\Index as BaseIndex;
use Vibraniuum\Pamtechoga\Models\OldDriverTrip;

class OldDriverTripsIndex extends BaseIndex
{

    public function model(): string
    {
        return OldDriverTrip::class;
    }

    public function columns(): array
    {
        return [
            'driver' => 'Driver',
            'number_of_trips' => 'Number of Old Trips',
            'updated_at' => 'Last updated',
        ];
    }

    public function mainSearchColumn(): string|false
    {
        return 'number_of_trips';
    }

    public function render()
    {
        return view('pamtechoga::models.old-driver-trips.index', [
            'models' => $this->getModels(),
        ])->extends('lego::layouts.lego')->section('content');
    }
}
