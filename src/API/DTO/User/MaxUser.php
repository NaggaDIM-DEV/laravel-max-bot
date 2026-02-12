<?php

namespace NaggaDIM\LaravelMaxBot\API\DTO\User;

abstract readonly class MaxUser
{
    public function __construct(
        public int $userId,
        public bool $isBot,
        public string $firstName,
        public ?string $lastName = null,
        public ?string $username = null,
        public ?float $lastActivityTime = null,
        public ?string $description = null,
        public ?string $avatarUrl = null,
        public ?string $fullAvatarUrl = null,
    ) {}

    abstract public static function fromJson(array $json): static;
}