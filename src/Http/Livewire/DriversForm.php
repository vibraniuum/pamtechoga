<?php

namespace Vibraniuum\Pamtechoga\Http\Livewire;

use Helix\Lego\Http\Livewire\Models\Form;
use Vibraniuum\Pamtechoga\Models\Driver;
use Vibraniuum\Pamtechoga\Models\Truck;

class DriversForm extends Form
{
    protected bool $canBeViewed = false;

    public function rules()
    {
        return [
            'model.name' => 'required',
            'model.phone' => 'required',
            'model.email' => 'nullable',
            'model.address' => 'required',
            'model.truck_id' => 'nullable',
        ];
    }

    public function mount($driver = null)
    {
        $this->setModel($driver);
    }

    public function view()
    {
        return 'pamtechoga::models.drivers.form';
    }

    public function model(): string
    {
        return Driver::class;
    }

    public function allTrucks()
    {
        return Truck::all();
    }


}
