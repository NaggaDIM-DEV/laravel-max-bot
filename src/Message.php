<?php

namespace NaggaDIM\LaravelMaxBot;

use NaggaDIM\LaravelMaxBot\Enums\MessageFormat;

class Message
{
    /** @var Attachment[] $attachments */
    protected array $attachments = [];
    public function __construct(
        protected string $text,
        protected MessageFormat $format = MessageFormat::MARKDOWN,
        protected null|Keyboard $keyboard = null,
    ) {}

    public function setText(string $text): static
    {
        return tap($this, fn() => $this->text = $text);
    }

    public function setFormat(MessageFormat $format): static
    {
        return tap($this, fn() => $this->format = $format);
    }

    public function setKeyboard(null|Keyboard $keyboard): static
    {
        return tap($this, fn() => $this->keyboard = $keyboard);
    }


    public function toJson(): array
    {
        return [
            'text' => $this->text,
            'format' => $this->format->value,
        ];
    }
}