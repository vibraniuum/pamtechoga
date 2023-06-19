<?php

namespace Vibraniuum\Pamtechoga\Http\Livewire;

use Helix\Lego\Http\Livewire\Models\Form;
use Vibraniuum\Pamtechoga\Models\FuelPrice;

class FuelPricesForm extends Form
{
    protected bool $canBeViewed = false;

    public function rules()
    {
        return [
            'model.company_name' => 'required',
            'model.petrol' => 'required',
            'model.diesel' => 'nullable',
            'model.premium' => 'nullable',
            'model.logo' => 'nullable',
        ];
    }

    public function mount($fuelPrice = null)
    {
        $this->setModel($fuelPrice);
    }

    public function view()
    {
        return 'pamtechoga::models.fuel-prices.form';
    }

    public function model(): string
    {
        return FuelPrice::class;
    }

    public function saved()
    {
        $this->model->logo = $this->model->getFirstMedia('Logo')->getUrl();
        $this->model->save();
    }
}
