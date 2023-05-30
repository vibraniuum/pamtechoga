<?php

namespace Vibraniuum\Pamtechoga\Http\Livewire;

use Helix\Lego\Http\Livewire\Models\Index as BaseIndex;
use Vibraniuum\Pamtechoga\Models\Depot;

class DepotsIndex extends BaseIndex
{

    public function model(): string
    {
        return Depot::class;
    }

    public function columns(): array
    {
        return [
            'name' => 'Depot Name',
            'address' => 'Address',
            'updated_at' => 'Last updated',
        ];
    }

    public function mainSearchColumn(): string|false
    {
        return 'name';
    }

    public function render()
    {
        return view('pamtechoga::models.depots.index', [
            'models' => $this->getModels(),
        ])->extends('lego::layouts.lego')->section('content');
    }
}
