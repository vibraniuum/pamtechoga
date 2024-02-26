<?php

namespace Vibraniuum\Pamtechoga\Http\Livewire;

use Helix\Lego\Http\Livewire\Models\Index as BaseIndex;
use Vibraniuum\Pamtechoga\Models\DepotOrder;
use Vibraniuum\Pamtechoga\Models\DepotPickup;

class DepotOrderBreakdown extends BaseIndex
{

    public $depotOrder;

    public function model(): string
    {
        return DepotOrder::class;
    }

    public function columns(): array
    {
        return [];
    }

    public function mainSearchColumn(): string|false
    {
        return 'product';
    }

    public function unloadedVolume()
    {
        $depotOrder = DepotOrder::where('id', $this->depotOrder)->first();

        $loadedVolume = $this->loadedVolume() + $this->deliveredVolume();
        $unloadedVolume = $depotOrder->volume - $loadedVolume;

        return $unloadedVolume;
    }

    public function loadedVolume()
    {
        $loadedVolume = DepotPickup::where('depot_order_id', $this->depotOrder)->where('status', 'LOADED')->sum('volume_assigned');

        return $loadedVolume;
    }

    public function deliveredVolume()
    {
        $loadedVolume = DepotPickup::where('depot_order_id', $this->depotOrder)->where('status', 'COMPLETED')->sum('volume_assigned');

        return $loadedVolume;
    }

    public function numberOfLoadedTrucks()
    {
        return DepotPickup::where('depot_order_id', $this->depotOrder)->where('status', 'LOADED')->orWhere('status', 'COMPLETED')->count();
    }

    public function render()
    {
        return view('pamtechoga::models.depot-orders.breakdown', [
            'models' => DepotPickup::where('depot_order_id', $this->depotOrder)->get(),
        ])->extends('lego::layouts.lego')->section('content');
    }
}
