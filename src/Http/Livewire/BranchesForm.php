<?php

namespace Vibraniuum\Pamtechoga\Http\Livewire;

use Helix\Lego\Http\Livewire\Models\Form;
use Vibraniuum\Pamtechoga\Models\Branch;
use Vibraniuum\Pamtechoga\Models\Organization;

class BranchesForm extends Form
{
    protected bool $canBeViewed = false;

    public $organizations;

    public function rules()
    {
        return [
            'model.organization_id' => 'required',
            'model.address' => 'required',
        ];
    }

    public function mount($branch = null)
    {
        $this->setModel($branch);
        $this->organizations = Organization::all();
    }

    public function view()
    {
        return 'pamtechoga::models.branches.form';
    }

    public function model(): string
    {
        return Branch::class;
    }


}
