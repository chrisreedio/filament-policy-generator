<?php

use ChrisReedIO\PolicyGenerator\PolicyGenerator;
use Illuminate\Filesystem\Filesystem;

final class ArticleModel {}

final class ArticleResource
{
    public static function getModel(): string
    {
        return ArticleModel::class;
    }
}

it('generates a policy using Laravel filesystem services', function () {
    config()->set('policy-generator.namespace', 'Testing');
    config()->set('policy-generator.user_model', 'App\Models\Administrator');

    $generated = PolicyGenerator::generate(ArticleResource::class);
    $policyPath = base_path('app/Policies/ArticleModelPolicy.php');
    $policy = file_get_contents($policyPath);

    expect($generated)->toBeTrue()
        ->and($policyPath)->toBeFile()
        ->and($policy)
        ->toContain('namespace Testing\Policies;')
        ->toContain('use App\Models\Administrator;')
        ->toContain('use ArticleModel;')
        ->toContain('view_any::article_model')
        ->not->toContain('{{');
});

it('preserves an existing policy unless overwrite is requested', function () {
    $policyPath = base_path('app/Policies/ArticleModelPolicy.php');

    app(Filesystem::class)->ensureDirectoryExists(dirname($policyPath));
    file_put_contents($policyPath, 'existing policy');

    expect(PolicyGenerator::generate(ArticleResource::class))->toBeFalse()
        ->and(file_get_contents($policyPath))->toBe('existing policy')
        ->and(PolicyGenerator::generate(ArticleResource::class, overwrite: true))->toBeTrue()
        ->and(file_get_contents($policyPath))->not->toBe('existing policy');
});
