<?php

namespace Vibraniuum\Pamtechoga\Http\Livewire;

use Helix\Lego\Http\Livewire\Models\Index as BaseIndex;
use Vibraniuum\Pamtechoga\Models\Driver;

class DriversIndex extends BaseIndex
{

    public function model(): string
    {
        return Driver::class;
    }

    public function columns(): array
    {
        return [
            'name' => 'Name',
            'phone' => 'Phone',
            'email' => 'Email',
            'truck' => 'Truck',
            'updated_at' => 'Last updated',
        ];
    }

    public function mainSearchColumn(): string|false
    {
        return 'name';
    }

    public function render()
    {
        return view('pamtechoga::models.drivers.index', [
            'models' => $this->getModels(),
        ])->extends('lego::layouts.lego')->section('content');
    }
}
