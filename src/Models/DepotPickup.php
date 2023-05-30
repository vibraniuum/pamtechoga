<?php

namespace Vibraniuum\Pamtechoga\Models;

use Helix\Fabrick\Icon;
use Helix\Lego\Models\Contracts\Searchable;
use Helix\Lego\Models\Model as LegoModel;
use Illuminate\Support\Str;

class DepotPickup extends LegoModel implements Searchable
{

    protected $table = 'pamtechoga_depot_pickups';

    public static function icon(): string
    {
        return Icon::COLLECTION;
    }

    public function getEditRoute(): string
    {
        return route('lego.pamtechoga.depot-pickups.edit', $this);
    }

    public static function getDisplayKeyName(): string
    {
        return 'assigned_volume';
    }

    public static function searchableIcon(): string
    {
        return static::icon();
    }

    public static function searchableIndexRoute(): string
    {
        return route('lego.pamtechoga.depot-pickups.index');
    }

    public function scopeGlobalSearch($query, $value)
    {
        return $query->where('assigned_volume', 'LIKE', '%' . $value . '%');
    }

    public function searchableName(): string
    {
        return $this->assigned_volume;
    }

    public function searchableDescription(): string
    {
        return Str::limit($this->assigned_volume ?? '', 60);
    }

    public function searchableRoute(): string
    {
        return route('lego.pamtechoga.depot-pickups.edit', $this);
    }

    public function depotOrder()
    {
       return $this->belongsTo(DepotOrder::class);
    }

    public function driver()
    {
       return $this->belongsTo(Driver::class);
    }
}
