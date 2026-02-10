<?php

namespace NaggaDIM\LaravelMaxBot\Helpers;

class UpdateHelper
{
    public static function getUserID(array &$update): int|null
    {
        return match (true) {
            !empty($update['callback']['user']['user_id'] ?? null) => $update['callback']['user']['user_id'],
            !empty($update['callback']['sender']['user_id'] ?? null) => $update['callback']['sender']['user_id'],
            !empty($update['message']['sender']['user_id'] ?? null) => $update['message']['sender']['user_id'],
            !empty($update['message']['user']['user_id'] ?? null) => $update['message']['user']['user_id'],
            default => null,
        };
    }

    public static function getMessageID(array &$update): string|null
    {
        return match (true) {
            !empty($update['message']['body']['mid'] ?? null) => trim($update['message']['body']['mid']),
            default => null,
        };
    }

    public static function getMessageText(array &$update): string|null
    {
        return match (true) {
            !empty($update['message']['body']['text'] ?? null) => trim($update['message']['body']['text']),
            default => null,
        };
    }
}