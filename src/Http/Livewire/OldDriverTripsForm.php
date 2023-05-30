<?php

namespace Vibraniuum\Pamtechoga\Http\Livewire;

use Helix\Lego\Http\Livewire\Models\Form;
use Vibraniuum\Pamtechoga\Models\Driver;
use Vibraniuum\Pamtechoga\Models\OldDriverTrip;

class OldDriverTripsForm extends Form
{
    protected bool $canBeViewed = false;

    public function rules()
    {
        return [
            'model.driver_id' => 'required',
            'model.number_of_trips' => 'required',
        ];
    }

    public function mount($oldDriverTrip = null)
    {
        $this->setModel($oldDriverTrip);
    }

    public function view()
    {
        return 'pamtechoga::models.old-driver-trips.form';
    }

    public function model(): string
    {
        return OldDriverTrip::class;
    }

    public function allDrivers()
    {
        return Driver::all();
    }

}
