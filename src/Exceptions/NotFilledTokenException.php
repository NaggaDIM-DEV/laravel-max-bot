<?php

namespace NaggaDIM\LaravelMaxBot\Exceptions;

use NaggaDIM\LaravelMaxBot\Exceptions\MaxBotException;
use Throwable;

class NotFilledTokenException extends MaxBotException
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct(
            "Max Bot Token is not filled",
            1,
            $previous
        );
    }
}