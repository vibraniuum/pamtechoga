<?php

namespace Vibraniuum\Pamtechoga\Http\Livewire;

use Helix\Lego\Http\Livewire\Models\Index as BaseIndex;
use Vibraniuum\Pamtechoga\Models\FuelPrice;
use Vibraniuum\Pamtechoga\Models\PaymentDetail;

class FuelPricesIndex extends BaseIndex
{

    public function model(): string
    {
        return FuelPrice::class;
    }

    public function columns(): array
    {
        return [
            'company_name' => 'Account Name',
            'petrol' => 'Petrol (NGN)',
            'diesel' => 'Diesel (NGN)',
            'premium' => 'Premium (NGN)',
            'updated_at' => 'Last updated',
        ];
    }

    public function mainSearchColumn(): string|false
    {
        return 'company_name';
    }

    public function render()
    {
        return view('pamtechoga::models.fuel-prices.index', [
            'models' => $this->getModels(),
        ])->extends('lego::layouts.lego')->section('content');
    }
}
