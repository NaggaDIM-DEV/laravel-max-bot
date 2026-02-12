<?php

namespace NaggaDIM\LaravelMaxBot\API\Helpers;

class Attachment
{
    public function __construct(protected string $type)
    {

    }

    public function toJson(): array
    {
        return [
            'type' => $this->type,
        ];
    }
}