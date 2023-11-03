<?php

namespace ChrisReedIO\PolicyGenerator\Commands;

use ChrisReedIO\PolicyGenerator\PolicyGenerator;
use Illuminate\Console\Command;

class PolicyGeneratorCommand extends Command
{
    public $signature = 'policies:generate {--overwrite}';

    public $description = 'Generate policies for all Filament resources';

    public function handle(): int
    {
        $overwrite = $this->option('overwrite');
        PolicyGenerator::generateAll($overwrite);

        $this->comment('All policies generated!');

        return self::SUCCESS;
    }
}
