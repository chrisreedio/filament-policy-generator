<?php

namespace ChrisReedIO\PolicyGenerator\Commands;

use Illuminate\Console\Command;

class PolicyGeneratorCommand extends Command
{
    public $signature = 'filament-policy-generator';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
