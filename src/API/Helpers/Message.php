<?php

namespace NaggaDIM\LaravelMaxBot\API\Helpers;

use NaggaDIM\LaravelMaxBot\Enums\MessageFormat;

class Message
{
    /** @var Attachment[] $attachments */
    protected array $attachments = [];

    public function __construct(
        protected string $text,
        protected MessageFormat $format = MessageFormat::MARKDOWN,
        protected bool $notify = true,
    ) {}

    public function setText(string $text): static
    {
        return tap($this, fn() => $this->text = $text);
    }

    public function setFormat(MessageFormat $format): static
    {
        return tap($this, fn() => $this->format = $format);
    }

    public function setNotify(bool $notify = true): static
    {
        return tap($this, fn() => $this->notify = $notify);
    }

    public function attachmentsSetEmpty(): static
    {
        return tap($this, fn() => $this->attachments = []);
    }

    public function addAttachment(Attachment $attachment): static
    {
        return tap($this, fn() => $this->attachments[] = $attachment);
    }

    public function addKeyboard(Keyboard $keyboard): static
    {
        return $this->addAttachment($keyboard);
    }


    public function toJson(): array
    {
        return [
            'text' => $this->text,
            'format' => $this->format->value,
            'notify' => $this->notify,
            'attachments' => array_map(
                fn(Attachment $attachment) => $attachment->toJson(),
                $this->attachments
            ),
        ];
    }
}