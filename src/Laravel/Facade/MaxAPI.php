<?php

namespace NaggaDIM\LaravelMaxBot\Laravel\Facade;

use GuzzleHttp\Promise\PromiseInterface;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Facade;
use JsonSerializable;
use NaggaDIM\LaravelMaxBot\API\DTO\Chat;
use NaggaDIM\LaravelMaxBot\API\DTO\Image;
use NaggaDIM\LaravelMaxBot\API\DTO\Subscription;
use NaggaDIM\LaravelMaxBot\API\DTO\User\BotInfo;
use NaggaDIM\LaravelMaxBot\API\DTO\Message as MessageDTO;
use NaggaDIM\LaravelMaxBot\API\DTO\User\ChatMember;
use NaggaDIM\LaravelMaxBot\API\Helpers\Message;
use NaggaDIM\LaravelMaxBot\API\IMaxAPI;
use NaggaDIM\LaravelMaxBot\API\Responses\GetChatMembersResponse;
use NaggaDIM\LaravelMaxBot\API\Responses\GetChatsResponse;
use NaggaDIM\LaravelMaxBot\Enums\ChatAction;
use NaggaDIM\LaravelMaxBot\Enums\UpdateType;

/**
 * @method static Response|PromiseInterface get(string $path = '/', array|string|null $query = null, array|null $headers = null)
 * @method static Response|PromiseInterface post(string $path = '/', array|JsonSerializable|Arrayable $data = [], array|string|null $query = null, array|null $headers = null)
 * @method static Response|PromiseInterface put(string $path = '/', array|JsonSerializable|Arrayable $data = [], array|string|null $query = null, array|null $headers = null)
 * @method static Response|PromiseInterface patch(string $path = '/', array|JsonSerializable|Arrayable $data = [], array|string|null $query = null, array|null $headers = null)
 * @method static Response|PromiseInterface delete(string $path = '/', array|JsonSerializable|Arrayable $data = [], array|string|null $query = null, array|null $headers = null)
 *
 * @method static Collection<Subscription> getSubscriptions()
 * @method static bool addSubscription(string $url, null|UpdateType[] $updateTypes = null, string|null $secret = null)
 * @method static bool deleteSubscription(string $url)
 *
 * @method static BotInfo getMe()
 * @method static GetChatsResponse getChats(int $count = 50, null|int $marker = null)
 * @method static Chat getChat(int $chatID)
 * @method static Chat editChat(int $chatID, null|Image $icon = null, null|string $title = null, null|string $pin = null, bool $notify = true)
 * @method static Chat editChatIcon(int $chatID, Image $icon, bool $notify = true)
 * @method static Chat editChatTitle(int $chatID, string $title, bool $notify = true)
 * @method static Chat editChatPin(int $chatID, string $pin, bool $notify = true)
 * @method static bool deleteChat(int $chatID)
 * @method static bool setChatAction(int $chatID, ChatAction $action)
 * @method static MessageDTO getChatPin(int $chatID)
 * @method static bool setChatPin(int $chatID, string $messageID, bool $notify = true)
 * @method static bool deleteChatPin(int $chatID)
 *
 * @method static ChatMember getMeMemberInChat(int $chatID)
 * @method static bool deleteMeMemberFromChat(int $chatID)
 * @method static GetChatMembersResponse getChatAdmins(int $chatID, int $count = 20, ?int $marker = null)
 * @method static GetChatMembersResponse getChatMembers(int $chatID, int $count = 20, ?int $marker = null, null|array $userIds = null)
 * @method static bool addChatMembers(int $chatID, int[] $userIds)
 * @method static bool addChatMember(int $chatID, int $userID)
 * @method static bool deleteChatMember(int $chatID, int $userID, null|bool $block = null)
 *
 * @method static array getUpdates(int $limit = 100, int $timeout = 30, null|int $marker = null, null|array $types = null)
 *
 * @method static bool sendMessageToUser(int $userID, Message $message)
 * @method static bool sendMessageToChat(int $chatID, Message $message)
 * @method static bool answerToCallback(string $callbackID, null|Message $message = null, null|string $notification = null)
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