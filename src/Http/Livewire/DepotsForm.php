<?php

namespace Vibraniuum\Pamtechoga\Http\Livewire;

use Helix\Lego\Http\Livewire\Models\Form;
use Vibraniuum\Pamtechoga\Models\Depot;

class DepotsForm extends Form
{
    protected bool $canBeViewed = false;

    public function rules()
    {
        return [
            'model.name' => 'required',
            'model.address' => 'required',
        ];
    }

    public function mount($depot = null)
    {
        $this->setModel($depot);
    }

    public function view()
    {
        return 'pamtechoga::models.depots.form';
    }

    public function model(): string
    {
        return Depot::class;
    }


}
