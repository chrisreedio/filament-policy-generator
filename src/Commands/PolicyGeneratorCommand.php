<?php

namespace ChrisReedIO\PolicyGenerator\Commands;

use Filament\Facades\Filament;
use Illuminate\Console\Command;

class PolicyGeneratorCommand extends Command
{
    public $signature = 'policies:generate';

    public $description = 'Generate policies for all Filament resources';

    public function handle(): int
    {
        $resources = Filament::getResources();
        foreach ($resources as $resource) {
            $model = $resource::getModel();
            dump("Generating policy for {$resource} -> {$model}...");
            // TODO - Generate policy code here
        }

        $this->comment('All done');

        return self::SUCCESS;
    }
}
