<?php

namespace NaggaDIM\LaravelMaxBot\Listeners;

use NaggaDIM\LaravelMaxBot\Helpers\UpdateHelper;

abstract class CommandListener extends Listener
{
    protected string $command;
    protected ?string $description;

    public function getCommandPart(): string
    {
        return strtolower('/' . trim(explode(' ', $this->command)[0]));
    }

    protected function getArgumentsRegex(): string
    {
        return "/^" . substr($this->command, strlen($this->getCommandPart())) . "$/m";
    }

    protected function getArgumentsRawString(): string
    {
        return substr($this->getMessageText() ?? '', strlen($this->getCommandPart()) + 1);
    }

    protected function argument(null|string $key = null, null|string $default = null): null|string|array
    {
        $matches = [];
        preg_match_all($this->getArgumentsRegex(), $this->getArgumentsRawString(), $matches);
        $matches = array_filter($matches, fn($k) => is_string($k), ARRAY_FILTER_USE_KEY);

        if(empty($key)) { return $matches; }

        return $matches[$key] ?? $default;
    }
}