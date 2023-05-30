<?php

namespace Vibraniuum\Pamtechoga\Models;

use Helix\Fabrick\Icon;
use Helix\Lego\Models\Contracts\Searchable;
use Helix\Lego\Models\Model as LegoModel;
use Illuminate\Support\Str;

class OldDriverTrip extends LegoModel implements Searchable
{

    protected $table = 'pamtechoga_old_driver_trips';

    public static function icon(): string
    {
        return Icon::COLLECTION;
    }

    public function getEditRoute(): string
    {
        return route('lego.pamtechoga.old-driver-trips.edit', $this);
    }

    public static function getDisplayKeyName(): string
    {
        return 'number_of_trips';
    }

    public static function searchableIcon(): string
    {
        return static::icon();
    }

    public static function searchableIndexRoute(): string
    {
        return route('lego.pamtechoga.old-driver-trips.index');
    }

    public function scopeGlobalSearch($query, $value)
    {
        return $query->where('number_of_trips', 'LIKE', '%' . $value . '%');
    }

    public function searchableName(): string
    {
        return $this->number_of_trips;
    }

    public function searchableDescription(): string
    {
        return Str::limit($this->number_of_trips ?? '', 60);
    }

    public function searchableRoute(): string
    {
        return route('lego.pamtechoga.old-driver-trips.edit', $this);
    }

    public function driver()
    {
       return $this->belongsTo(Driver::class);
    }
}
