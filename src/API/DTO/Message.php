<?php

namespace NaggaDIM\LaravelMaxBot\API\DTO;

use NaggaDIM\LaravelMaxBot\API\DTO\User\User;

readonly class Message
{
    public function __construct(
        public User $sender,
        public Recipient $recipient,
        public int $timestamp,
        public LinkedMessage $link,
        public MessageBody $body,
        public ?MessageStat $stat = null,
        public ?string $url = null,
    ) {}

    public static function fromJson(array $json): static
    {
        return new static(
            User::fromJson($json['sender']),
            Recipient::fromJson($json['recipient']),
            $json['timestamp'],
            LinkedMessage::fromJson($json['link']),
            MessageBody::fromJson($json['body']),
            MessageStat::fromJson($json['stat']) ?? null,
            $json['url'] ?? null,
        );
    }
}