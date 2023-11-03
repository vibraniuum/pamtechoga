<?php

namespace Vibraniuum\Pamtechoga\Http\Livewire;

use Helix\Lego\Http\Livewire\Models\Form;
use Vibraniuum\Pamtechoga\Events\OrderUpdated;
use Vibraniuum\Pamtechoga\Models\Branch;
use Vibraniuum\Pamtechoga\Models\DepotOrder;
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
            'model.depot_order_id' => 'nullable',
            'model.product_id' => 'required',
            'model.status' => 'required',
            'model.organization_id' => 'required',
            'model.branch_id' => 'nullable',
            'model.volume' => 'required',
            'model.unit_price' => 'required',
            'model.driver_id' => 'nullable',
//            'model.made_down_payment' => 'required',
            'model.payment_deadline' => 'nullable',
            'model.trucking_expense' => 'nullable',
        ];
    }

    public function mount($order = null)
    {
        $this->setModel($order);
        if (! $this->model->exists) {
//            $this->model->made_down_payment = false;
            $this->model->trucking_expense = 0.00;
        }
    }

    public function view()
    {
        return 'pamtechoga::models.orders.form';
    }

    public function saved()
    {
        OrderUpdated::dispatch([
            'organization_id' => $this->model->organization_id
        ]);
    }

    public function model(): string
    {
        return Order::class;
    }

    public function calculateValue($type)
    {
        // Check if all data needed for calculation are set
        if (
            $this->model->depot_order_id &&
            $this->model->unit_price &&
            $this->model->volume
        ) {
            // Retrieve the DepotOrder by its ID
            $depotOrder = DepotOrder::find($this->model->depot_order_id);

            // Calculate the cost price for the depot order
            $depotNewUnitPrice = $depotOrder->unit_price + $depotOrder->trucking_expense;
            $orderCostPrice = $this->model->volume * $depotNewUnitPrice;

            // Calculate the selling price for the order
            $orderSellingPrice = ($this->model->volume * $this->model->unit_price);

            // Calculate and return the requested value based on the type
            $value = match ($type) {
                'profit' => $orderSellingPrice - $orderCostPrice,
                'costPrice' => max($orderCostPrice, 0),
                'sellingPrice' => max($orderSellingPrice, 0),
                default => 0,
            };

            return number_format($value);
        }

        return 0;
    }

    public function allDepotOrders()
    {
        return DepotOrder::all();
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
