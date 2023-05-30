<?php

namespace Vibraniuum\Pamtechoga\Models;

use Helix\Fabrick\Icon;
use Helix\Lego\Models\Contracts\Searchable;
use Helix\Lego\Models\Model as LegoModel;
use Illuminate\Support\Str;

class DepotOrder extends LegoModel implements Searchable
{

    protected $table = 'pamtechoga_depot_orders';

    public static function icon(): string
    {
        return Icon::COLLECTION;
    }

    public function getEditRoute(): string
    {
        return route('lego.pamtechoga.depot-orders.edit', $this);
    }

    public static function getDisplayKeyName(): string
    {
        return 'volume';
    }

    public static function searchableIcon(): string
    {
        return static::icon();
    }

    public static function searchableIndexRoute(): string
    {
        return route('lego.pamtechoga.depot-orders.index');
    }

    public function scopeGlobalSearch($query, $value)
    {
        return $query->where('volume', 'LIKE', '%' . $value . '%');
    }

    public function searchableName(): string
    {
        return $this->volume;
    }

    public function searchableDescription(): string
    {
        return Str::limit($this->volume ?? '', 60);
    }

    public function searchableRoute(): string
    {
        return route('lego.pamtechoga.depot-orders.edit', $this);
    }

    public function product()
    {
       return $this->belongsTo(Product::class);
    }

    public function depot()
    {
       return $this->belongsTo(Depot::class);
    }

    public function depotPickup()
    {
        return $this->hasMany(DepotPickup::class, 'depot_order_id', 'id');
    }
}
