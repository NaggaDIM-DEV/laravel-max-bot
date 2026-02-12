<?php

namespace NaggaDIM\LaravelMaxBot\Enums;

enum MessageLinkType: string
{
    case FORWARD = 'forward';
    case REPLY = 'reply';
}
