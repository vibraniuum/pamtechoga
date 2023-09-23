<?php

namespace Vibraniuum\Pamtechoga\Http\Livewire;

use Helix\Lego\Http\Livewire\Models\Form;
use Vibraniuum\Pamtechoga\Models\Product;
use Vibraniuum\Pamtechoga\Models\Truck;

class TrucksForm extends Form
{
    protected bool $canBeViewed = false;

    public function rules()
    {
        return [
            'model.product_id' => 'required',
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

    public function allProducts()
    {
        return Product::all();
    }
    public function saved()
    {
        $this->model->chart = $this->model->getFirstMedia('Chart')->getUrl();
        $this->model->save();
    }


}
