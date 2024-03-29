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
use Vibraniuum\Pamtechoga\Models\Order;
use Vibraniuum\Pamtechoga\Models\Review;

class Driver extends LegoModel implements Searchable, Mediable
{
    use HasMedia;

    protected $table = 'pamtechoga_drivers';

    protected $appends = ['trips_count', 'ratings'];

    public function getTripsCountAttribute()
    {
        $oldTrips = OldDriverTrip::where('driver_id', $this->id)->first();
        if(!is_null($oldTrips)) {
            $numberOfOldTrips = $oldTrips->number_of_trips;
        } else {
            $numberOfOldTrips = 0;
        }

        return Order::where('driver_id', $this->id)->count() + $numberOfOldTrips;
    }

    public function getRatingsAttribute()
    {
        return Review::where('driver_id', $this->id)->avg('rating');
    }

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

    public function depotPickups()
    {
         return $this->hasMany(DepotPickup::class);
    }
}
