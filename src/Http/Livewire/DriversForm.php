<?php

namespace Vibraniuum\Pamtechoga\Http\Livewire;

use Helix\Lego\Http\Livewire\Models\Form;
use Vibraniuum\Pamtechoga\Events\FuelPriceUpdated;
use Vibraniuum\Pamtechoga\Models\Driver;
use Vibraniuum\Pamtechoga\Models\Truck;

class DriversForm extends Form
{
    protected bool $canBeViewed = false;

    public function rules()
    {
        return [
            'model.name' => 'required',
            'model.nickname' => 'nullable',
            'model.phone' => 'required',
            'model.email' => 'nullable',
            'model.address' => 'required',
            'model.truck_id' => 'nullable',
            'model.photo' => 'nullable',
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
        $trucks = Truck::whereDoesntHave('driver', function ($query) {
            $query->where('id', '!=', null);
        })->orWhere('id', $this->model->truck_id)->get();

        return $trucks->unique('id');
    }

    public function saved()
    {
        $this->model->photo = $this->model->getFirstMedia('Photo')->getUrl();
        $this->model->save();
    }

}
