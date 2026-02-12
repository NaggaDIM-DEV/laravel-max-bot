<?php

namespace NaggaDIM\LaravelMaxBot;

use Exception;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use NaggaDIM\LaravelMaxBot\Enums\Mode;
use NaggaDIM\LaravelMaxBot\Enums\UpdateType;
use NaggaDIM\LaravelMaxBot\Helpers\UpdateHelper;
use NaggaDIM\LaravelMaxBot\Laravel\Facade\MaxAPI;

class LaravelMaxBot implements ILaravelMaxBot
{
    protected bool $debug;

    protected int|null $longPollingLimit = null;

    protected int|null $longPollingTimeout = null;

    protected MaxBotRouter $router;

    public function __construct()
    {
        $this->debug = boolval(Config::get('maxbot.debug', false));
        $this->longPollingLimit = Config::get('maxbot.long-polling.limit');
        $this->longPollingTimeout = Config::get('maxbot.long-polling.timeout');
        $this->router = app(MaxBotRouter::class);
    }

    /**
     * @param array<UpdateType>|null $allowedUpdates
     * @param Mode $mode
     * @return void
     */
    public function start(?array $allowedUpdates = null, Mode $mode = Mode::AUTO_DETECT): void
    {
        if($mode === Mode::AUTO_DETECT) {
            $mode = php_sapi_name() === 'cli' || app()->runningInConsole()
                ? Mode::LONG_POLLING
                : Mode::WEBHOOKS;
        }

        if($mode === Mode::LONG_POLLING) {
            $this->longPolling($allowedUpdates);
        }

        else { $this->webhooks(); }
    }

    /**
     * @param UpdateType[]|null $allowedUpdates
     * @return void
     */
    protected function longPolling(null|array $allowedUpdates = null): void
    {
        $lastUpdateMarker = Cache::get('laravel-max-bot::last-update-marker');
        if($this->debug) {
            echo "------------------------------------------------------\n";
            echo "MaxBot started on long polling mode\n";
            echo "------------------------------------------------------\n";
            echo "marker: $lastUpdateMarker\n";
            echo "------------------------------------------------------\n";
        }

        while(true) {
            try {
                $response = MaxAPI::getUpdates(
                    limit: $this->longPollingLimit ?? 100,
                    timeout: $this->longPollingTimeout ?? 30,
                    marker: $lastUpdateMarker,
                    types: $allowedUpdates,
                );

                if(!empty($response['marker'])) {
                    $lastUpdateMarker = $response['marker'];
                    Cache::set('laravel-max-bot::last-update-marker', $lastUpdateMarker);
                }

                foreach($response['updates'] as $update) {
                    if($this->debug) {
                        echo "\n\n|----------------------------------------------------|\n";
                        echo "|-----------------------UPDATE-----------------------|\n";
                        echo "|----------------------------------------------------|\n";
                        echo "Type: " . ($update['update_type'] ?? 'undefined') . "\n";
                        echo "Time: " . ($update['timestamp'] ?? 'undefined') . "\n";
                        echo "------------------------CONTENT-----------------------\n";
                        echo json_encode($update, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
                        echo "------------------------------------------------------\n";
                    }

                    $this->handleUpdate($update);
                }

                sleep(1);
            }
            catch(Exception $e) { sleep(5); }
        }
    }

    protected function webhooks(): void
    {
        $update = request()->toArray() ?? json_decode(file_get_contents('php://input'), true);
        abort_if(empty($update), 400, "Invalid JSON in webhook request");

        $this->handleUpdate($update);
    }

    protected function handleUpdate(array $update): void
    {
        $cacheKey = 'laravel-max-bot::update::' . UpdateHelper::getUpdateVerifyHash($update);
        if(Cache::has($cacheKey)) { return; }

        Cache::set($cacheKey, true, ttl: now()->addHours(12));
        $this->router->dispatch($update);
    }
}