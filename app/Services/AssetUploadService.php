<?php
namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class AssetUploadService
{
    public function uploadAsset(string $fileName, UploadedFile | string $content, string $fileType): string
    {
        // Directory
        $directory = match ($fileType) {
            'audio'    => 'generated-audios',
            'image'    => 'generated-images',
            'document' => 'documents',
            default    => throw new \InvalidArgumentException('Invalid file type'),
        };

        // Path
        $path = "{$directory}/{$fileName}";

        // Upload file or raw content
        if ($content instanceof UploadedFile) {
            Storage::disk('public')->putFileAs($directory, $content, $fileName);
        } else {
            Storage::disk('public')->put($path, $content, [
                'visibility' => 'public',
            ]);
        }

        // return url
        return Storage::url($path);
    }
}
