<?php

namespace MingyuKim\MoreCommand;

use Illuminate\Support\ServiceProvider;
use MingyuKim\MoreCommand\Console\Commands\MakeRepositoriesCommand;

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
            __DIR__ . '/../config/moreCommand.php' => config_path('moreCommand.php'),
        ], 'config');
    }
}