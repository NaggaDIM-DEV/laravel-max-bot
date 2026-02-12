<?php

namespace NaggaDIM\LaravelMaxBot\API\DTO;

use NaggaDIM\LaravelMaxBot\API\DTO\User\User;
use NaggaDIM\LaravelMaxBot\Enums\MessageLinkType;

readonly class LinkedMessage
{
    public function __construct(
        public MessageLinkType $type,
        public User $sender,
        public MessageBody $message,
        public ?int $chatId = null,
    ) {}

    public static function fromJson(array $json): static
    {
        return new static(
            MessageLinkType::from($json['type']),
            User::fromJson($json['sender']),
            MessageBody::fromJson($json['message']),
            $json['chat_id'] ?? null,
        );
    }
}