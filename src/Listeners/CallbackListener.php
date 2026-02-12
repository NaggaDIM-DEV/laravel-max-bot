<?php

namespace NaggaDIM\LaravelMaxBot\Listeners;

abstract class CallbackListener extends Listener
{
    protected string $callback;
    abstract public function handle(): void;

    public function getCallback(): string
    {
        return $this->callback;
    }

    public function arguments(): array
    {
        $args = explode(':', trim($this->getCallbackPayload() ?? ''));
        if(count($args) < 2) { return []; }

        return array_slice($args, 1);
    }
}