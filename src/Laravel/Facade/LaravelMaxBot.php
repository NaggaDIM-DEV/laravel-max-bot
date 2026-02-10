<?php

namespace NaggaDIM\LaravelMaxBot\Laravel\Facade;

use Illuminate\Support\Facades\Facade;
use NaggaDIM\LaravelMaxBot\Service\ILaravelMaxBot;

class LaravelMaxBot extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return ILaravelMaxBot::class;
    }
}