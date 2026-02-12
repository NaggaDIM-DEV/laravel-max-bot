<?php

namespace NaggaDIM\LaravelMaxBot\API\Helpers;

class Keyboard extends Attachment
{
    protected array $rows = [];

    public static function inlineKeyboard(): static
    {
        return new Keyboard(type: 'inline_keyboard');
    }

    public function addRow(Button ...$buttons): static
    {
        return tap($this, fn() => $this->rows[] = $buttons);
    }

    public function toJson(): array
    {
        return array_merge(parent::toJson(), [
            'payload'   => [
                'buttons' => array_map(
                    fn($buttons) => array_map(
                        fn(Button $button) => $button->toJson(),
                        $buttons
                    ),
                    $this->rows
                ),
            ]
        ]);
    }
}