<?php

namespace NaggaDIM\LaravelMaxBot\API\DTO;

readonly class ChatAdminPermission
{
    public function __construct(
        protected string $name,
    ) {}
}