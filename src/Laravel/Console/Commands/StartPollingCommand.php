<?php

namespace NaggaDIM\LaravelMaxBot\Laravel\Console\Commands;

use Illuminate\Console\Command;
use NaggaDIM\LaravelMaxBot\Enums\Mode;
use NaggaDIM\LaravelMaxBot\Laravel\Facade\LaravelMaxBot;

class StartPollingCommand extends Command
{
    protected $signature = 'max:start-polling';

    protected $description = 'MAX Messenger Bot - Start in Long polling mode';

    public function handle(): void
    {
        LaravelMaxBot::start(mode: Mode::LONG_POLLING);
    }
}
