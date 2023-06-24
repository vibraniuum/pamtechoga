<?php

namespace Vibraniuum\Pamtechoga\Http\Livewire;

use Helix\Lego\Http\Livewire\Models\Index as BaseIndex;
use Vibraniuum\Pamtechoga\Models\Announcement;

class AnnouncementsIndex extends BaseIndex
{

    public function model(): string
    {
        return Announcement::class;
    }

    public function columns(): array
    {
        return [
            'title' => 'News Title',
            'updated_at' => 'Last updated',
        ];
    }

    public function mainSearchColumn(): string|false
    {
        return 'title';
    }

    public function render()
    {
        return view('pamtechoga::models.announcements.index', [
            'models' => $this->getModels(),
        ])->extends('lego::layouts.lego')->section('content');
    }
}
