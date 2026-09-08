<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Plan extends Model
{
    use HasFactory;

    // static statuses
    public const STATUS_ACTIVE  = 1;
    public const STATUS_DELETED = 0;

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
            $plan->plan_id ??= uniqid();
        });
    }

// active plans
    public static function activePlans()
    {
        // currency
        $currency = Config::where('config_key', 'currency')->first()->config_value ?? 'USD';

        // return plans
        return self::where('status', self::STATUS_ACTIVE)->where('is_private', 0)->get()->map(function ($plan) use ($currency) {
            $plan->formatted_price = formatCurrency($plan->price, $currency);
            $plan->content_templates_count = count($plan->content_templates);

            // return plan
            return $plan;
        });
    }
}