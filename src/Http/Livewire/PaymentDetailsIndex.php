<?php

namespace Vibraniuum\Pamtechoga\Http\Livewire;

use Helix\Lego\Http\Livewire\Models\Index as BaseIndex;
use Vibraniuum\Pamtechoga\Models\PaymentDetail;

class PaymentDetailsIndex extends BaseIndex
{

    public function model(): string
    {
        return PaymentDetail::class;
    }

    public function columns(): array
    {
        return [
            'account_name' => 'Account Name',
            'bank' => 'Bank',
            'account_number' => 'Account Number',
            'account_type' => 'Account Type',
            'updated_at' => 'Last updated',
        ];
    }

    public function mainSearchColumn(): string|false
    {
        return 'account_name';
    }

    public function render()
    {
        return view('pamtechoga::models.payment-details.index', [
            'models' => $this->getModels(),
        ])->extends('lego::layouts.lego')->section('content');
    }
}
