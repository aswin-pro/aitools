<?php

namespace App\Models;

use App\Models\ChatGenius;
use App\Models\ChatMessage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Chat extends Model
{
    use HasFactory;

    protected $table = 'chats';

    protected $fillable = [
        'chat_id',
        'generate_by',
        'chat_genius_id',
        'chat_title',
        'word_count',
        'status',
        'created_at',
        'updated_at',
    ];
}
