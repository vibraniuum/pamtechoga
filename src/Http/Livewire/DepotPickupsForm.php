<?php

namespace Vibraniuum\Pamtechoga\Http\Livewire;

use Helix\Lego\Http\Livewire\Models\Form;
use Vibraniuum\Pamtechoga\Models\DepotOrder;
use Vibraniuum\Pamtechoga\Models\DepotPickup;
use Vibraniuum\Pamtechoga\Models\Driver;

class DepotPickupsForm extends Form
{
    protected bool $canBeViewed = false;

    public function rules()
    {
        return [
            'model.depot_order_id' => 'required',
            'model.status' => 'required',
            'model.driver_id' => 'required',
            'model.volume_assigned' => 'required',
            'model.pickup_datetime' => 'nullable',
            'model.loaded_datetime' => 'nullable',
        ];
    }

    public function mount($depotPickup = null)
    {
        $this->setModel($depotPickup);
    }

    public function view()
    {
        return 'pamtechoga::models.depot-pickups.form';
    }

    public function model(): string
    {
        return DepotPickup::class;
    }

    public function allDepotOrders()
    {
        return DepotOrder::all();
    }

    public function allDrivers()
    {
        return Driver::all(); // show if driver is available or not (if already assigned to an order that is not complete)
    }


}
