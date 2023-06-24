<?php

namespace Vibraniuum\Pamtechoga\Http\Livewire;

use Helix\Lego\Http\Livewire\Models\Index as BaseIndex;
use Vibraniuum\Pamtechoga\Models\News;

class NewsIndex extends BaseIndex
{

    public function model(): string
    {
        return News::class;
    }

    public function columns(): array
    {
        return [
            'title' => 'News Title',
            'author' => 'Author',
            'updated_at' => 'Last updated',
        ];
    }

    public function mainSearchColumn(): string|false
    {
        return 'title';
    }

    public function render()
    {
        return view('pamtechoga::models.news.index', [
            'models' => $this->getModels(),
        ])->extends('lego::layouts.lego')->section('content');
    }
}
