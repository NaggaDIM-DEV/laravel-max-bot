<?php

namespace NaggaDIM\LaravelMaxBot\API\Responses;

use Illuminate\Support\Collection;
use NaggaDIM\LaravelMaxBot\API\DTO\User\ChatMember;

readonly class GetChatMembersResponse
{
    public function __construct(
        public Collection $members,
        public ?int $marker = null,
    ) {}

    public static function fromJson(array $json): static
    {
        return new static(
            collect($json['members'])->map(fn($e) => ChatMember::fromJson($e)),
            $json['marker'] ?? null,
        );
    }
}