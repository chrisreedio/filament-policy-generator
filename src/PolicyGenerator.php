<?php

namespace ChrisReedIO\PolicyGenerator;

use Filament\Facades\Filament;
use Touhidurabir\StubGenerator\Facades\StubGenerator;

use function Laravel\Prompts\info;
use function Laravel\Prompts\warning;

class PolicyGenerator
{
    public static function generateAll(bool $overwrite = false)
    {
        $resources = Filament::getResources();
        foreach ($resources as $resource) {
            self::generate($resource, $overwrite);
        }
    }

    public static function generate(string $resource, bool $overwrite = false): bool
    {
        if (self::exists($resource) && !$overwrite) {
            warning("Policy for {$resource::getModel()} already exists.");
            return false;
        }

        // Prepare to populate the stub
        $model = $resource::getModel();
        $modelName = class_basename($model);
        $policyName = $modelName . 'Policy';
        $stubDir = __DIR__ . '/../stubs/';
        $stubFile = $stubDir . ($modelName === 'User' ? 'User' : 'Generic') . 'Policy.stub';
        $destPath = base_path('app/Policies/');

        info("Generating {$policyName}...");
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
            ->replace($overwrite) // to replace the file if already exist // TODO - Check if it exists and ask to overwrite
            ->withReplacers($replacements) // the stub replacing params
            ->save(); // save the file

        return true;
    }

    public static function exists(string $resource): bool
    {
        $model = $resource::getModel();
        $modelName = class_basename($model);
        $policyName = $modelName . 'Policy';
        $destPath = base_path('app/Policies/');
        return file_exists($destPath . $policyName . '.php');
    }
}
