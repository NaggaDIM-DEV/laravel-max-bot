<?php

namespace NaggaDIM\LaravelMaxBot\Laravel\Providers;

use Illuminate\Support\ServiceProvider;
use NaggaDIM\LaravelMaxBot\API\IMaxAPI;
use NaggaDIM\LaravelMaxBot\API\MaxAPI;
use NaggaDIM\LaravelMaxBot\ILaravelMaxBot;
use NaggaDIM\LaravelMaxBot\Laravel\Console\Commands\StartPollingCommand;
use NaggaDIM\LaravelMaxBot\Laravel\Console\Commands\SubscriptionsCommand;
use NaggaDIM\LaravelMaxBot\LaravelMaxBot;
use NaggaDIM\LaravelMaxBot\MaxBotRouter;

class LaravelMaxBotServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        if($this->app->runningInConsole()) {
            $this->loadMigrationsFrom(__DIR__.'/../../database/migrations');

            $this->publishes([
                __DIR__.'/../../config/maxbot.php' => config_path('maxbot.php'),
                __DIR__.'/../../routes/maxbot.php' => base_path('routes/maxbot.php'),
            ], 'laravel-max-bot');

            $this->commands([
                StartPollingCommand::class,
                SubscriptionsCommand::class,
            ]);
        }

        $this->loadRoutesFrom(__DIR__.'/../../routes/api.php');
    }

    public function register(): void
    {
        $this->app->singleton(MaxBotRouter::class, function ($app) {
            return MaxBotRouter::bootstrap();
        });

        $this->app->bind(
            IMaxAPI::class,
            MaxAPI::class,
        );

        $this->app->bind(
            ILaravelMaxBot::class,
            LaravelMaxBot::class,
        );
    }
}
