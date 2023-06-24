<?php

namespace Vibraniuum\Pamtechoga\Models;

use Helix\Fabrick\Icon;
use Helix\Lego\Media\HasMedia;
use Helix\Lego\Media\Mediable;
use Helix\Lego\Media\MediaCollection;
use Helix\Lego\Models\Contracts\Searchable;
use Helix\Lego\Models\Model as LegoModel;
use Illuminate\Support\Str;

class News extends LegoModel implements Mediable
{
    use HasMedia;

    protected $table = 'pamtechoga_news';

    public static function icon(): string
    {
        return Icon::COLLECTION;
    }

    public function mediaCollections(): array
    {
        return [
            MediaCollection::name('Image')->maxFiles(1),
        ];
    }

    public function getEditRoute(): string
    {
        return route('lego.pamtechoga.news.edit', $this);
    }

    public static function getDisplayKeyName(): string
    {
        return 'title';
    }
}
