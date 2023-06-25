<?php

namespace Vibraniuum\Pamtechoga\Models;

use Helix\Fabrick\Icon;
use Helix\Lego\Models\Contracts\Searchable;
use Helix\Lego\Models\Model as LegoModel;
use Helix\Lego\Models\User;
use Illuminate\Support\Str;

class SupportMessage extends LegoModel
{

    protected $table = 'pamtechoga_support_messages';

    public static function icon(): string
    {
        return Icon::COLLECTION;
    }

    public static function getDisplayKeyName(): string
    {
        return 'message';
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
