<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatGenius extends Model
{
    use HasFactory;

    protected $table = 'chat_geniuses';

    protected $fillable = [
        'chat_genius_id',
        'chat_genius_image',
        'chat_genius_name',
        'chat_genius_expert',
        'chat_genius_message',
        'chat_created_at',
        'status',
        'created_at',
        'updated_at',
    ];
}
