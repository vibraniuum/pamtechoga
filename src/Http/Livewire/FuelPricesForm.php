<?php

namespace Vibraniuum\Pamtechoga\Http\Livewire;

use Helix\Lego\Http\Livewire\Models\Form;
use Vibraniuum\Pamtechoga\Events\FuelPriceUpdated;
use Vibraniuum\Pamtechoga\Models\FuelPrice;
use Vibraniuum\Pamtechoga\Models\Zone;

class FuelPricesForm extends Form
{
    protected bool $canBeViewed = false;

    public function rules()
    {
        return [
            'model.zone_id' => 'required',
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

    public function allZones()
    {
        return Zone::all();
    }

    public function saved()
    {
        $this->model->logo = $this->model->getFirstMedia('Logo')->getUrl();
        $this->model->save();

        FuelPriceUpdated::dispatch([
            'company_name' => $this->model->company_name
        ]);
    }
}
