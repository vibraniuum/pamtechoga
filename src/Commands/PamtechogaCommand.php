<?php

namespace Vibraniuum\Pamtechoga\Commands;

use Illuminate\Console\Command;

class PamtechogaCommand extends Command
{
    public $signature = 'pamtechoga';

    public $description = 'My command';

    public function handle()
    {
        $this->comment('All done');
    }
}
