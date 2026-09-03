<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'unique_slug',
        'name',
        'description',
        'prompt',
        'status',
    ];
    protected $casts = [
        'status' => 'boolean',
    ];
}
