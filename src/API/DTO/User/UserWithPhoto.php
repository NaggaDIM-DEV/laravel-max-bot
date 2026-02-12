<?php

namespace NaggaDIM\LaravelMaxBot\API\DTO\User;

readonly class UserWithPhoto extends User
{
    public function __construct(
        int $userId,
        bool $isBot,
        string $firstName,
        ?string $lastName,
        ?string $username,
        ?float $lastActivityTime,
        public ?string $description = null,
        public ?string $avatarUrl = null,
        public ?string $fullAvatarUrl = null,
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
            description: $json['description'] ?? null,
            avatarUrl: $json['avatar_url'] ?? null,
            fullAvatarUrl: $json['full_avatar_url'] ?? null,
        );
    }
}