<?php

namespace Vibraniuum\Pamtechoga\Http\Livewire;

use Helix\Lego\Http\Livewire\Models\Index as BaseIndex;
use Vibraniuum\Pamtechoga\Models\Product;

class ProductsIndex extends BaseIndex
{

    public function model(): string
    {
        return Product::class;
    }

    public function columns(): array
    {
        return [
            'type' => 'Product Type',
            'market_price' => 'Market Price (NGN)',
            'instock' => 'Instock',
            'updated_at' => 'Last updated',
        ];
    }

    public function mainSearchColumn(): string|false
    {
        return 'type';
    }

    public function render()
    {
        return view('pamtechoga::models.products.index', [
            'models' => $this->getModels(),
        ])->extends('lego::layouts.lego')->section('content');
    }
}
