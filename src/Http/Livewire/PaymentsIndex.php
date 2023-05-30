<?php

namespace Vibraniuum\Pamtechoga\Http\Livewire;

use Helix\Lego\Http\Livewire\Models\Index as BaseIndex;
use Vibraniuum\Pamtechoga\Models\Payment;

class PaymentsIndex extends BaseIndex
{

    public function model(): string
    {
        return Payment::class;
    }

    public function columns(): array
    {
        return [
            'amount' => 'Amount Paid (NGN)',
            'status' => 'Payment Status',
            'type' => 'Payment Type',
            'organization' => 'Organization',
            'payment_date' => 'Payment Made On',
            'updated_at' => 'Last updated',
        ];
    }

    public function mainSearchColumn(): string|false
    {
        return 'amount';
    }

    public function render()
    {
        return view('pamtechoga::models.payments.index', [
            'models' => $this->getModels(),
        ])->extends('lego::layouts.lego')->section('content');
    }
}
