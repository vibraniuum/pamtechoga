<?php

namespace Vibraniuum\Pamtechoga\Http\Livewire;

use Helix\Lego\Http\Livewire\Models\Index as BaseIndex;
use Vibraniuum\Pamtechoga\Models\Order;

class SalesIndex extends BaseIndex
{

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
        return view('pamtechoga::models.sales.index', [
            'models' => $this->getModels(),
        ])->extends('lego::layouts.lego')->section('content');
    }
}
