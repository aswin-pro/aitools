<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Plan extends Model
{
    use HasFactory;

    static $ACTIVE = 1;
    static $DELETED = 0;

    protected $fillable = [
        'plan_id',
        'is_private',
        'name',
        'description',
        'price',
        'validity',
        'content_templates',
        'ai_credits',
        'ai_image_credits',
        'speech_to_text',
        'text_to_speech',
        'code_generator',
        'personalized_chat',
        'document_analyzer',
        'site_analyzer',
        'is_recommended',
        'customer_support',
        'status',
    ];

    protected $casts = [
        'is_private' => 'boolean',
        'content_templates' => 'array',
        'speech_to_text' => 'boolean',
        'text_to_speech' => 'boolean',
        'code_generator' => 'boolean',
        'personalized_chat' => 'boolean',
        'document_analyzer' => 'boolean',
        'site_analyzer' => 'boolean',
        'is_recommended' => 'boolean',
        'customer_support' => 'boolean',
        'status' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::creating(function ($plan) {
            $plan->plan_id ??= (string) Str::uuid();
        });
    }

    public static function activePlans()
    {
        return self::where('status', self::$ACTIVE)
            ->where('is_private', 0)
            ->get();
    }
}