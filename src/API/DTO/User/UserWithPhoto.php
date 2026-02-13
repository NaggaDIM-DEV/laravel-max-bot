<?php

namespace NaggaDIM\LaravelMaxBot\API\DTO\User;

readonly class UserWithPhoto
{
    public function __construct(
        public int $userId,
        public bool $isBot,
        public string $firstName,
        public ?string $lastName,
        public ?string $username,
        public ?float $lastActivityTime,
        public ?string $description = null,
        public ?string $avatarUrl = null,
        public ?string $fullAvatarUrl = null,
    ) {}

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