<?php

namespace Vibraniuum\Pamtechoga\Http\Livewire;

use Helix\Lego\Http\Livewire\Models\Form;
use Helix\Lego\Rules\SlugRule;
use Illuminate\Support\Collection as SupportCollection;
use Vibraniuum\Pamtechoga\Models\Branch;
use Vibraniuum\Pamtechoga\Models\Organization;

class OrganizationsForm extends Form
{

    protected bool $canBeViewed = false;

    public SupportCollection $selectedBranches;
    public array $selectedBranchesIds = [];

    public SupportCollection $branches;

    protected $listeners = [
        'updateBranchesOrder',
    ];

    public function rules()
    {
        return [
            'model.name' => 'required',
            'model.slug' => [new SlugRule($this->model)],
            'model.phone' => 'required',
            'model.email' => 'required',
        ];
    }

    public function mount($organization = null)
    {
        $this->setModel($organization);

        if (! $this->model->exists) {
            $this->model->indexable = true;
            $this->model->layout = array_key_first(siteLayouts());
        }

        $this->branches = collect($this->model->branches ?? []);
//
//        $this->selectedProducts = $this->model->products;
//        $this->selectedProductsIds = $this->selectedProducts->map(fn ($product) => $product->id)->toArray();
    }

//    public function saving()
//    {
//        $this->model->products()->sync(
//            $this->selectedProducts->mapWithKeys(fn ($product, $index) => [$product->id => ['order' => $index]])
//        );
//    }


//    protected function getProductsForCollectionCombobox(): array
//    {
//        return Product::all()->map(fn (Product $product) => [
//            'key' => $product->id,
//            'value' => $product->title,
//            'selected' => in_array($product->id, $this->selectedProductsIds),
//        ])->toArray();
//    }

//    public function selectProduct($productId)
//    {
//        $this->selectedProductsIds[] = $productId;
//        $this->selectedProducts->push(Product::find($productId));
//        $this->markAsDirty();
//    }

//    public function unselectProduct($productId)
//    {
//        $this->selectedProductsIds = array_filter($this->selectedProductsIds, fn ($id) => $id !== $productId);
//        $this->selectedProducts = $this->selectedProducts->reject(fn ($product) => $product->id === $productId);
//        $this->emitTo('fab.forms.combobox', 'updateItems', $this->getProductsForCollectionCombobox());
//        $this->markAsDirty();
//    }

//    public function updateProductsOrder($order)
//    {
//        $this->selectedProducts = $this->selectedProducts
//            ->sort(function ($a, $b) use ($order) {
//                $positionA = array_search($a->id, $order);
//                $positionB = array_search($b->id, $order);
//
//                return $positionA - $positionB;
//            })
//            ->values();
//
//        $this->markAsDirty();
//    }

    public function view()
    {
        return 'pamtechoga::models.organizations.form';
    }

    public function model(): string
    {
        return Organization::class;
    }

    public function addBranchTextArea()
    {
        $this->branches->push(['id' => $this->branches->count(), 'address' => '']);
    }

}
