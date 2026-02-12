<?php

namespace NaggaDIM\LaravelMaxBot\Helpers;

use NaggaDIM\LaravelMaxBot\Enums\UpdateType;

class UpdateHelper
{
    public static function getUpdateVerifyHash(array &$update): string
    {
        return md5(UpdateHelper::getUpdateType($update)->value . "_" . UpdateHelper::getUpdateTimestamp($update));
    }

    public static function getUpdateType(array &$update): UpdateType|string
    {
        return UpdateType::tryFrom($update['update_type']) ?? $update['update_type'] ?? 'undefined';
    }

    public static function getUpdateTimestamp(array &$update): float
    {
        return ($update['timestamp'] ?? (now()->timestamp * 1000)) / 1000;
    }

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

    public static function getCallbackID(array &$update): string|null
    {
        return match (true) {
            !empty($update['callback']['callback_id'] ?? null) => $update['callback']['callback_id'],
            default => null,
        };
    }

    public static function getCallbackPayload(array &$update): string|null
    {
        return match (true) {
            !empty($update['callback']['payload'] ?? null) => $update['callback']['payload'],
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