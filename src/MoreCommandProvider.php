<?php

namespace MingyuKim\MoreCommand;

use Illuminate\Support\ServiceProvider;
use MingyuKim\MoreCommand\Console\Commands\MakeRepositoriesCommand;
use MingyuKim\MoreCommand\Console\Commands\MakeRepositoryCommand;
use MingyuKim\MoreCommand\Console\Commands\MakeServiceCommand;
use MingyuKim\MoreCommand\Console\Commands\MakeTraitCommand;
use MingyuKim\MoreCommand\Console\Commands\MakeViewCommand;

class MoreCommandProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->commands([
            MakeRepositoriesCommand::class,
            MakeRepositoryCommand::class,
            MakeTraitCommand::class,
            MakeServiceCommand::class,
            MakeViewCommand::class,
        ]);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/more-command.php' => config_path('more-command.php'),
        ], 'config');
    }
}
