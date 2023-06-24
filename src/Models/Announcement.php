<?php

namespace Vibraniuum\Pamtechoga\Models;

use Helix\Fabrick\Icon;
use Helix\Lego\Models\Contracts\Searchable;
use Helix\Lego\Models\Model as LegoModel;
use Illuminate\Support\Str;

class Announcement extends LegoModel
{

    protected $table = 'pamtechoga_announcements';

    public static function icon(): string
    {
        return Icon::COLLECTION;
    }

    public function getEditRoute(): string
    {
        return route('lego.pamtechoga.announcements.edit', $this);
    }

    public static function getDisplayKeyName(): string
    {
        return 'title';
    }
}
