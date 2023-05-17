<?php

namespace VendorName\Skeleton\Settings;

use Helix\Lego\Settings\AppSettings;
use Illuminate\Validation\Rule;
use VendorName\Skeleton\Actions\SkeletonAction;

class SkeletonSettings extends AppSettings
{
    // public string $url;

    public function rules(): array
    {
        return [
            // 'url' => Rule::requiredIf($this->enabled === true),
        ];
    }

    // protected static array $actions = [
    //     SkeletonAction::class,
    // ];

    // public static function encrypted(): array
    // {
    //     return ['access_token'];
    // }

    public function description(): string
    {
        return 'Interact with Skeleton.';
    }

    public static function group(): string
    {
        return 'skeleton';
    }
}
