<?php

namespace Vibraniuum\Pamtechoga\Http\Livewire;

use Helix\Lego\Http\Livewire\Models\Index as BaseIndex;
use Vibraniuum\Pamtechoga\Models\Truck;
use Vibraniuum\Pamtechoga\Models\Zone;

class ZonesIndex extends BaseIndex
{

    public function model(): string
    {
        return Zone::class;
    }

    public function columns(): array
    {
        return [
            'name' => 'Zone Name',
            'updated_at' => 'Last updated',
        ];
    }

    public function mainSearchColumn(): string|false
    {
        return 'name';
    }

    public function render()
    {
        return view('pamtechoga::models.zones.index', [
            'models' => $this->getModels(),
        ])->extends('lego::layouts.lego')->section('content');
    }
}
