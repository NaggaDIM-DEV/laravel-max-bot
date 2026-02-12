# LaravelMaxBot

PHP библиотека для создания ботов в мессенджере MAX на Laravel. 
Поддерживает полное API MAX messenger и предоставляет удобный интерфейс для разработки ботов.

## Особенности

- Простой и интуитивно понятный API
- Поддержка webhook и long polling режимов
- Полная поддержка MAX Bot API
- Встроенные помощники для создания клавиатур и кнопок
- Обработка команд, событий и callback-действий
- Поддержка регулярных выражений для обработчиков
- Обработка исключений и ошибок API
- PSR-4 автозагрузка

## Требования

- PHP >= 8.2
- Laravel >= 12

## Установка

### Через Composer

```bash
composer require naggadim-dev/laravel-max-bot
```

```bash
php artisan vendor:publish --tag=laravel-max-bot
```

## Настройка
В файле config/maxbot.php указать требуемые параметры
```php
<?php

return [
    'debug' => false,
    'api_url' => 'https://platform-api.max.ru',
    'token' => env('MAX_MESSENGER_BOT_TOKEN'),
    'long-polling'  => [
        'limit' => 100,
        'timeout' => 30,
    ]
];
```

или достаточно в .env указать значение MAX_MESSENGER_BOT_TOKEN

## Быстрый старт

Зарегистриуйте обработчики ваших событий в routes/maxbot.php
```php
<?php

use App\MaxBot\Listeners\Update\MessageCreatedListener;
use App\MaxBot\Listeners\Command\StartCommandListener;
use App\MaxBot\Listeners\Callback\CallbackExampleListener;

use NaggaDIM\LaravelMaxBot\MaxBotRouter;
/** @var MaxBotRouter $router */

/*
|--------------------------------------------------------------------------
| MaxBot Handlers
|--------------------------------------------------------------------------
*/

$router->registerUpdate(MessageCreatedListener::class);
$router->registerCommand(StartCommandListener::class);
$router->registerCallback(CallbackExampleListener::class);
```

## Запуск бота
```bash
php artisan max:start-polling
```
для запуска в режиме Long Polling

или укажите в настройках вашего бота в мессенджере Max ссылку вида
```
https://<you-domain>/max-messenger/webhook
```
для работы в режиме Webhook (Для Webhook режима Max поддерживает только ссылки https)

## Лицензия

MIT

## Автор

NaggaDIM <naggadim@vivladi.net>

## Ссылки

- [MAX Messenger](https://max.ru/)
- [MAX Bot API Documentation](https://platform-api.max.ru/docs/)

## Поддержка
Пакет всё ещё находится в разработке и будет активно дорабатываться.
Если у вас возникли проблемы или вопросы, создайте issue на GitHub.
