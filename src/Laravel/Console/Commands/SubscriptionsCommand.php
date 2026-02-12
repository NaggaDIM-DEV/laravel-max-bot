<?php

namespace NaggaDIM\LaravelMaxBot\Laravel\Console\Commands;

use Illuminate\Console\Command;
use NaggaDIM\LaravelMaxBot\API\DTO\Subscription;
use NaggaDIM\LaravelMaxBot\Exceptions\MaxBotException;
use NaggaDIM\LaravelMaxBot\Laravel\Facade\MaxAPI;

class SubscriptionsCommand extends Command
{
    protected $signature = 'max:subscriptions';

    protected $description = 'Command description';

    public function handle(): void
    {
        while (true) {
            $choice = $this->choice('Select menu entry', [
                'Get subscriptions',
                'Add subscription',
                'Delete subscription',
                'Exit'
            ], default: 0);

            if($choice == 'Get subscriptions') { $this->getSubscriptions(); }
            if($choice == 'Add subscription') { $this->addSubscription(); }
            if($choice == 'Delete subscription') { $this->deleteSubscription(); }
            if($choice == 'Exit') { return; }
        }
    }

    protected function getSubscriptions(): void
    {
        $subscriptions = MaxAPI::getSubscriptions();
        $this->info('Active subscriptions: ' . $subscriptions->count());
        $this->table(
            ['Webhook URL', 'Update types', 'Created at'],
            $subscriptions
                ->map(fn(Subscription $e) => [
                    $e->url,
                    implode(', ', $e->update_types->map(fn($updateType) => $updateType->value) ?? ['*']),
                    $e->createdAt()->format('Y-m-d H:i:s'),
                ])
                ->toArray()
        );
    }

    protected function addSubscription(): void
    {
        $webhookUri = $this->ask('Webhook URI:', route('max-messenger.webhook'));
        if($this->confirm("Do you want to write subscription webhook with this uri? ($webhookUri)", true)) {
            try {
                MaxAPI::addSubscription($webhookUri);
                $this->info('Webhook URI successfully created');
            }
            catch (MaxBotException $e) { $this->error($e->getMessage()); }
        }
    }

    protected function deleteSubscription(): void
    {
        $webhookUri = $this->ask('Webhook URI:', route('max-messenger.webhook'));
        if($this->confirm("Do you want to delete subscription webhook with this uri? ($webhookUri)", true)) {
            try {
                MaxAPI::deleteSubscription($webhookUri);
                $this->info('Webhook URI successfully deleted');
            }
            catch (MaxBotException $e) { $this->error($e->getMessage()); }
        }
    }
}
