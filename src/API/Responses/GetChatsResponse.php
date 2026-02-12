<?php

namespace NaggaDIM\LaravelMaxBot\API\Responses;

use Illuminate\Support\Collection;
use NaggaDIM\LaravelMaxBot\API\DTO\Chat;

readonly class GetChatsResponse
{
    /**
     * @param Collection<Chat> $chats
     * @param int|null $marker
     */
    public function __construct(
        public Collection $chats,
        public ?int $marker = null,
    ) {}

    public static function fromJson(array $json): GetChatsResponse
    {
        return new GetChatsResponse(
            collect($json['chats'])->map(fn($e) => Chat::fromJson($e)),
            $json['marker'] ?? null,
        );
    }
}