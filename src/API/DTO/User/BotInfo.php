<?php

namespace NaggaDIM\LaravelMaxBot\API\DTO\User;

use Illuminate\Support\Collection;
use NaggaDIM\LaravelMaxBot\API\DTO\BotCommand;

readonly class BotInfo extends UserWithPhoto
{
    public function __construct(
        int $userId,
        bool $isBot,
        string $firstName,
        ?string $lastName,
        ?string $username,
        ?float $lastActivityTime,
        ?string $description = null,
        ?string $avatarUrl = null,
        ?string $fullAvatarUrl = null,
        public ?Collection $commands = null,
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
        $commands = $json['commands'] ?? null;
        if(!is_null($commands)) {
            $commands = collect($commands)
                ->map(fn(array $command) => BotCommand::fromJson($command));
        }

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
            commands: $commands
        );
    }
}