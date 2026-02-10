<?php

namespace NaggaDIM\LaravelMaxBot;

use NaggaDIM\LaravelMaxBot\Enums\Mode;
use NaggaDIM\LaravelMaxBot\Enums\UpdateType;

interface ILaravelMaxBot
{
    /**
     * @param UpdateType[]|null $allowedUpdates
     * @param Mode $mode
     * @return void
     */
    public function start(?array $allowedUpdates = null, Mode $mode = Mode::AUTO_DETECT): void;
}