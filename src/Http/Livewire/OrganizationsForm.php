<?php

namespace Vibraniuum\Pamtechoga\Http\Livewire;

use Helix\Lego\Http\Livewire\Models\Form;
use Helix\Lego\Rules\SlugRule;
use Illuminate\Support\Collection as SupportCollection;
use Illuminate\Support\Facades\Hash;
use Vibraniuum\Pamtechoga\Events\OrganizationLoginInstructions;
use Vibraniuum\Pamtechoga\Models\Branch;
use Vibraniuum\Pamtechoga\Models\Order;
use Vibraniuum\Pamtechoga\Models\Organization;
use Helix\Lego\Models\User;
use Vibraniuum\Pamtechoga\Models\OrganizationUser;
use Vibraniuum\Pamtechoga\Models\Payment;
use Vibraniuum\Pamtechoga\Models\Review;
use Vibraniuum\Pamtechoga\Models\SupportMessage;

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
            'model.contact_person_name' => 'nullable',
            'model.contact_person_phone' => 'nullable',
            'model.contact_person_dob' => 'nullable',
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

    public function saved()
    {
        // check to see if a user account with this email exists already
        // if not, create one with a default password

        $user = User::where('email', $this->model->email)->first();

        if(is_null($user)) {
            $userAccount = User::create([
                'name' => $this->model->name,
                'email' => $this->model->email,
                'password' => Hash::make('11111111')
            ]);

            OrganizationUser::create([
                'organization_id' => $this->model->id,
                'user_id' => $userAccount->id,
            ]);
        }
    }
//
//public function saving()
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

    public function deleting()
    {
        // delete branches
        Branch::where('organization_id', $this->model->id)->delete();

        // delete customer orders
        Order::where('organization_id', $this->model->id)->delete();

        // delete payments
        Payment::where('organization_id', $this->model->id)->delete();

        // delete organization users
        OrganizationUser::where('organization_id', $this->model->id)->delete();

        // delete notifiacations

        // delete reviews
        Review::where('organization_id', $this->model->id)->delete();

        // delete support messages
        SupportMessage::where('organization_id', $this->model->id)->delete();

    }

    public function model(): string
    {
        return Organization::class;
    }

    public function addBranchTextArea()
    {
        $this->branches->push(['id' => $this->branches->count(), 'address' => '']);
    }

    public function sendLoginInstructions()
    {
        OrganizationLoginInstructions::dispatch([
            'email' => $this->model->email,
            'organizationName' => $this->model->name,
        ]);

        $this->confetti();
    }

}
