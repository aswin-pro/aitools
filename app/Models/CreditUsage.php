<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CreditUsage extends Model
{
    protected $fillable = [
        'user_id',
        'plan_ai_credits',
        'purchased_ai_credits',
        'plan_ai_image_credits',
        'purchased_ai_image_credits',
    ];
}
