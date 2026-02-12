<?php

namespace NaggaDIM\LaravelMaxBot\API\DTO;

use NaggaDIM\LaravelMaxBot\Enums\ChatType;

readonly class Recipient
{
    public function __construct(
        public int $chatId,
        public ChatType $chatType,
        public int $userId,
    ) {}

    public static function fromJson(array $json): Recipient
    {
        return new Recipient(
            $json['chat_id'],
            ChatType::from($json['chat_type']),
            $json['user_id'],
        );
    }
}