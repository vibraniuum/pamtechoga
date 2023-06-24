<?php

namespace Vibraniuum\Pamtechoga\Http\Livewire;

use Helix\Lego\Http\Livewire\Models\Form;
use Vibraniuum\Pamtechoga\Events\FuelPriceUpdated;
use Vibraniuum\Pamtechoga\Models\FuelPrice;
use Vibraniuum\Pamtechoga\Models\News;

class NewsForm extends Form
{
    protected bool $canBeViewed = false;

    public function rules()
    {
        return [
            'model.title' => 'required',
            'model.author' => 'nullable',
            'model.content' => 'required',
            'model.image' => 'nullable',
        ];
    }

    public function mount($news = null)
    {
        $this->setModel($news);
    }

    public function view()
    {
        return 'pamtechoga::models.news.form';
    }

    public function model(): string
    {
        return News::class;
    }

    public function saved()
    {
        $this->model->image = $this->model->getFirstMedia('Image')->getUrl();
        $this->model->save();

//        FuelPriceUpdated::dispatch([
//            'company_name' => $this->model->company_name
//        ]);
    }
}
