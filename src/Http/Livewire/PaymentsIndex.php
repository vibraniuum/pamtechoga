<?php

namespace Vibraniuum\Pamtechoga\Http\Livewire;

use Helix\Lego\Http\Livewire\Models\Index as BaseIndex;
use Vibraniuum\Pamtechoga\Models\Payment;
use Vibraniuum\Pamtechoga\Traits\DateFilter;

class PaymentsIndex extends BaseIndex
{
    use DateFilter;

    protected $listeners = ['filterApplied'];

    public function filterApplied($filterData)
    {
        $this->startDate = $filterData['startDate'];
        $this->endDate = $filterData['endDate'];
        $this->mount();
    }

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
        if($this->canResetDate) {
            $this->resetDates();
        }
        $this->applyFilter();
        return view('pamtechoga::models.payments.index', [
            'models' => $this->getModels(),
        ])->extends('lego::layouts.lego')->section('content');
    }
}
