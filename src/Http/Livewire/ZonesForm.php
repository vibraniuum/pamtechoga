<?php

namespace Vibraniuum\Pamtechoga\Http\Livewire;

use Helix\Lego\Http\Livewire\Models\Form;
use Vibraniuum\Pamtechoga\Models\Zone;

class ZonesForm extends Form
{
    protected bool $canBeViewed = false;

    public function rules()
    {
        return [
            'model.name' => 'required',
        ];
    }

    public function mount($zone = null)
    {
        $this->setModel($zone);
    }

    public function view()
    {
        return 'pamtechoga::models.zones.form';
    }

    public function model(): string
    {
        return Zone::class;
    }
}
