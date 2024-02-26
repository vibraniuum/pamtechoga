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
//            'model.status' => 'required',
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
            if ($data['driver_id'] == 0) {
                continue;
            }
            DepotPickup::create(
                [
                    'depot_order_id' => $this->model['depot_order_id'],
                    'status' => 'LOADED',
                    'loaded_datetime' => $this->model['loaded_datetime'],
                    'driver_id' => $data['driver_id'],
                    'volume_assigned' => $data['volume_assigned'],
                    'volume_balance' => $data['volume_assigned'],
                ]
            );
        }

        if($this->unloadedVolume() <= 0) {
            DepotOrder::where('id', $this->model['depot_order_id'])->update(['status' => 'LOADED']);
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
        // return all depot orders that have not been fully loaded
        $depotOrders = DepotOrder::where('status', 'UNLOADED')->get();

        return $depotOrders;
    }

    public function allDrivers($loopedDriverId = null)
    {
        $selectedDrivers = [];
        foreach ($this->extraData as $data) {
            $selectedDrivers[] = $data['driver_id'];
        }

        // only return drivers that do not have a depot pickup with status 'LOADED' OR 'COMPLETED' for the depot order
        $drivers = Driver::whereDoesntHave('depotPickups', function ($query) {
            $query->where('status', 'LOADED')->orWhere('status', 'COMPLETED');
        })->get();

        $completedPickupDrivers = Driver::whereHas('depotPickups', function ($query) {
            $query->where('status', 'COMPLETED');
        })->get();

        $drivers = $drivers->merge($completedPickupDrivers);

        // remove selected drivers from the list, make such that the current looped driver is in the selected drivers array
        $drivers = $drivers->reject(function ($driver) use ($selectedDrivers, $loopedDriverId) {
            return in_array($driver->id, $selectedDrivers) && !($loopedDriverId == $driver->id);
        });

        return $drivers;
    }

    public function addExtraData()
    {
        if($this->loadedVolumeIsOutOfRange()) {
            $this->emit('toast', 'error', 'The loaded volume exceeds the unloaded volume');
            return;
        }

        $volumeToAssign = $this->unloadedVolume() - $this->newloadedVolume();

        if ($volumeToAssign <= 0) {
            $this->emit('toast', 'error', 'The depot order has been fully loaded');
            return;
        }

        if ($volumeToAssign > 45000) {
            $volumeToAssign = 45000;
        }

        $this->extraData[] = ['driver_id' => 0, 'volume_assigned' => $volumeToAssign];
    }

    public function loadedVolumeIsOutOfRange()
    {
        return $this->newloadedVolume() >= $this->unloadedVolume();
    }

    public function unloadedVolume()
    {
        $depotOrder = DepotOrder::find($this->model['depot_order_id']);

        if (!$depotOrder) {
            return 0;
        }

        $loadedVolume = $this->loadedVolume();
        $unloadedVolume = $depotOrder->volume - $loadedVolume;

        return $unloadedVolume;
    }

    public function loadedVolume()
    {
        $loadedVolume = DepotPickup::where('depot_order_id', $this->model['depot_order_id'])->where('status', 'LOADED')->sum('volume_assigned');
        $completedVolume = DepotPickup::where('depot_order_id', $this->model['depot_order_id'])->where('status', 'COMPLETED')->sum('volume_assigned');

        $loadedVolume = $loadedVolume + $completedVolume;

        return $loadedVolume;
    }

    public function newloadedVolume()
    {
        $loadedVolume = 0;
        foreach ($this->extraData as $data) {
            $loadedVolume += $data['volume_assigned'];
        }

        return $loadedVolume;
    }

    public function checkThatVolumeIsNotOutOfRange($index)
    {
        $driverId = $this->extraData[$index]['driver_id'];
        $driver = Driver::where('id', $driverId)->first();
        $volumeAssigned = $this->extraData[$index]['volume_assigned'];

        if ($this->newloadedVolume() > $this->unloadedVolume()) {
            session()->flash('message', 'The volume assigned exceeds the available product at depot');
        } else if ($volumeAssigned > $driver->truck->volume_capacity) {
            session()->flash('message', 'This volume exceeds the truck capacity');
        }
    }

}
