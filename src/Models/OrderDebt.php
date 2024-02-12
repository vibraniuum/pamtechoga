<?php

namespace Vibraniuum\Pamtechoga\Models;

use Helix\Fabrick\Icon;
use Helix\Lego\Models\Contracts\Searchable;
use Helix\Lego\Models\Model as LegoModel;
use Helix\Lego\Models\User;
use Illuminate\Support\Str;

class OrderDebt extends LegoModel
{

    protected $table = 'pamtechoga_order_debts';

    public static function icon(): string
    {
        return Icon::COLLECTION;
    }

    public static function getDisplayKeyName(): string
    {
        return 'balance';
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
