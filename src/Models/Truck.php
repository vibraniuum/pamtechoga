<?php

namespace Vibraniuum\Pamtechoga\Models;

use Helix\Fabrick\Icon;
use Helix\Lego\Media\HasMedia;
use Helix\Lego\Media\Mediable;
use Helix\Lego\Media\MediaCollection;
use Helix\Lego\Models\Contracts\Searchable;
use Helix\Lego\Models\Model as LegoModel;
use Illuminate\Support\Str;
use Spatie\Sluggable\SlugOptions;

class Truck extends LegoModel implements Searchable, Mediable
{
    use HasMedia;

    protected $table = 'pamtechoga_trucks';

    public static function icon(): string
    {
        return Icon::COLLECTION;
    }

    public function getEditRoute(): string
    {
        return route('lego.pamtechoga.trucks.edit', $this);
    }

    public static function getDisplayKeyName(): string
    {
        return 'truck_number';
    }

    public static function searchableIcon(): string
    {
        return static::icon();
    }

    public static function searchableIndexRoute(): string
    {
        return route('lego.pamtechoga.trucks.index');
    }

    public function scopeGlobalSearch($query, $value)
    {
        return $query->where('truck_number', 'LIKE', '%' . $value . '%');
    }

    public function searchableName(): string
    {
        return $this->truck_number;
    }

    public function searchableDescription(): string
    {
        return Str::limit($this->truck_number ?? '', 60);
    }

    public function searchableRoute(): string
    {
        return route('lego.pamtechoga.trucks.edit', $this);
    }

    public function mediaCollections(): array
    {
        return [
            MediaCollection::name('Chart')->maxFiles(1),
        ];
    }

    public function driver()
    {
       return $this->hasOne(Driver::class);
    }
}
