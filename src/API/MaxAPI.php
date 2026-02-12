<?php

namespace NaggaDIM\LaravelMaxBot\API;

use GuzzleHttp\Promise\PromiseInterface;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use JsonSerializable;
use NaggaDIM\LaravelMaxBot\API\DTO\Chat;
use NaggaDIM\LaravelMaxBot\API\DTO\Image;
use NaggaDIM\LaravelMaxBot\API\DTO\Subscription;
use NaggaDIM\LaravelMaxBot\API\DTO\Message as MessageDTO;
use NaggaDIM\LaravelMaxBot\API\DTO\User\BotInfo;
use NaggaDIM\LaravelMaxBot\API\Helpers\Message;
use NaggaDIM\LaravelMaxBot\API\Responses\GetChatsResponse;
use NaggaDIM\LaravelMaxBot\Enums\ChatAction;
use NaggaDIM\LaravelMaxBot\Enums\UpdateType;
use NaggaDIM\LaravelMaxBot\Exceptions\APIException;
use NaggaDIM\LaravelMaxBot\Exceptions\InvalidArgumentException;
use NaggaDIM\LaravelMaxBot\Exceptions\MaxBotException;
use NaggaDIM\LaravelMaxBot\Exceptions\NotFilledTokenException;

class MaxAPI implements IMaxAPI
{
    protected string $baseURL;
    protected string $token;

    public function __construct()
    {
        $this->baseURL = Config::get('maxbot.api_url');
        $this->token = Config::get('maxbot.token');
    }

    protected function buildUri(string $path = '/', null|array|string $query = null): string
    {
        return $this->baseURL . (str_starts_with($path, '/') ? $path : '/' . $path)
            . (!empty($query) ? '?' . (is_array($query) ? http_build_query($query) : $query) : '');
    }

    /**
     * @throws NotFilledTokenException
     */
    protected function buildHeaders(null|array $additionalHeaders = null): array
    {
        if(empty($this->token)) {
            throw new NotFilledTokenException();
        }

        return array_merge(
            $additionalHeaders ?? [],
            ['Authorization' => $this->token]
        );
    }

    /**
     * @throws MaxBotException
     * @throws ConnectionException
     */
    public function get(
        string $path = '/',
        null|array|string $query = null,
        null|array $headers = null
    ): Response|PromiseInterface
    {
        return Http::withHeaders($this->buildHeaders($headers))
            ->get($this->buildUri($path), $query);
    }

    /**
     * @throws MaxBotException
     * @throws ConnectionException
     */
    public function post(
        string $path = '/',
        array|JsonSerializable|Arrayable $data = [],
        null|array|string $query = null,
        null|array $headers = null
    ): Response|PromiseInterface
    {
        return Http::withHeaders($this->buildHeaders($headers))
            ->post($this->buildUri($path, $query), $data);
    }

    /**
     * @throws MaxBotException
     * @throws ConnectionException
     */
    public function put(
        string $path = '/',
        array|JsonSerializable|Arrayable $data = [],
        null|array|string $query = null,
        null|array $headers = null
    ): Response|PromiseInterface
    {
        return Http::withHeaders($this->buildHeaders($headers))
            ->put($this->buildUri($path, $query), $data);
    }

    /**
     * @throws MaxBotException
     * @throws ConnectionException
     */
    public function patch(
        string $path = '/',
        array|JsonSerializable|Arrayable $data = [],
        null|array|string $query = null,
        null|array $headers = null
    ): Response|PromiseInterface
    {
        return Http::withHeaders($this->buildHeaders($headers))
            ->patch($this->buildUri($path, $query), $data);
    }

    /**
     * @throws MaxBotException
     * @throws ConnectionException
     */
    public function delete(
        string $path = '/',
        array|JsonSerializable|Arrayable $data = [],
        null|array|string $query = null,
        null|array $headers = null
    ): Response|PromiseInterface
    {
        return Http::withHeaders($this->buildHeaders($headers))
            ->delete($this->buildUri($path, $query), $data);
    }

