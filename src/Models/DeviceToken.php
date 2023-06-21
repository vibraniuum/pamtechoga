<?php

namespace Vibraniuum\Pamtechoga\Models;

use Helix\Fabrick\Icon;
use Helix\Lego\Models\Model as LegoModel;
use Helix\Lego\Models\User;

class DeviceToken extends LegoModel
{

    protected $table = 'pamtechoga_device_tokens';

    public static function icon(): string
    {
        return Icon::COLLECTION;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
