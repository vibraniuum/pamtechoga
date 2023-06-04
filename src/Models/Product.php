<?php

namespace Vibraniuum\Pamtechoga\Models;

use Helix\Fabrick\Icon;
use Helix\Lego\Models\Contracts\Searchable;
use Helix\Lego\Models\Model as LegoModel;
use Illuminate\Support\Str;
use Spatie\Sluggable\SlugOptions;

class Product extends LegoModel implements Searchable
{

    protected $table = 'pamtechoga_products';

    protected $casts = [
        "instock" => "boolean"
    ];

    public static function icon(): string
    {
        return Icon::COLLECTION;
    }

    public function getEditRoute(): string
    {
        return route('lego.pamtechoga.products.edit', $this);
    }

    public static function getDisplayKeyName(): string
    {
        return 'type';
    }

    public static function searchableIcon(): string
    {
        return static::icon();
    }

    public static function searchableIndexRoute(): string
    {
        return route('lego.pamtechoga.products.index');
    }

    public function scopeGlobalSearch($query, $value)
    {
        return $query->where('type', 'LIKE', '%' . $value . '%');
    }

    public function searchableName(): string
    {
        return $this->type;
    }

    public function searchableDescription(): string
    {
        return Str::limit($this->type ?? '', 60);
    }

    public function searchableRoute(): string
    {
        return route('lego.pamtechoga.products.edit', $this);
    }
}
