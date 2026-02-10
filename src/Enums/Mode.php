<?php

namespace NaggaDIM\LaravelMaxBot\Enums;

enum Mode
{
    case AUTO_DETECT;
    case LONG_POLLING;
    case WEBHOOKS;
}
