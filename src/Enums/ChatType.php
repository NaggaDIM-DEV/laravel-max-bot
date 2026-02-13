<?php

namespace NaggaDIM\LaravelMaxBot\Enums;

enum ChatType: string
{
    case CHAT = 'chat';
    case CHANNEL = 'channel';
    case DIALOG = 'dialog';
}
