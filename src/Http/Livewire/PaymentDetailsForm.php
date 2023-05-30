<?php

namespace Vibraniuum\Pamtechoga\Http\Livewire;

use Helix\Lego\Http\Livewire\Models\Form;
use Vibraniuum\Pamtechoga\Models\PaymentDetail;

class PaymentDetailsForm extends Form
{
    protected bool $canBeViewed = false;

    public function rules()
    {
        return [
            'model.account_name' => 'required',
            'model.bank' => 'required',
            'model.account_number' => 'required',
            'model.account_type' => 'required',
        ];
    }

    public function mount($paymentDetail = null)
    {
        $this->setModel($paymentDetail);
    }

    public function view()
    {
        return 'pamtechoga::models.payment-details.form';
    }

    public function model(): string
    {
        return PaymentDetail::class;
    }


}
