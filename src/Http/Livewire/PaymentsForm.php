<?php

namespace Vibraniuum\Pamtechoga\Http\Livewire;

use Helix\Lego\Http\Livewire\Models\Form;
use Vibraniuum\Pamtechoga\Models\DepotOrder;
use Vibraniuum\Pamtechoga\Models\Order;
use Vibraniuum\Pamtechoga\Models\Organization;
use Vibraniuum\Pamtechoga\Models\Payment;

class PaymentsForm extends Form
{
    protected bool $canBeViewed = false;

    public function rules()
    {
        return [
            'model.customer_order_id' => 'nullable',
            'model.depot_order_id' => 'nullable',
            'model.organization_id' => 'nullable',
            'model.status' => 'required',
            'model.type' => 'required',
            'model.amount' => 'required',
            'model.payment_date' => 'required',
            'model.reference_description' => 'nullable',
        ];
    }

    public function mount($payment = null)
    {
        $this->setModel($payment);

        $this->model->user_id = 1;
    }

    public function saving()
    {
        $this->model->user_id = auth()->id();
    }

    public function view()
    {
        return 'pamtechoga::models.payments.form';
    }

    public function model(): string
    {
        return Payment::class;
    }

    public function allDepotOrders()
    {
        return DepotOrder::all();
    }

    public function allCustomerOrders()
    {
        return Order::all();
    }

    public function allOrganizations()
    {
        return Organization::all();
    }

}
