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

class Driver extends LegoModel implements Searchable, Mediable
{
    use HasMedia;

    protected $table = 'pamtechoga_drivers';

    public static function icon(): string
    {
        return Icon::COLLECTION;
    }

    public function getEditRoute(): string
    {
        return route('lego.pamtechoga.drivers.edit', $this);
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
        return route('lego.pamtechoga.drivers.index');
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
        return route('lego.pamtechoga.drivers.edit', $this);
    }

    public function mediaCollections(): array
    {
        return [
            MediaCollection::name('Photo')->maxFiles(1),
        ];
    }

    public function truck()
    {
       return $this->belongsTo(Truck::class);
    }
}
