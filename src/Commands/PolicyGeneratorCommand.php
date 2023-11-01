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

            $modelName = class_basename($model);
            $policyName = $modelName . 'Policy';
            $stubFile = __DIR__ . '/stubs/GenericPolicy.stub';
            $destPath = base_path('app/Policies/');
            dump("[$modelName] - Generating {$policyName} from {$stubFile} to {$destPath}.");
            StubGenerator::from($stubFile, true) // the stub file path
                ->to($destPath, true, true) // the store directory path
                ->as($policyName) // the generatable file name without extension
                // ->ext('php') // the file extension(optional, by default to php)
                // ->noExt() // to remove the extension from the file name for the generated file like .env
                ->withReplacers([
                    '{{Namespace}}' => config('policy-generator.namespace', 'App'),
                    '{{Model}}' => $modelName,
                    '{{modelVariable}}' => lcfirst($modelName),
                ]) // the stub replacing params
                ->save(); // save the file
        }

        $this->comment('All done');

        return self::SUCCESS;
    }
}
