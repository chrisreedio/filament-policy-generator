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
            // Prepare to populate the stub
            $model = $resource::getModel();
            $modelName = class_basename($model);
            $policyName = $modelName . 'Policy';
            $stubDir = __DIR__ . '/stubs/';
            $stubFile = $stubDir . ($modelName === 'User' ? 'User' : 'Generic') . 'Policy.stub';
            $destPath = base_path('app/Policies/');

            $this->info("Generating {$policyName}...");
            // Load the stub's placeholders
            $replacements = [
                'Namespace' => config('policy-generator.namespace', 'App'),
                'UserModel' => config('policy-generator.user_model', 'App\Models\User'),
                'PolicyModel' => $model,
                'Model' => $modelName,
                'modelVariable' => lcfirst($modelName),
            ];

            // Generate the policy
            StubGenerator::from($stubFile, true) // the stub file path
                ->to($destPath, true, true) // the store directory path
                ->as($policyName) // the generatable file name without extension
                ->replace(true) // to replace the file if already exist // TODO - Check if it exists and ask to overwrite
                ->withReplacers($replacements) // the stub replacing params
                ->save(); // save the file
        }

        $this->comment('All policies generated!');

        return self::SUCCESS;
    }
}
