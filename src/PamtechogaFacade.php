<?php

namespace Vibraniuum\Pamtechoga;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Vibraniuum\Pamtechoga\Pamtechoga
 */
class PamtechogaFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'pamtechoga';
    }
}
