<?php

namespace Vibraniuum\Pamtechoga\Http\Livewire;

use Helix\Lego\Http\Livewire\Models\Form;
use Vibraniuum\Pamtechoga\Models\Truck;

class TrucksForm extends Form
{
    protected bool $canBeViewed = false;

    public function rules()
    {
        return [
            'model.plate_number' => 'required',
            'model.volume_capacity' => 'required',
            'model.available_volume' => 'required',
        ];
    }

    public function mount($truck = null)
    {
        $this->setModel($truck);
    }

    public function view()
    {
        return 'pamtechoga::models.trucks.form';
    }

    public function model(): string
    {
        return Truck::class;
    }


}
