<?php

namespace Vibraniuum\Pamtechoga\Models;

use Helix\Fabrick\Icon;
use Helix\Lego\Media\HasMedia;
use Helix\Lego\Media\Mediable;
use Helix\Lego\Media\MediaCollection;
use Helix\Lego\Models\Contracts\Searchable;
use Helix\Lego\Models\Model as LegoModel;
use Illuminate\Support\Str;

class FuelPrice extends LegoModel implements Mediable
{
    use HasMedia;

    protected $table = 'pamtechoga_fuel_prices';

    public static function icon(): string
    {
        return Icon::COLLECTION;
    }

    public function mediaCollections(): array
    {
        return [
            MediaCollection::name('Logo')->maxFiles(1),
        ];
    }

    public function getEditRoute(): string
    {
        return route('lego.pamtechoga.fuel-prices.edit', $this);
    }

    public static function getDisplayKeyName(): string
    {
        return 'company_name';
    }

    public function zone()
    {
        return $this->belongsTo(Zone::class);
    }
}
