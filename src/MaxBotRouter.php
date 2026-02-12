<?php

namespace NaggaDIM\LaravelMaxBot;


use NaggaDIM\LaravelMaxBot\Enums\UpdateType;
use NaggaDIM\LaravelMaxBot\Exceptions\InvalidArgumentException;
use NaggaDIM\LaravelMaxBot\Helpers\UpdateHelper;
use NaggaDIM\LaravelMaxBot\Listeners\CallbackListener;
use NaggaDIM\LaravelMaxBot\Listeners\CommandListener;
use NaggaDIM\LaravelMaxBot\Listeners\UpdateListener;

class MaxBotRouter
{
    protected array $updates = [];
    protected array $commands = [];
    protected array $callbacks = [];

    public function __construct() { }

    public static function bootstrap(): static
    {
        $router = new static();

        if(file_exists(base_path('routes/maxbot.php'))) {
            include base_path('routes/maxbot.php');
        }
        return $router;
    }

    /**
     * @throws InvalidArgumentException
     */
    public function registerUpdate(string|UpdateListener $update): void
    {
        if(is_string($update)) {
            if(!is_subclass_of($update, UpdateListener::class)) {
                throw new InvalidArgumentException(sprintf(
                    'You must provide subclass of the %s class or an instance.',
                    UpdateListener::class
                ));
            }
            $update = new $update;
        }

        if(!isset($this->updates[$update->getUpdateType()->value])) {
            $this->updates[$update->getUpdateType()->value] = [];
        }

        $this->updates[$update->getUpdateType()->value][] = $update::class;
        $this->updates[$update->getUpdateType()->value] = array_unique($this->updates[$update->getUpdateType()->value]);
    }

    /**
     * @throws InvalidArgumentException
     */
    public function registerCommand(string|CommandListener $command): void
    {
        if(is_string($command)) {
            if(!is_subclass_of($command, CommandListener::class)) {
                throw new InvalidArgumentException(sprintf(
                    'You must provide subclass of the %s class or an instance.',
                    CommandListener::class
                ));
            }
            $command = new $command;
        }
        
        $this->commands[$command->getCommandPart()] = $command::class;
    }

    /**
     * @throws InvalidArgumentException
     */
    public function registerCallback(string|CallbackListener $callback): void
    {
        if(is_string($callback)) {
            if(!is_subclass_of($callback, CallbackListener::class)) {
                throw new InvalidArgumentException(sprintf(
                    'You must provide subclass of the %s class or an instance.',
                    CommandListener::class
                ));
            }
            $callback = new $callback;
        }

        $this->callbacks[$callback->getCallback()] = $callback::class;
    }

    public function dispatch(array $update): void
    {
        $updateType = $update['update_type'] ?? 'undefined';
        if(!empty($this->updates[$updateType] ?? [])) {
            foreach($this->updates[$updateType] as $updateListener) {
                (new $updateListener)
                    ->run($update);
            }
        }

        if($updateType === UpdateType::MESSAGE_CREATED->value) {
            $messageText = UpdateHelper::getMessageText($update) ?? '';
            if(str_starts_with($messageText, '/')) {
                $commandPart = strtolower(trim(explode(' ', $messageText)[0]));
                if(isset($this->commands[$commandPart])) {
                    (new $this->commands[$commandPart])
                        ->run($update);
                }
            }
        }

        if($updateType === UpdateType::MESSAGE_CALLBACK->value) {
            $callback = strtolower(trim(explode(':', UpdateHelper::getCallbackPayload($update) ?? '')[0]));
            if(isset($this->callbacks[$callback])) {
                if(isset($this->callbacks[$callback])) {
                    (new $this->callbacks[$callback])
                        ->run($update);
                }
            }
        }
    }
}