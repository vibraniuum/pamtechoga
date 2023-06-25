<?php

namespace Vibraniuum\Pamtechoga\Http\Livewire;

use Helix\Lego\Http\Livewire\Models\Form;
use Vibraniuum\Pamtechoga\Events\AnnouncementBlasted;
use Vibraniuum\Pamtechoga\Models\Announcement;

class AnnouncementsForm extends Form
{
    protected bool $canBeViewed = false;

    public function rules()
    {
        return [
            'model.title' => 'required',
            'model.message' => 'nullable',
        ];
    }

    public function mount($announcement = null)
    {
        $this->setModel($announcement);
    }

    public function view()
    {
        return 'pamtechoga::models.announcements.form';
    }

    public function model(): string
    {
        return Announcement::class;
    }

    public function sendAnnouncement()
    {
        AnnouncementBlasted::dispatch([
            'title' => $this->model->title,
            'message' => $this->model->message,
        ]);

        $this->confetti();
    }
}
