<?php

namespace Vibraniuum\Pamtechoga\Http\Livewire;

use Helix\Lego\Http\Livewire\Models\Index as BaseIndex;
use Vibraniuum\Pamtechoga\Models\Branch;
use Vibraniuum\Pamtechoga\Models\Organization;

class BranchesIndex extends BaseIndex
{

    public function model(): string
    {
        return Branch::class;
    }

    public function columns(): array
    {
        return [
            'address' => 'Address',
            'organization' => 'Organization',
            'updated_at' => 'Last updated',
        ];
    }

    public function mainSearchColumn(): string|false
    {
        return 'address';
    }

    public function render()
    {
        return view('pamtechoga::models.branches.index', [
            'models' => $this->getModels(),
        ])->extends('lego::layouts.lego')->section('content');
    }
}
