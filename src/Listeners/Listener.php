<?php

namespace NaggaDIM\LaravelMaxBot\Listeners;

use NaggaDIM\LaravelMaxBot\API\Helpers\Message;
use NaggaDIM\LaravelMaxBot\Helpers\UpdateHelper;
use NaggaDIM\LaravelMaxBot\Laravel\Facade\MaxAPI;

abstract class Listener
{
    protected ?array $currentUpdate;

    public function run(array $update): void
    {
        $this->currentUpdate = $update;
        $this->handle();
    }

    abstract public function handle(): void;

    protected function getUserID(): int|null
    {
        return UpdateHelper::getUserID($this->currentUpdate);
    }

    protected function getCallbackID(): string|null
    {
        return UpdateHelper::getCallbackID($this->currentUpdate);
    }

    protected function getCallbackPayload(): string|null
    {
        return UpdateHelper::getCallbackPayload($this->currentUpdate);
    }

    protected function getMessageID(): null|string
    {
        return UpdateHelper::getMessageID($this->currentUpdate);
    }

    protected function getMessageText(): null|string
    {
        return UpdateHelper::getMessageText($this->currentUpdate);
    }

    protected function sendMessageToUser(int $userID, Message $message): bool
    {
        return MaxAPI::sendMessageToUser($userID, $message);
    }

    protected function sendMessageToChat(int $chatID, Message $message): bool
    {
        return MaxAPI::sendMessageToChat($chatID, $message);
    }

    protected function sendMessage(Message $message): bool
    {
        return $this->sendMessageToUser($this->getUserID(), $message);
    }

    public function answerToCallbackWithCallbackID(string $callbackId, ?Message $message = null, ?string $notification = null): bool
    {
        return MaxAPI::answerToCallback($callbackId, $message, $notification);
    }

    public function answerToCallback(?Message $message = null, ?string $notification = null): bool
    {
        return $this->answerToCallbackWithCallbackID($this->getCallbackID(), $message, $notification);
    }
}