    /**
     * @throws MaxBotException
     * @throws ConnectionException
     * @return Collection<Subscription>
     */
    public function getSubscriptions(): Collection
    {
        return collect($this->get('/subscriptions')->json()['subscriptions'])
            ->map(fn($e) => Subscription::fromJSON($e));
    }

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
    ): bool
    {
        $data = ['url' => $url];
        if(!empty($updateTypes)) {
            $data['update_types'] = collect($updateTypes)
                ->map(fn(UpdateType $e) => $e->value)
                ->toArray();
        }
        if(!empty($secret)) {
            $data['secret'] = $secret;
        }

        $response = $this->post('/subscriptions', $data);

        if(!($response->successful() && ($response->json()['success'] ?? false))) {
            throw new APIException(
                $response->json()['message'] ?? $response->body(),
                $response->status(),
            );
        }

        return true;
    }

    /**
     * @param string $url
     * @return bool
     *
     * @throws ConnectionException
     * @throws MaxBotException
     */
    public function deleteSubscription(string $url): bool
    {
        $response = $this->delete('/subscriptions', query: ['url' => $url]);

        if(!($response->successful() && ($response->json()['success'] ?? false))) {
            throw new APIException(
                $response->json()['message'] ?? $response->body(),
                $response->status(),
            );
        }

        return true;
    }

    /**
     * @throws MaxBotException
     * @throws ConnectionException
     */
    public function getMe(): BotInfo
    {
        return BotInfo::fromJson($this->get('/me')->json());
    }

    /**
     * @throws MaxBotException
     * @throws ConnectionException
     */
    public function getChats(int $count = 50, ?int $marker = null): GetChatsResponse
    {
        return GetChatsResponse::fromJson($this->get('/chats', ['count' => $count, 'marker' => $marker])->json());
    }

    /**
     * @throws MaxBotException
     * @throws ConnectionException
     */
    public function getChat(int $chatID): Chat
    {
        return Chat::fromJson($this->get("/chats/$chatID")->json());
    }

    /**
     * @throws MaxBotException
     * @throws ConnectionException
     */
    public function editChat(
        int $chatID,
        ?Image $icon = null,
        ?string $title = null,
        ?string $pin = null,
        bool $notify = true,
    ): Chat
    {
        return Chat::fromJson($this->patch("/chats/$chatID", [
            'icon' => $icon?->toJson() ?? null,
            'title' => $title,
            'pin' => $pin,
            'notify' => $notify,
        ])->json());
    }

    /**
     * @throws MaxBotException
     * @throws ConnectionException
     */
    public function editChatIcon(int $chatID, Image $icon, bool $notify = true): Chat
    {
        return $this->editChat($chatID, icon: $icon, notify: $notify);
    }

    /**
     * @throws MaxBotException
     * @throws ConnectionException
     */
    public function editChatTitle(int $chatID, string $title, bool $notify = true): Chat
    {
        return $this->editChat($chatID, title: $title, notify: $notify);
    }

    /**
     * @throws MaxBotException
     * @throws ConnectionException
     */
    public function editChatPin(int $chatID, string $pin, bool $notify = true): Chat
    {
        return $this->editChat($chatID, pin: $pin, notify: $notify);
    }

    /**
     * @throws MaxBotException
     * @throws ConnectionException
     * @throws APIException
     */
    public function deleteChat(int $chatID): bool
    {
        $response = $this->delete("/chats/$chatID");

        if(!($response->successful() && ($response->json()['success'] ?? false))) {
            throw new APIException(
                $response->json()['message'] ?? $response->body(),
                $response->status(),
            );
        }

        return true;
    }

    /**
     * @throws MaxBotException
     * @throws ConnectionException
     * @throws APIException
     */
    public function setChatAction(int $chatID, ChatAction $action): bool
    {
        $response = $this->post("/chats/$chatID/actions", [
            'action' => $action->value,
        ]);

        if(!($response->successful() && ($response->json()['success'] ?? false))) {
            throw new APIException(
                $response->json()['message'] ?? $response->body(),
                $response->status(),
            );
        }

        return true;
    }

    /**
     * @throws MaxBotException
     * @throws ConnectionException
     */
    public function getChatPin(int $chatID): ?MessageDTO
    {
        $response = $this->get("/chats/$chatID/pin")->json();

        return !empty($response['message'])
            ? MessageDTO::fromJson($response['message'])
            : null;
    }

    /**
     * @throws MaxBotException
     * @throws ConnectionException
     * @throws APIException
     */
    public function setChatPin(int $chatID, string $messageID, bool $notify = true): bool
    {
        $response = $this->put("/chats/$chatID/pin", [
            'message_id' => $messageID,
            'notify' => $notify,
        ]);

        if(!($response->successful() && ($response->json()['success'] ?? false))) {
            throw new APIException(
                $response->json()['message'] ?? $response->body(),
                $response->status(),
            );
        }

        return true;
    }

    /**
     * @throws MaxBotException
     * @throws ConnectionException
     * @throws APIException
     */
    public function deleteChatPin(int $chatID): bool
    {
        $response = $this->delete("/chats/$chatID/pin");

        if(!($response->successful() && ($response->json()['success'] ?? false))) {
            throw new APIException(
                $response->json()['message'] ?? $response->body(),
                $response->status(),
            );
        }

        return true;
    }

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
    ): array
    {
        $response = $this->get('/updates', [
            'limit' => $limit,
            'timeout' => $timeout,
            'marker' => $marker,
            'types' => !empty($types)
                ? array_map(fn(UpdateType $e) => $e->value, $types)
                : null,
        ])->json();

        return [
            'updates'   => $response['updates'],
            'marker'    => $response['marker'] ?? null,
        ];
    }

    /**
     * @throws MaxBotException
     * @throws ConnectionException
     */
    public function sendMessageToUser(int $userID, Message $message): bool
    {
        $response = $this->post('/messages', data: $message->toJson(), query: ['user_id' => $userID]);

        if(!$response->successful()) {
            throw new APIException(
                $response->body(),
                $response->status(),
            );
        }

        return true;
    }

    /**
     * @throws MaxBotException
     * @throws APIException
     * @throws ConnectionException
     */
    public function sendMessageToChat(int $chatID, Message $message): bool
    {
        $response = $this->post('/messages', data: $message->toJson(), query: ['chat_id' => $chatID]);

        if(!$response->successful()) {
            throw new APIException(
                $response->body(),
                $response->status(),
            );
        }

        return true;
    }

    /**
     * @throws MaxBotException
     * @throws ConnectionException
     * @throws InvalidArgumentException
     */
    public function answerToCallback(string $callbackID, ?Message $message = null, ?string $notification = null): bool
    {
        if(empty($message) && empty($notification)) {
            throw new InvalidArgumentException('Message or Notification must be set');
        }

        $data = [];
        if(!empty($message)) { $data['message'] = $message->toJson(); }
        if(!empty($notification)) { $data['notification'] = $notification; }

        $response = $this->post('/answers', data: $data, query: ['callback_id' => $callbackID]);

        if(!($response->successful() && ($response->json()['success'] ?? false))) {
            throw new APIException(
                $response->json()['message'] ?? $response->body(),
                $response->status(),
            );
        }

        return true;
    }
}