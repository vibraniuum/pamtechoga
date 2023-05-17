<?php

namespace Vibraniuum\Pamtechoga\Actions;

use Helix\Lego\Apps\Actions\Action;

class PamtechogaAction extends Action
{
    public static function actionName(): string
    {
        return 'Pamtechoga action name';
    }

    public static function run(): mixed
    {
        return redirect()->back();
    }
}
