<?php

namespace Vibraniuum\Pamtechoga\Models;

use Astrogoat\Storefront\Models\Product;
use Helix\Fabrick\Icon;
use Helix\Lego\Media\HasMedia;
use Helix\Lego\Media\Mediable;
use Helix\Lego\Media\MediaCollection;
use Helix\Lego\Models\Contracts\Searchable;
use Helix\Lego\Models\Model as LegoModel;
use Illuminate\Support\Str;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Organization extends LegoModel implements Mediable, Searchable
{
    use HasSlug;
    use HasMedia;

    protected $table = 'pamtechoga_organizations';

    public static function icon(): string
    {
        return Icon::COLLECTION;
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom($this->getDisplayKeyName())
            ->saveSlugsTo('slug')
            ->doNotGenerateSlugsOnUpdate();
    }

    public function getEditRoute(): string
    {
        return route('lego.pamtechoga.organizations.edit', $this);
    }

    public static function getDisplayKeyName(): string
    {
        return 'name';
    }

    public function getMedia(): array
    {
        return $this->featured_image ?: [];
    }

    public function mediaCollections(): array
    {
        return [
            MediaCollection::name('Photo')->maxFiles(1),
        ];
    }

    public static function searchableIcon(): string
    {
        return static::icon();
    }

    public static function searchableIndexRoute(): string
    {
        return route('lego.pamtechoga.organizations.index');
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
        return Str::limit($this->address ?? '', 60);
    }

    public function searchableRoute(): string
    {
        return route('lego.pamtechoga.organizations.edit', $this);
    }

    public function branches()
    {
        return $this->hasMany(Branch::class, 'organization_id', 'id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'organization_id', 'id');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class, 'organization_id', 'id');
    }
}
