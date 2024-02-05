<?php

namespace Vibraniuum\Pamtechoga\Http\Livewire;

use Helix\Lego\Http\Livewire\Models\Form;
use Vibraniuum\Pamtechoga\Models\Branch;
use Vibraniuum\Pamtechoga\Models\Depot;
use Vibraniuum\Pamtechoga\Models\DepotOrder;
use Vibraniuum\Pamtechoga\Models\Driver;
use Vibraniuum\Pamtechoga\Models\Order;
use Vibraniuum\Pamtechoga\Models\Organization;
use Vibraniuum\Pamtechoga\Models\Product;

class DepotOrdersForm extends Form
{
    protected bool $canBeViewed = false;

    public function rules()
    {
        return [
            'model.product_id' => 'required',
            'model.status' => 'required',
            'model.depot_id' => 'required',
            'model.volume' => 'required',
            'model.unit_price' => 'required',
            'model.trucking_expense' => 'required',
            'model.order_date' => 'required',
        ];
    }

    public function mount($depotOrder = null)
    {
        $this->setModel($depotOrder);
    }

    public function view()
    {
        return 'pamtechoga::models.depot-orders.form';
    }

    public function model(): string
    {
        return DepotOrder::class;
    }

    public function allProducts()
    {
        return Product::all();
    }

    public function allDepots()
    {
        return Depot::all();
    }

}
