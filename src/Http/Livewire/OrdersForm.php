<?php

namespace Vibraniuum\Pamtechoga\Http\Livewire;

use Helix\Lego\Http\Livewire\Models\Form;
use Vibraniuum\Pamtechoga\Events\OrderUpdated;
use Vibraniuum\Pamtechoga\Models\Branch;
use Vibraniuum\Pamtechoga\Models\Driver;
use Vibraniuum\Pamtechoga\Models\Order;
use Vibraniuum\Pamtechoga\Models\Organization;
use Vibraniuum\Pamtechoga\Models\Product;

class OrdersForm extends Form
{
    protected bool $canBeViewed = false;

    public function rules()
    {
        return [
            'model.product_id' => 'required',
            'model.status' => 'required',
            'model.organization_id' => 'required',
            'model.branch_id' => 'nullable',
            'model.volume' => 'required',
            'model.unit_price' => 'required',
            'model.driver_id' => 'nullable',
            'model.made_down_payment' => 'required',
            'model.trucking_expense' => 'nullable',
        ];
    }

    public function mount($order = null)
    {
        $this->setModel($order);
        if (! $this->model->exists) {
            $this->model->made_down_payment = false;
            $this->model->trucking_expense = 0.00;
        }
    }

    public function view()
    {
        return 'pamtechoga::models.orders.form';
    }

    public function saved()
    {
        OrderUpdated::dispatch();
    }

    public function model(): string
    {
        return Order::class;
    }

    public function allProducts()
    {
        return Product::all();
    }

    public function allDrivers()
    {
        return Driver::all(); // show if driver is available or not (if already assigned to an order that is not complete)
    }

    public function allOrganizations()
    {
        return Organization::all();
    }

    public function branchesOfSelectedOrganization()
    {
        return Branch::query()->where('organization_id', $this->model?->organization_id)->get();
    }


}
