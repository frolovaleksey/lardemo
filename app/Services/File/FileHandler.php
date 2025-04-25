<?php

namespace App\Services\File;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class FileHandler implements File
{
    public static function getDisk(): string
    {
        return 'public';
    }

    public static function storeUploadedFile(UploadedFile $data, string $folder = ''): string
    {
        return Storage::url($data->store($folder, static::getDisk()));
    }

    public static function delete(?string $filePath): bool
    {
        if ($filePath) {
            return Storage::disk(static::getDisk())->delete(str_replace('storage/', '', $filePath));
        }

        return false;
    }
}
