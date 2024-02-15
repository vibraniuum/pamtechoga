<?php

namespace Vibraniuum\Pamtechoga\Http\Livewire;

use Helix\Lego\Http\Livewire\Models\Form;
use Vibraniuum\Pamtechoga\Events\OrderUpdated;
use Vibraniuum\Pamtechoga\Models\Branch;
use Vibraniuum\Pamtechoga\Models\DepotOrder;
use Vibraniuum\Pamtechoga\Models\Driver;
use Vibraniuum\Pamtechoga\Models\Order;
use Vibraniuum\Pamtechoga\Models\OrderDebt;
use Vibraniuum\Pamtechoga\Models\Organization;
use Vibraniuum\Pamtechoga\Models\Product;
use Vibraniuum\Pamtechoga\Services\ConfirmPayment;

class OrdersForm extends Form
{
    protected bool $canBeViewed = false;

    public function rules()
    {
        return [
            'model.order_date' => 'nullable',
            'model.depot_order_id' => 'nullable',
            'model.product_id' => 'required',
            'model.status' => 'required',
            'model.organization_id' => 'required',
            'model.branch_id' => 'nullable',
            'model.volume' => 'required',
            'model.unit_price' => 'required',
            'model.driver_id' => 'nullable',
            'model.payment_deadline' => 'nullable',
        ];
    }

    public function mount($order = null)
    {
        $this->setModel($order);
        if (! $this->model->exists) {
            $this->model->status = 'PENDING';
        }
    }

    public function view()
    {
        return 'pamtechoga::models.orders.form';
    }

    public function saving()
    {
        if(is_null($this->model->status))
        $this->model->status = 'PENDING';
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
//                'sellingPrice' => max($orderSellingPrice, 0) + ((float) $this->model->trucking_expense ?? 0),
                'sellingPrice' => max($orderSellingPrice, 0),
                default => 0,
            };

            return number_format($value);
        }

        return 0;
    }

    public function markAsProcessing()
    {
        $this->model->status = 'PROCESSING';
        $this->model->save();

        // save order as debt
        OrderDebt::create([
            'organization_id' => $this->model->organization_id,
            'order_id' => $this->model->id,
//            'balance' => ($this->model->volume * $this->model->unit_price) + $this->model->trucking_expense,
            'balance' => ($this->model->volume * $this->model->unit_price), // with no trucking expense
        ]);

        $this->confetti();
    }

    public function markAsDispatched()
    {
        $this->model->status = 'DISPATCHED';
        $this->model->save();
        $this->confetti();
    }

    public function markAsDelivered()
    {
        $this->model->status = 'DELIVERED';
        $this->model->save();
        $this->confetti();
    }

    public function allDepotOrders()
    {
        return DepotOrder::orderBy('created_at', 'desc')->get();
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
