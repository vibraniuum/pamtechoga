<?php

namespace Vibraniuum\Pamtechoga\Models;

use Helix\Fabrick\Icon;
use Helix\Lego\Media\HasMedia;
use Helix\Lego\Models\Contracts\Searchable;
use Helix\Lego\Models\Model as LegoModel;
use Illuminate\Support\Str;

class PaymentDetail extends LegoModel implements Searchable
{
    use HasMedia;

    protected $table = 'pamtechoga_payment_details';

    public static function icon(): string
    {
        return Icon::COLLECTION;
    }

    public function getEditRoute(): string
    {
        return route('lego.pamtechoga.payment-details.edit', $this);
    }

    public static function getDisplayKeyName(): string
    {
        return 'account_name';
    }

    public static function searchableIcon(): string
    {
        return static::icon();
    }

    public static function searchableIndexRoute(): string
    {
        return route('lego.pamtechoga.payment-details.index');
    }

    public function scopeGlobalSearch($query, $value)
    {
        return $query->where('account_name', 'LIKE', '%' . $value . '%');
    }

    public function searchableName(): string
    {
        return $this->account_name;
    }

    public function searchableDescription(): string
    {
        return Str::limit($this->address ?? '', 60);
    }

    public function searchableRoute(): string
    {
        return route('lego.pamtechoga.payment-details.edit', $this);
    }

    public function organization()
    {
       return $this->belongsTo(Organization::class);
    }
}
