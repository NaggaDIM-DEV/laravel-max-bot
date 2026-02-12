<?php

namespace NaggaDIM\LaravelMaxBot\API\DTO;

readonly class MessageBody
{
    public function __construct(
        public string $mid,
        public int $seq,
        public string $text,
    ) {}

    public static function fromJson(array $json): static
    {
        return new static(
            $json['mid'],
            $json['seq'],
            $json['text'],
        );
    }
}