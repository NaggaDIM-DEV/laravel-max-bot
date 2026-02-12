<?php

namespace NaggaDIM\LaravelMaxBot\API\DTO;

use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use NaggaDIM\LaravelMaxBot\Enums\UpdateType;

readonly class Subscription
{
    /**
     * @param string $url
     * @param int $time
     * @param Collection<UpdateType> $update_types
     */
    public function __construct(
        public string $url,
        public int $time,
        public Collection $update_types,
    ) {}

    public function createdAt(): Carbon
    {
        return Carbon::createFromTimestamp($this->time / 1000);
    }

    public static function fromJSON(array $json): static
    {
        return new static(
            $json['url'],
            intval($json['time']),
            collect($json['update_types'])
                ->map(fn($e) => UpdateType::tryFrom($e)),
        );
    }
}