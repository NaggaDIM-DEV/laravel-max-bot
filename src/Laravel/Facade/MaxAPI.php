<?php

namespace NaggaDIM\LaravelMaxBot\Laravel\Facade;

use GuzzleHttp\Promise\PromiseInterface;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Facade;
use JsonSerializable;
use NaggaDIM\LaravelMaxBot\API\DTO\Subscription;
use NaggaDIM\LaravelMaxBot\API\IMaxAPI;
use NaggaDIM\LaravelMaxBot\Enums\UpdateType;
use NaggaDIM\LaravelMaxBot\Message;

/**
 * @method static Response|PromiseInterface get(string $path = '/', array|string|null $query = null, array|null $headers = null)
 * @method static Response|PromiseInterface post(string $path = '/', array|JsonSerializable|Arrayable $data = [], array|string|null $query = null, array|null $headers = null)
 * @method static Response|PromiseInterface delete(string $path = '/', array|JsonSerializable|Arrayable $data = [], array|string|null $query = null, array|null $headers = null)
 *
 * @method static Collection<Subscription> getSubscriptions()
 * @method static bool addSubscription(string $url, UpdateType[] $updateTypes, string|null $secret)
 * @method static bool deleteSubscription(string $url)
 *
 * @method static array getUpdates(int $limit = 100, int $timeout = 30, null|int $marker = null, null|array $types = null)
 *
 * @method static bool sendMessageToUser(int $userID, Message $message)
 * @method static bool sendMessageToChat(int $chatID, Message $message)
 *
 * @see IMaxAPI
 */
class MaxAPI extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return IMaxAPI::class;
    }
}