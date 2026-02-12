<?php

namespace NaggaDIM\LaravelMaxBot\API\DTO;

readonly class MessageStat
{
    public function __construct(
        public int $views
    ) {}

    public static function fromJson(array $json): static
    {
        return new static(
            $json['views']
        );
    }
}