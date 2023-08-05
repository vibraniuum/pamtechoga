<?php

namespace Vibraniuum\Pamtechoga\Http\Livewire;

use Carbon\Carbon;
use Helix\Lego\Http\Livewire\Models\Form;
use Vibraniuum\Pamtechoga\Models\DepotOrder;

class DateFilter extends Form
{
    public $startDate;
    public $endDate;


    public function mount($depotOrder = null)
    {
        $this->setModel($depotOrder);
    }

    public function resetDates()
    {
        $this->startDate = Carbon::createFromFormat('Y-m-d', '2019-01-01')->startOfDay();
        $this->endDate = Carbon::now();
    }

    public function applyFilter()
    {
        $this->markAsClean();
        $this->emitUp('filterApplied', [
            'startDate' => $this->startDate,
            'endDate' => $this->endDate,
        ]);
    }

    public function view()
    {
        return 'pamtechoga::models.datefilter.form';
    }

    // random model name to get access to a display key name
    public function model(): string
    {
        return DepotOrder::class;
    }
}
