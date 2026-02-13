<?php

namespace NaggaDIM\LaravelMaxBot\Enums;

enum UploadType: string
{
    case IMAGE = 'image';
    case VIDEO = 'video';
    case AUDIO = 'audio';
    case FILE = 'file';

    public function supportedFormats(): string
    {
        return match ($this) {
            self::IMAGE => 'JPG,JPEG,PNG,GIF,TIFF,BMP,HEIC',
            self::VIDEO => 'MP4,MOV,MKV,WEBM,MATROSKA',
            self::AUDIO => 'MP3,WAV,M4A',
            self::FILE => '*',
        };
    }
}
