<?php

namespace NaggaDIM\LaravelMaxBot\API\DTO\User;

readonly class User extends MaxUser
{
    public function __construct(
        int $userId,
        bool $isBot,
        string $firstName,
        ?string $lastName,
        ?string $username,
        ?float $lastActivityTime,
    )
    {
        parent::__construct(
            userId: $userId,
            isBot: $isBot,
            firstName: $firstName,
            lastName: $lastName,
            username: $username,
            lastActivityTime: $lastActivityTime,
        );
    }

    public static function fromJson(array $json): static
    {
        return new static(
            userId: $json['user_id'],
            isBot: $json['is_bot'],
            firstName: $json['first_name'],
            lastName: $json['last_name'] ?? null,
            username: $json['username'] ?? null,
            lastActivityTime: $json['last_activity_time'] ?? null,
        );
    }
}