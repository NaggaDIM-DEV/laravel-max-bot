<?php

namespace NaggaDIM\LaravelMaxBot\Laravel\Providers;

use Illuminate\Support\ServiceProvider;
use NaggaDIM\LaravelMaxBot\Laravel\Console\Commands\StartPollingCommand;
use NaggaDIM\LaravelMaxBot\Service\ILaravelMaxBot;
use NaggaDIM\LaravelMaxBot\Service\LaravelMaxBot;

class LaravelMaxBotServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        if($this->app->runningInConsole()) {
            $this->loadMigrationsFrom(__DIR__.'/../../database/migrations');

            $this->publishes([
                __DIR__.'/../../config/max-messenger.php' => config_path('max-messenger.php'),
            ]);

            $this->commands([
                StartPollingCommand::class,
            ]);
        }

        $this->loadRoutesFrom(__DIR__.'/../../routes/api.php');
    }

    public function register(): void
    {
        $this->app->bind(
            ILaravelMaxBot::class,
            LaravelMaxBot::class,
        );
    }
}
