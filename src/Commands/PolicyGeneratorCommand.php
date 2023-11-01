<?php

namespace ChrisReedIO\PolicyGenerator\Commands;

use Filament\Facades\Filament;
use Illuminate\Console\Command;
use Touhidurabir\StubGenerator\Facades\StubGenerator;

class PolicyGeneratorCommand extends Command
{
    public $signature = 'policies:generate';

    public $description = 'Generate policies for all Filament resources';

    public function handle(): int
    {
        $resources = Filament::getResources();
        foreach ($resources as $resource) {
            $model = $resource::getModel();
            dump("{$resource} -> {$model}...");

            $policyName = class_basename($model) . 'Policy';
            StubGenerator::from('stubs/Policy.stub.php') // the stub file path
                ->to(base_path('app/Policies/')) // the store directory path
                ->as($policyName) // the generatable file name without extension 
                // ->ext('php') // the file extension(optional, by default to php)
                // ->noExt() // to remove the extension from the file name for the generated file like .env
                ->withReplacers([]) // the stub replacing params
                ->save(); // save the file
        }

        $this->comment('All done');

        return self::SUCCESS;
    }
}
