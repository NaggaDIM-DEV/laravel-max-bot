<?php

namespace NaggaDIM\LaravelMaxBot\API\DTO;

readonly class Image
{
    public function __construct(
        public string $url,
    ) {}

    public static function fromJson(array $json): Image
    {
        return new Image(
            url: $json['url'],
        );
    }

    public function toJson(): array
    {
        return [
            'url' => $this->url,
        ];
    }
}