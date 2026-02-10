<?php

namespace NaggaDIM\LaravelMaxBot\Laravel\Console\Commands;

use Illuminate\Console\Command;

class StartPollingCommand extends Command
{
    protected $signature = 'max:start-polling';

    protected $description = 'MAX Messenger Bot - Start in Long polling mode';

    public function handle(): void
    {

    }
}
