<?php

namespace ChrisReedIO\PolicyGenerator;

use Filament\Facades\Filament;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;

use function app;
use function config;
use function Laravel\Prompts\info;

class PolicyGenerator
{
    public static function generateAll(bool $overwrite = false): void
    {
        $resources = Filament::getResources();

        foreach ($resources as $resource) {
            self::generate($resource, $overwrite);
        }
    }

    public static function generate(string $resource, bool $overwrite = false): bool
    {
        if (self::exists($resource) && ! $overwrite) {
            // warning("Policy for {$resource::getModel()} already exists.");
            return false;
        }

        $model = $resource::getModel();
        $modelName = class_basename($model);
        $policyName = $modelName . 'Policy';
        $stubDir = __DIR__ . '/../stubs/';
        $stubFile = $stubDir . ($modelName === 'User' ? 'User' : 'Generic') . 'Policy.stub';
        $destPath = base_path('app/Policies/');

        info("Generating {$policyName}...");

        $replacements = [
            'Namespace' => config('policy-generator.namespace', 'App'),
            'UserModel' => config('policy-generator.user_model', 'App\Models\User'),
            'PolicyModel' => $model,
            'Model' => $modelName,
            'permissionModelVariable' => Str::snake($modelName),
            'modelVariable' => lcfirst($modelName),
        ];

        $filesystem = app(Filesystem::class);
        $filesystem->ensureDirectoryExists($destPath);
        $filesystem->put(
            $destPath . $policyName . '.php',
            self::renderStub($filesystem->get($stubFile), $replacements),
        );

        return true;
    }

    public static function getPolicyName(string $resource): string
    {
        $model = $resource::getModel();
        $modelName = class_basename($model);
        $policyName = $modelName . 'Policy';

        return config('policy-generator.namespace', 'App') . '\\Policies\\' . $policyName;
    }

    public static function exists(string $resource): bool
    {
        $model = $resource::getModel();
        $modelName = class_basename($model);
        $policyName = $modelName . 'Policy';
        $destPath = base_path('app/Policies/');

        return file_exists($destPath . $policyName . '.php');
    }

    /**
     * @param  array<string, string>  $replacements
     */
    protected static function renderStub(string $stub, array $replacements): string
    {
        $placeholders = array_map(
            fn (string $placeholder): string => '{{' . $placeholder . '}}',
            array_keys($replacements),
        );

        return str_replace($placeholders, array_values($replacements), $stub);
    }
}
