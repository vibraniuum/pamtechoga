<?php

namespace Vibraniuum\Pamtechoga\Http\Livewire;

use Helix\Lego\Http\Livewire\Traits\InteractsWithTopBar;
use Helix\Lego\Http\Livewire\Traits\ProvidesFeedback;
use Illuminate\Support\Collection;
use Livewire\Component;
use Vibraniuum\Pamtechoga\Models\DepotOrder;
use Vibraniuum\Pamtechoga\Models\DepotPickup;
use Vibraniuum\Pamtechoga\Models\Driver;

class DepotPickupsCreate extends Component
{
    use InteractsWithTopBar;

    use ProvidesFeedback;

    protected bool $canBeViewed = false;

    public Collection $extraData;

    public $model;

    protected $listeners = [
        'dirty',
        'bellNotificationReceived',
        'saving',
        'save',
    ];

    public function rules()
    {
        return [
            'model.depot_order_id' => 'required',
            'model.status' => 'required',
//            'model.driver_id' => 'required',
//            'model.volume_assigned' => 'required',
            'model.pickup_datetime' => 'nullable',
            'model.loaded_datetime' => 'nullable',
        ];
    }

    public function mount($depotPickup = null)
    {
//        $this->setModel($depotPickup);
        $this->extraData = collect([]);
    }

    public function updating()
    {
        $this->markAsDirty();
    }

    public function save()
    {
        foreach ($this->extraData as $data) {
            DepotPickup::create(
                [
                    'depot_order_id' => $this->model['depot_order_id'],
                    'status' => $this->model['status'],
                    'pickup_datetime' => $this->model['pickup_datetime'],
                    'loaded_datetime' => $this->model['loaded_datetime'],
                    'driver_id' => $data['driver_id'],
                    'volume_assigned' => $data['volume_assigned'],
                ]
            );
        }
        $this->markAsClean();
        $this->confetti();
        $this->redirectRoute('lego.pamtechoga.depot-pickups.index');
    }

    public function render()
    {
        return view('pamtechoga::models.depot-pickups.create')->extends('lego::layouts.lego')->section('content');
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

    public function addExtraData()
    {
        $this->extraData[] = ['driver_id' => 0, 'volume_assigned' => 45000];
    }


}
