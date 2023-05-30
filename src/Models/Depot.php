<?php

namespace Vibraniuum\Pamtechoga\Models;

use Helix\Fabrick\Icon;
use Helix\Lego\Models\Contracts\Searchable;
use Helix\Lego\Models\Model as LegoModel;
use Illuminate\Support\Str;
use Spatie\Sluggable\SlugOptions;

class Depot extends LegoModel implements Searchable
{
    protected $table = 'pamtechoga_depots';

    public static function icon(): string
    {
        return Icon::COLLECTION;
    }

    public function getEditRoute(): string
    {
        return route('lego.pamtechoga.depots.edit', $this);
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
        return route('lego.pamtechoga.depots.index');
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
        return route('lego.pamtechoga.depots.edit', $this);
    }
}
