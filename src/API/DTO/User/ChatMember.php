<?php

namespace NaggaDIM\LaravelMaxBot\API\DTO\User;

use NaggaDIM\LaravelMaxBot\API\DTO\ChatAdminPermission;
use Ramsey\Collection\Collection;

readonly class ChatMember
{
    public function __construct(
        public int $userId,
        public bool $isBot,
        public string $firstName,
        public bool $isOwner,
        public bool $isAdmin,
        public ?string $lastName,
        public ?string $username,
        public ?float $lastActivityTime,
        public ?float $lastAccessTime = null,
        public ?float $joinTime = null,
        public ?string $description = null,
        public ?string $avatarUrl = null,
        public ?string $fullAvatarUrl = null,
        public ?string $alias = null,
        public ?Collection $permissions = null
    ) {}

    public static function fromJson(array $json): static
    {
        $permissions = $json['permissions'] ?? null;
        if(!is_null($permissions)) {
            $permissions = collect($permissions)
                ->map(fn(string $permission) => new ChatAdminPermission(name: $permission));
        }

        return new static(
            userId: $json['user_id'],
            isBot: $json['is_bot'],
            firstName: $json['first_name'],
            isOwner: $json['is_owner'],
            isAdmin: $json['is_admin'],
            lastName: $json['last_name'] ?? null,
            username: $json['username'] ?? null,
            lastActivityTime: $json['last_activity_time'] ?? null,
            lastAccessTime: $json['last_access_time'] ?? null,
            joinTime: $json['join_time'] ?? null,
            description: $json['description'] ?? null,
            avatarUrl: $json['avatar_url'] ?? null,
            fullAvatarUrl: $json['full_avatar_url'] ?? null,
            alias: $json['alias'] ?? null,
            permissions: $permissions,
        );
    }
}