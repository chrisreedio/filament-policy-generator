<?php

namespace ChrisReedIO\PolicyGenerator\Commands;

use ChrisReedIO\PolicyGenerator\PolicyGenerator;
use Filament\Facades\Filament;
use Illuminate\Console\Command;
use Touhidurabir\StubGenerator\Facades\StubGenerator;

class PolicyGeneratorCommand extends Command
{
    public $signature = 'policies:generate';

    public $description = 'Generate policies for all Filament resources';

    public function handle(): int
    {
        PolicyGenerator::generateAll(true);

        $this->comment('All policies generated!');

        return self::SUCCESS;
    }
}
