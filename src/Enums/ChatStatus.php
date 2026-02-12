<?php

namespace NaggaDIM\LaravelMaxBot\Enums;

enum ChatStatus: string
{
    case ACTIVE = 'active';
    case REMOVED = 'removed';
    case LEFT = 'left';
    case CLOSED = 'closed';
}
