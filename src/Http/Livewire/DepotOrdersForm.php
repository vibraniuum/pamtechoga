<?php

namespace Vibraniuum\Pamtechoga\Http\Livewire;

use Helix\Lego\Http\Livewire\Models\Form;
use Vibraniuum\Pamtechoga\Models\Branch;
use Vibraniuum\Pamtechoga\Models\Depot;
use Vibraniuum\Pamtechoga\Models\DepotOrder;
use Vibraniuum\Pamtechoga\Models\DepotPickup;
use Vibraniuum\Pamtechoga\Models\Driver;
use Vibraniuum\Pamtechoga\Models\Order;
use Vibraniuum\Pamtechoga\Models\Organization;
use Vibraniuum\Pamtechoga\Models\Product;

class DepotOrdersForm extends Form
{
    protected bool $canBeViewed = false;

    public $formattedVolume = 0;
    public $formattedUnitPrice = 0;

    public function rules()
    {
        return [
            'model.product_id' => 'required',
            'model.status' => 'nullable',
            'model.depot_id' => 'required',
            'model.volume' => 'required',
            'model.unit_price' => 'required',
            'model.trucking_expense' => 'required',
            'model.order_date' => 'required',
            'formattedVolume' => 'required',
            'formattedUnitPrice' => 'required',
        ];
    }

    public function mount($depotOrder = null)
    {
        $this->setModel($depotOrder);

        if (is_null($depotOrder)) {
            $this->model->status = 'UNLOADED';
        } else {
            $this->formattedVolume = number_format($this->model->volume);
            $this->formattedUnitPrice = number_format($this->model->unit_price);
        }
    }

    public function updatedFormattedVolume()
    {
        $volume = (float) str_replace(',', '', $this->formattedVolume);
        $this->model->volume = $volume;
    }

    public function updatedFormattedUnitPrice()
    {
        $unitPrice = (float) str_replace(',', '', $this->formattedUnitPrice);
        $this->model->unit_price = $unitPrice;
    }

    public function unloadedVolume()
    {
        $loadedVolume = $this->loadedVolume();
        $unloadedVolume = $this->model->volume - $loadedVolume;

        return $unloadedVolume;
    }

    public function loadedVolume()
    {
        $loadedVolume = DepotPickup::where('depot_order_id', $this->model->id)->where('status', 'LOADED')->orWhere('status', 'COMPLETED')->sum('volume_assigned');

        return $loadedVolume;
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
