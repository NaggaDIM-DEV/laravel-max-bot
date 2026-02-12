<?php

namespace NaggaDIM\LaravelMaxBot\API\DTO\User;

use NaggaDIM\LaravelMaxBot\API\DTO\ChatAdminPermission;
use Ramsey\Collection\Collection;

readonly class ChatMember extends UserWithPhoto
{
    public function __construct(
        int $userId,
        bool $isBot,
        string $firstName,
        bool $isOwner,
        bool $isAdmin,
        ?string $lastName,
        ?string $username,
        ?float $lastActivityTime,
        public ?float $lastAccessTime = null,
        public ?float $joinTime = null,
        ?string $description = null,
        ?string $avatarUrl = null,
        ?string $fullAvatarUrl = null,
        public ?string $alias = null,
        public ?Collection $permissions = null
    )
    {
        parent::__construct(
            $userId,
            $isBot,
            $firstName,
            $lastName,
            $username,
            $lastActivityTime,
            $description,
            $avatarUrl,
            $fullAvatarUrl
        );
    }

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