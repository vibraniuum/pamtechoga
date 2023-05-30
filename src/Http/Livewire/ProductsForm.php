<?php

namespace Vibraniuum\Pamtechoga\Http\Livewire;

use Helix\Lego\Http\Livewire\Models\Form;
use Vibraniuum\Pamtechoga\Models\Product;

class ProductsForm extends Form
{
    protected bool $canBeViewed = false;

    public function rules()
    {
        return [
            'model.type' => 'required',
            'model.market_price' => 'required',
            'model.instock' => 'required',
        ];
    }

    public function mount($product = null)
    {
        $this->setModel($product);
        if (! $this->model->exists) {
            $this->model->instock = true;
        }
    }

    public function view()
    {
        return 'pamtechoga::models.products.form';
    }

    public function model(): string
    {
        return Product::class;
    }


}
