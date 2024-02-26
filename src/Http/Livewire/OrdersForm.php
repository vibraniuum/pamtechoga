<?php

namespace Vibraniuum\Pamtechoga\Http\Livewire;

use Helix\Lego\Http\Livewire\Models\Form;
use Vibraniuum\Pamtechoga\Events\OrderUpdated;
use Vibraniuum\Pamtechoga\Models\Branch;
use Vibraniuum\Pamtechoga\Models\DepotOrder;
use Vibraniuum\Pamtechoga\Models\DepotPickup;
use Vibraniuum\Pamtechoga\Models\Driver;
use Vibraniuum\Pamtechoga\Models\Order;
use Vibraniuum\Pamtechoga\Models\OrderDebt;
use Vibraniuum\Pamtechoga\Models\Organization;
use Vibraniuum\Pamtechoga\Models\Product;
use Vibraniuum\Pamtechoga\Services\ConfirmPayment;

class OrdersForm extends Form
{
    protected bool $canBeViewed = false;

    public $allDepotPickups = [];

    public function rules()
    {
        return [
            'model.order_date' => 'nullable',
            'model.depot_order_id' => 'nullable',
            'model.depot_pickup_id' => 'required',
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

        if ($this->model->depot_order_id) {
            $depotOrder = DepotOrder::find($this->model->depot_order_id);
            if ($depotOrder) {
                $this->model->product_id = $depotOrder->product_id;
                $this->allDepotPickups = DepotPickup::where('depot_order_id', $this->model->depot_order_id)->where('status', 'LOADED')->get();

                $completedPickups = DepotPickup::where('depot_order_id', $this->model->depot_order_id)->where('status', 'COMPLETED');

                $this->allDepotPickups = $this->allDepotPickups->merge($completedPickups);
            }
        }
    }

    public function isAssignedToAnotherOrder($depotPickupId)
    {
        $order = Order::where('depot_pickup_id', $depotPickupId)->first();
        if ($order) {
            return true;
        }

        return false;
    }

    public function deleting()
    {
        // reset pickup status
        if ($this->model->depot_pickup_id) {
            $depotPickup = DepotPickup::find($this->model->depot_pickup_id);
            if ($depotPickup) {
                $depotPickup->status = 'LOADED';
                $depotPickup->save();
            }
        }

        // reset depot order status
        if ($this->model->depot_order_id) {
            $depotOrder = DepotOrder::find($this->model->depot_order_id);
            if ($depotOrder) {
                $depotOrder->status = 'LOADED';
                $depotOrder->save();
            }
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

    public function setProduct()
    {
        if ($this->model->depot_order_id) {
            $depotOrder = DepotOrder::find($this->model->depot_order_id);
            if ($depotOrder) {
                $this->model->product_id = $depotOrder->product_id;
                $this->allDepotPickups = DepotPickup::where('depot_order_id', $this->model->depot_order_id)->where('status', 'LOADED')->get();

                $completedPickups = DepotPickup::where('depot_order_id', $this->model->depot_order_id)->where('status', 'COMPLETED');

                $this->allDepotPickups = $this->allDepotPickups->merge($completedPickups);
            }
        }
    }

    public function setDriver()
    {
        if ($this->model->depot_pickup_id) {
            $depotPickup = DepotPickup::find($this->model->depot_pickup_id);
            if ($depotPickup) {
                $this->model->driver_id = $depotPickup->driver->id;
            }
        }
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
        if ($this->model->depot_pickup_id) {
            $depotPickup = DepotPickup::find($this->model->depot_pickup_id);
            if ($depotPickup) {
                $depotPickup->status = 'COMPLETED';
                $depotPickup->volume_balance = 0;
                $depotPickup->save();

                $this->model->status = 'DELIVERED';
                $this->model->save();
                $this->confetti();
            }
        }

        // set depot order status to 'COMPLETED' if all pickups are completed
        // and if all pickups volumes balance is 0
        // and volume equals the depot order volume
        $depotOrder = DepotOrder::find($this->model->depot_order_id);
        if ($depotOrder) {
            $depotOrderPickups = DepotPickup::where('depot_order_id', $depotOrder->id)->get();
            $completedPickups = $depotOrderPickups->where('status', 'COMPLETED');
            $completedPickupsVolumes = $completedPickups->sum('volume_assigned');
            if ($completedPickupsVolumes == $depotOrder->volume) {
                $depotOrder->status = 'COMPLETED';
                $depotOrder->save();
            }
        }
    }

    public function balance($depotOrderId)
    {
        $loadedVolume = DepotPickup::where('depot_order_id', $depotOrderId)->where('status', 'LOADED')->sum('volume_assigned');

        return $loadedVolume;
    }

    public function allDepotOrders()
    {
        return DepotOrder::where('status', 'LOADED')->orWhere('status', 'UNLOADED')->orderBy('created_at', 'desc')->orWhere('id', $this->model->depot_order_id)->get();
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
