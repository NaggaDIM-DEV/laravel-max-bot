<?php

namespace NaggaDIM\LaravelMaxBot\API;

use GuzzleHttp\Promise\PromiseInterface;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Collection;
use JsonSerializable;
use NaggaDIM\LaravelMaxBot\API\DTO\Subscription;
use NaggaDIM\LaravelMaxBot\Enums\UpdateType;
use NaggaDIM\LaravelMaxBot\Exceptions\APIException;
use NaggaDIM\LaravelMaxBot\Exceptions\MaxBotException;
use NaggaDIM\LaravelMaxBot\Message;

interface IMaxAPI
{
    /**
     * @throws MaxBotException
     * @throws ConnectionException
     */
    public function get(
        string $path = '/',
        null|array|string $query = null,
        null|array $headers = null
    ): Response|PromiseInterface;

    /**
     * @throws MaxBotException
     * @throws ConnectionException
     */
    public function post(
        string $path = '/',
        array|JsonSerializable|Arrayable $data = [],
        null|array|string $query = null,
        null|array $headers = null
    ): Response|PromiseInterface;

    /**
     * @throws MaxBotException
     * @throws ConnectionException
     */
    public function delete(
        string $path = '/',
        array|JsonSerializable|Arrayable $data = [],
        null|array|string $query = null,
        null|array $headers = null
    ): Response|PromiseInterface;

    /**
     * @throws MaxBotException
     * @throws ConnectionException
     * @return Collection<Subscription>
     */
    public function getSubscriptions(): Collection;

    /**
     * @param string $url
     * @param array<UpdateType>|Collection<UpdateType>|null $updateTypes
     * @param string|null $secret
     * @return bool
     *
     * @throws ConnectionException
     * @throws MaxBotException
     */
    public function addSubscription(
        string $url,
        null|array|Collection $updateTypes = null,
        null|string $secret = null
    ): bool;

    /**
     * @param string $url
     * @return bool
     *
     * @throws ConnectionException
     * @throws MaxBotException
     */
    public function deleteSubscription(string $url): bool;

    /**
     * @param int<1,1000> $limit
     * @param int<0,90> $timeout
     * @param positive-int|null $marker
     * @param UpdateType[]|null $types
     *
     * @return array{updates: array, marker: null|int}
     * @throws ConnectionException
     * @throws MaxBotException
     */
    public function getUpdates(
        int $limit = 100,
        int $timeout = 30,
        null|int $marker = null,
        null|array $types = null
    ): array;

    /**
     * @throws MaxBotException
     * @throws APIException
     * @throws ConnectionException
     */
    public function sendMessageToUser(int $userID, Message $message): bool;

    /**
     * @throws MaxBotException
     * @throws APIException
     * @throws ConnectionException
     */
    public function sendMessageToChat(int $chatID, Message $message): bool;
}