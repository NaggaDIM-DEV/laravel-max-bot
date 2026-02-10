<?php

namespace NaggaDIM\LaravelMaxBot\Listeners;

use NaggaDIM\LaravelMaxBot\Helpers\UpdateHelper;
use NaggaDIM\LaravelMaxBot\Laravel\Facade\MaxAPI;
use NaggaDIM\LaravelMaxBot\Message;

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
        $this->sendMessageToUser($this->getUserID(), $message);
    }
}