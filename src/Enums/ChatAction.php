<?php

namespace NaggaDIM\LaravelMaxBot\Enums;

enum ChatAction: string
{
    case TYPING_ON = 'typing_on'; // Бот набирает сообщение.
    case SENDING_PHOTO = 'sending_photo'; // Бот отправляет фото.
    case SENDING_VIDEO = 'sending_video'; // Бот отправляет видео.
    case SENDING_AUDIO = 'sending_audio'; // Бот отправляет аудиофайл.
    case SENDING_FILE = 'sending_file'; // Бот отправляет файл.
    case MARK_SEEN = 'mark_seen'; // Бот помечает сообщения как прочитанные.
}
