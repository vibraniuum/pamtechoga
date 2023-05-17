<?php

namespace Vibraniuum\Pamtechoga\Settings;

use Helix\Lego\Settings\AppSettings;
use Illuminate\Validation\Rule;
use Vibraniuum\Pamtechoga\Actions\PamtechogaAction;

class PamtechogaSettings extends AppSettings
{
    // public string $url;

    public function rules(): array
    {
        return [
            // 'url' => Rule::requiredIf($this->enabled === true),
        ];
    }

    // protected static array $actions = [
    //     PamtechogaAction::class,
    // ];

    // public static function encrypted(): array
    // {
    //     return ['access_token'];
    // }

    public function description(): string
    {
        return 'Interact with Pamtechoga.';
    }

    public static function group(): string
    {
        return 'pamtechoga';
    }
}
