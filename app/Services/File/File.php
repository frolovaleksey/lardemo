<?php

namespace App\Services\File;

use Illuminate\Http\UploadedFile;

interface File
{
    public static function getDisk(): string;
    public static function storeUploadedFile(UploadedFile $data, string $folder=''): string;

    public static function delete(string $filePath): bool;
}
