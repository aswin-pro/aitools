<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatMessage extends Model
{
    use HasFactory;

    // static statuses
    public const RESPONSED_BY_USER   = 'user';
    public const RESPONSED_BY_SYSTEM = 'system';

    protected $fillable = [
        'chat_message_id',
        'chat_id',
        'responsed_by',
        'chat_message',
        'status',
        'created_at',
        'updated_at',
    ];

    // get messages
    public static function getMessages(string $chatId): Collection
    {
        // return messages
        return self::query()
            ->where('chat_id', $chatId)
            ->select(['id', 'chat_message_id', 'chat_id', 'responsed_by', 'chat_message', 'created_at'])
            ->get();
    }

    // get past conversations
    public static function getPastConversations(string $chatId): Collection
    {
        // return messages
        return self::query()
            ->where('chat_id', $chatId)->latest()->take(10)->get()->reverse()->values();
    }
}
