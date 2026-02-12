<?php

namespace NaggaDIM\LaravelMaxBot\API\DTO;

use Illuminate\Support\Collection;
use NaggaDIM\LaravelMaxBot\API\DTO\User\ChatMember;
use NaggaDIM\LaravelMaxBot\API\DTO\User\UserWithPhoto;
use NaggaDIM\LaravelMaxBot\Enums\ChatStatus;
use NaggaDIM\LaravelMaxBot\Enums\ChatType;

readonly class Chat
{
    public function __construct(
        public int $chat_id,
        public ChatType $type,
        public ChatStatus $status,
        public int $lastEventTime,
        public int $participantsCount,
        public bool $isPublic,
        public ?string $title = null,
        public ?Image $icon = null,
        public ?int $ownerId = null,
        public ?Collection $participants = null,
        public ?string $link = null,
        public ?string $description = null,
        public ?UserWithPhoto $dialogWithUser = null,
        public ?string $chatMessageId = null,
        /* TODO: pinned_message */
    ) {}

    public static function fromJson(array $json): Chat
    {
        return new Chat(
            $json['chat_id'],
            ChatType::from($json['type']),
            ChatStatus::from($json['status']),
            $json['last_event_time'],
            $json['participants_count'],
            $json['is_public'],
            $json['title'] ?? null,
            !empty($json['icon'] ?? null) ? Image::fromJson($json['icon']) : null,
            $json['owner_id'] ?? null,
            !empty($json['participants'] ?? null)
                ? collect($json['participants'])->map(fn($e) => ChatMember::fromJson($e))
                : null,
            $json['link'] ?? null,
            $json['description'] ?? null,
            !empty($json['dialog_with_user'] ?? null) ? UserWithPhoto::fromJson($json['dialog_with_user']) : null,
            $json['chat_message_id'] ?? null,
        );
    }
}