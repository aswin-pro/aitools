<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    use HasFactory;

    // fillable
    protected $fillable = [
        'chat_id',
        'chat_type',
        'attachment',
        'generated_by',
        'chat_assistant_id',
        'chat_title',
        'word_count',
        'status',
        'created_at',
    ];

    // get chats
    public static function getChats(string $assistantId, string $type, ?int $userId = null): Collection
    {
        // return chats
        return self::query()
            ->where('chat_assistant_id', $assistantId)
            ->where('chat_type', $type)
            ->when($userId, fn($query) => $query->where('generated_by', $userId))
            ->select(['id', 'chat_id', 'chat_title', 'attachment'])
            ->get();
    }

    // get data by field
    public static function getDataByField(
        string $field,
        mixed $value,
        ?int $userId = null,
    ): ?self {
        return self::query()
            ->where($field, $value)
            ->when($userId, fn($query) => $query->where('generated_by', $userId))
            ->first();
    }
}
