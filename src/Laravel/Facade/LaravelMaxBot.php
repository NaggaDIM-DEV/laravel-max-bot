<?php

namespace NaggaDIM\LaravelMaxBot\Laravel\Facade;

use Illuminate\Support\Facades\Facade;
use NaggaDIM\LaravelMaxBot\Enums\Mode;
use NaggaDIM\LaravelMaxBot\Enums\UpdateType;
use NaggaDIM\LaravelMaxBot\ILaravelMaxBot;

/**
 * @method static void start(null|UpdateType[] $allowedUpdates = null, Mode $mode = Mode::AUTO_DETECT)
 *
 * @see ILaravelMaxBot
 */
class LaravelMaxBot extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return ILaravelMaxBot::class;
    }
}