<?php

namespace ChrisReedIO\PolicyGenerator\Tests;

use BladeUI\Heroicons\BladeHeroiconsServiceProvider;
use BladeUI\Icons\BladeIconsServiceProvider;
use ChrisReedIO\PolicyGenerator\PolicyGeneratorServiceProvider;
use Filament\Actions\ActionsServiceProvider;
use Filament\FilamentServiceProvider;
use Filament\Forms\FormsServiceProvider;
use Filament\Infolists\InfolistsServiceProvider;
use Filament\Notifications\NotificationsServiceProvider;
use Filament\Support\SupportServiceProvider;
use Filament\Tables\TablesServiceProvider;
use Filament\Widgets\WidgetsServiceProvider;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Filesystem\Filesystem;
use Livewire\LivewireServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;
use RyanChandler\BladeCaptureDirective\BladeCaptureDirectiveServiceProvider;

class TestCase extends Orchestra
{
    protected string $originalBasePath;

    protected string $temporaryBasePath;

    protected function setUp(): void
    {
        parent::setUp();

        $this->originalBasePath = $this->app->basePath();
        $this->temporaryBasePath = sys_get_temp_dir() . '/filament-policy-generator-' . bin2hex(random_bytes(8));
        $this->app->setBasePath($this->temporaryBasePath);

        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'ChrisReedIO\\PolicyGenerator\\Database\\Factories\\' . class_basename($modelName) . 'Factory'
        );
    }

    protected function tearDown(): void
    {
        $this->app->setBasePath($this->originalBasePath);

        (new Filesystem)->deleteDirectory($this->temporaryBasePath);

        parent::tearDown();
    }

    protected function getPackageProviders($app): array
    {
        return [
            ActionsServiceProvider::class,
            BladeCaptureDirectiveServiceProvider::class,
            BladeHeroiconsServiceProvider::class,
            BladeIconsServiceProvider::class,
            FilamentServiceProvider::class,
            FormsServiceProvider::class,
            InfolistsServiceProvider::class,
            LivewireServiceProvider::class,
            NotificationsServiceProvider::class,
            SupportServiceProvider::class,
            TablesServiceProvider::class,
            WidgetsServiceProvider::class,
            PolicyGeneratorServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app): void
    {
        config()->set('database.default', 'testing');

        /*
        $migration = include __DIR__.'/../database/migrations/create_filament-policy-generator_table.php.stub';
        $migration->up();
        */
    }
}
