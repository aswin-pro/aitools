<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class UserUpload extends Model
{
    protected $table = 'user_uploads';

    protected $fillable = [
        'upload_id',
        'user_id',
        'file_name',
        'file_type',
        'file_url',
        'file_size',
    ];

    // get uploads
    public static function getUploads(?int $userId = null): Collection
    {
        return self::query()
            ->when($userId, fn($query) => $query->where('user_id', $userId))
            ->latest()
            ->get();
    }
}
