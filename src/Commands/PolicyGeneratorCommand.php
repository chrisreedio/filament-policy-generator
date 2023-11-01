<?php

namespace ChrisReedIO\PolicyGenerator\Commands;

use Filament\Facades\Filament;
use Illuminate\Console\Command;
use Illuminate\Support\Str;
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
            $stubFile = __DIR__ . '/stubs/GenericPolicy.stub';
            $destPath = base_path('app/Policies/');
            dump("Generating {$policyName} from {$stubFile} to {$destPath}.");
            StubGenerator::from($stubFile, true) // the stub file path
                ->to($destPath, true, true) // the store directory path
                ->as($policyName) // the generatable file name without extension
                // ->ext('php') // the file extension(optional, by default to php)
                // ->noExt() // to remove the extension from the file name for the generated file like .env
                ->withReplacers([]) // the stub replacing params
                ->save(); // save the file
        }

        $this->comment('All done');

        return self::SUCCESS;
    }

    // From Filament Shield - https://github.com/bezhanSalleh/filament-shield/blob/3.x/src/Commands/Concerns/CanGeneratePolicy.php#L21C19-L21C19
    protected function generatePolicyPath(array $entity): string
    {
        $path = (new \ReflectionClass($entity['fqcn']::getModel()))->getFileName();

        if (Str::of($path)->contains(['vendor', 'src'])) {
            $basePolicyPath = app_path(
                (string) Str::of($entity['model'])
                    ->prepend('Policies\\')
                    ->replace('\\', DIRECTORY_SEPARATOR),
            );

            return "{$basePolicyPath}Policy.php";
        }

        /** @phpstan-ignore-next-line */
        $basePath = Str::of($path)
            ->replace('Models', 'Policies')
            ->replaceLast('.php', 'Policy.php')
            ->replace('\\', DIRECTORY_SEPARATOR);

        return $basePath;
    }
}
