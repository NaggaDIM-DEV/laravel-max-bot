<?php

namespace NaggaDIM\LaravelMaxBot\API\DTO;

readonly class BotCommand
{
    public function __construct(
        public string $name,
        public ?string $description = null,
    ) {}

    public static function fromJson(array $json): static
    {
        return new static(
            name: $json['name'],
            description: $json['description'] ?? null,
        );
    }
}