<?php

namespace Vibraniuum\Pamtechoga\Models;

use Helix\Fabrick\Icon;
use Helix\Lego\Models\Contracts\Searchable;
use Helix\Lego\Models\Model as LegoModel;
use Helix\Lego\Models\User;
use Illuminate\Support\Str;

class Zone extends LegoModel
{

    protected $table = 'pamtechoga_zones';

    public static function icon(): string
    {
        return Icon::COLLECTION;
    }

    public function getEditRoute(): string
    {
        return route('lego.pamtechoga.zones.edit', $this);
    }

    public function getIndexRoute(): string
    {
        return route('lego.pamtechoga.zones.index', $this);
    }

    public static function getDisplayKeyName(): string
    {
        return 'name';
    }

    public static function searchableIcon(): string
    {
        return static::icon();
    }

    public static function searchableIndexRoute(): string
    {
        return route('lego.pamtechoga.zones.index');
    }

    public function scopeGlobalSearch($query, $value)
    {
        return $query->where('name', 'LIKE', '%' . $value . '%');
    }

    public function searchableName(): string
    {
        return $this->name;
    }

    public function searchableDescription(): string
    {
        return Str::limit($this->name ?? '', 60);
    }

    public function searchableRoute(): string
    {
        return route('lego.pamtechoga.zones.edit', $this);
    }

    public function stations()
    {
        return $this->hasMany(FuelPrice::class);
    }
}
