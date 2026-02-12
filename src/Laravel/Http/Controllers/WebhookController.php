<?php

namespace NaggaDIM\LaravelMaxBot\Laravel\Http\Controllers;

use NaggaDIM\LaravelMaxBot\Enums\Mode;
use NaggaDIM\LaravelMaxBot\Laravel\Facade\LaravelMaxBot;

class WebhookController
{
    public function __invoke()
    {
        LaravelMaxBot::start(mode: Mode::WEBHOOKS);
    }
}
