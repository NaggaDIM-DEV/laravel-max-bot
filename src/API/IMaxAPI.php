<?php

namespace NaggaDIM\LaravelMaxBot\API;

use GuzzleHttp\Promise\PromiseInterface;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Collection;
use JsonSerializable;
use NaggaDIM\LaravelMaxBot\API\DTO\BotCommand;
use NaggaDIM\LaravelMaxBot\API\DTO\Chat;
use NaggaDIM\LaravelMaxBot\API\DTO\Image;
use NaggaDIM\LaravelMaxBot\API\DTO\Message as MessageDTO;
use NaggaDIM\LaravelMaxBot\API\DTO\Subscription;
use NaggaDIM\LaravelMaxBot\API\DTO\User\BotInfo;
use NaggaDIM\LaravelMaxBot\API\DTO\User\ChatMember;
use NaggaDIM\LaravelMaxBot\API\Helpers\Message;
use NaggaDIM\LaravelMaxBot\API\Responses\GetChatMembersResponse;
use NaggaDIM\LaravelMaxBot\API\Responses\GetChatsResponse;
use NaggaDIM\LaravelMaxBot\Enums\ChatAction;
use NaggaDIM\LaravelMaxBot\Enums\UpdateType;
use NaggaDIM\LaravelMaxBot\Exceptions\APIException;
use NaggaDIM\LaravelMaxBot\Exceptions\InvalidArgumentException;
use NaggaDIM\LaravelMaxBot\Exceptions\MaxBotException;

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
    public function put(
        string $path = '/',
        array|JsonSerializable|Arrayable $data = [],
        null|array|string $query = null,
        null|array $headers = null
    ): Response|PromiseInterface;

    /**
     * @throws MaxBotException
     * @throws ConnectionException
     */
    public function patch(
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
     * @throws MaxBotException
     * @throws ConnectionException
     */
    public function getMe(): BotInfo;

    /**
     * @param string|null $name
     * @param string|null $description
     * @param BotCommand[]|Collection<BotCommand>|null $commands
     * @return BotInfo
     * @throws APIException
     * @throws ConnectionException
     * @throws InvalidArgumentException
     * @throws MaxBotException
     */
    public function editMe(?string $name = null, ?string $description = null, array|Collection|null $commands = null): BotInfo;

    /**
     * @param BotCommand[]|Collection<BotCommand> $commands
     * @throws MaxBotException
     * @throws ConnectionException
     * @throws APIException
     * @throws InvalidArgumentException
     */
    public function setMeCommands(array|Collection $commands): BotInfo;

    /**
     * @throws MaxBotException
     * @throws ConnectionException
     * @throws APIException
     * @throws InvalidArgumentException
     */
    public function deleteMeCommands(): BotInfo;

    /**
     * @throws MaxBotException
     * @throws ConnectionException
     */
    public function getChats(int $count = 50, ?int $marker = null): GetChatsResponse;

    /**
     * @throws MaxBotException
     * @throws ConnectionException
     */
    public function getChat(int $chatID): Chat;

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
    ): Chat;

    /**
     * @throws MaxBotException
     * @throws ConnectionException
     */
    public function editChatIcon(int $chatID, Image $icon, bool $notify = true): Chat;

    /**
     * @throws MaxBotException
     * @throws ConnectionException
     */
    public function editChatTitle(int $chatID, string $title, bool $notify = true): Chat;

    /**
     * @throws MaxBotException
     * @throws ConnectionException
     */
    public function editChatPin(int $chatID, string $pin, bool $notify = true): Chat;

    /**
     * @throws MaxBotException
     * @throws ConnectionException
     * @throws APIException
     */
    public function deleteChat(int $chatID): bool;

    /**
     * @throws MaxBotException
     * @throws ConnectionException
     * @throws APIException
     */
    public function setChatAction(int $chatID, ChatAction $action): bool;

    /**
     * @throws MaxBotException
     * @throws ConnectionException
     */
    public function getChatPin(int $chatID): ?MessageDTO;

    /**
     * @throws MaxBotException
     * @throws ConnectionException
     * @throws APIException
     */
    public function setChatPin(int $chatID, string $messageID, bool $notify = true): bool;

    /**
     * @throws MaxBotException
     * @throws ConnectionException
     * @throws APIException
     */
    public function deleteChatPin(int $chatID): bool;

    /**
     * @throws MaxBotException
     * @throws ConnectionException
     */
    public function getMeMemberInChat(int $chatID): ChatMember;

    /**
     * @throws MaxBotException
     * @throws APIException
     * @throws ConnectionException
     */
    public function deleteMeMemberFromChat(int $chatID): bool;

    /**
     * @throws MaxBotException
     * @throws ConnectionException
     */
    public function getChatAdmins(int $chatID, int $count = 20, ?int $marker = null): GetChatMembersResponse;

    /**
     * @param null|array<int> $userIds
     * @throws MaxBotException
     * @throws ConnectionException
     */
    public function getChatMembers(int $chatID, int $count = 20, ?int $marker = null, ?array $userIds = null): GetChatMembersResponse;

    /**
     * @param array<int> $userIds
     * @throws MaxBotException
     * @throws ConnectionException
     * @throws APIException
     */
    public function addChatMembers(int $chatID, array $userIds): bool;

    /**
     * @throws MaxBotException
     * @throws ConnectionException
     * @throws APIException
     */
    public function addChatMember(int $chatID, int $userID): bool;

    /**
     * @throws MaxBotException
     * @throws ConnectionException
     * @throws APIException
     */
    public function deleteChatMember(int $chatID, int $userID, ?bool $block = null): bool;

    /**
     * Get Messages by ChatID or MessageIDs
     * @param null|int $chatID
     * @param null|array<string>|Collection<string> $messageIDs
     * @param null|int $from
     * @param null|int $to
     * @param null|int $count
     *
     * @return Collection<MessageDTO>
     *
     * @throws MaxBotException
     * @throws InvalidArgumentException
     * @throws ConnectionException
     *
     * @doc https://dev.max.ru/docs-api/methods/GET/messages
     */
    public function getMessages(
        null|int $chatID = null,
        null|array|Collection $messageIDs = null,
        null|int $from = null,
        null|int $to = null,
        null|int $count = null,
    ): Collection;

    /**
     * Get Message
     * @param string $messageID
     * @return MessageDTO
     *
     * @throws ConnectionException
     * @throws MaxBotException
     *
     * @doc https://dev.max.ru/docs-api/methods/GET/messages/-messageId-
     */
    public function getMessage(string $messageID): MessageDTO;

    /**
     * Send message to user or chat
     * @param Message $message
     * @param int|null $userID
     * @param int|null $chatID
     * @param bool|null $disableLinkPreview
     * @return MessageDTO
     *
     * @doc https://dev.max.ru/docs-api/methods/POST/messages
     *
     * @throws MaxBotException
     * @throws InvalidArgumentException
     * @throws ConnectionException
     */
    public function sendMessage(Message $message, int|null $userID = null, null|int $chatID = null, null|bool $disableLinkPreview = null): MessageDTO;

    /**
     * Send message to user
     *
     * @param int $userID
     * @param Message $message
     * @param bool|null $disableLinkPreview
     * @return MessageDTO
     *
     * @throws ConnectionException
     * @throws InvalidArgumentException
     * @throws MaxBotException
     */
    public function sendMessageToUser(int $userID, Message $message, null|bool $disableLinkPreview = null): MessageDTO;

    /**
     * Send message to chat
     *
     * @param int $chatID
     * @param Message $message
     * @param bool|null $disableLinkPreview
     * @return MessageDTO
     *
     * @throws ConnectionException
     * @throws InvalidArgumentException
     * @throws MaxBotException
     */
    public function sendMessageToChat(int $chatID, Message $message, null|bool $disableLinkPreview = null): MessageDTO;

    /**
     * Edit message
     * @param string $messageID
     * @param Message $message
     * @return bool
     *
     * @throws APIException
     * @throws ConnectionException
     * @throws MaxBotException
     *
     * @doc https://dev.max.ru/docs-api/methods/PUT/messages
     */
    public function editMessage(string $messageID, Message $message): bool;

    /**
     * Delete message
     * @param string $messageID
     * @return bool
     *
     * @throws APIException
     * @throws ConnectionException
     * @throws MaxBotException
     *
     * @doc https://dev.max.ru/docs-api/methods/DELETE/messages
     */
    public function deleteMessage(string $messageID): bool;

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
     * @throws ConnectionException
     * @throws InvalidArgumentException
     */
    public function answerToCallback(string $callbackID, ?Message $message = null, ?string $notification = null): bool;
}