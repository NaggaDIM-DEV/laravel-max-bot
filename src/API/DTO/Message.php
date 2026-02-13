<?php

namespace NaggaDIM\LaravelMaxBot\API\DTO;

use NaggaDIM\LaravelMaxBot\API\DTO\User\User;

readonly class Message
{
    public function __construct(
        public Recipient $recipient,
        public int $timestamp,
        public MessageBody $body,
        public ?User $sender = null,
        public ?LinkedMessage $link = null,
        public ?MessageStat $stat = null,
        public ?string $url = null,
    ) {}

    public static function fromJson(array $json): static
    {
        return new static(
            Recipient::fromJson($json['recipient']),
            $json['timestamp'],
            MessageBody::fromJson($json['body']),
            isset($json['sender']) ? User::fromJson($json['sender']) : null,
            isset($json['link']) ? LinkedMessage::fromJson($json['link']) : null,
            isset($json['stat']) ? MessageStat::fromJson($json['stat']) : null,
            $json['url'] ?? null,
        );
    }
}