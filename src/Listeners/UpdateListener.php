<?php

namespace NaggaDIM\LaravelMaxBot\Listeners;

use NaggaDIM\LaravelMaxBot\Enums\UpdateType;

abstract class UpdateListener extends Listener
{
    protected UpdateType $updateType;
    protected ?string $description;

    public function getUpdateType(): UpdateType
    {
        return $this->updateType;
    }
}