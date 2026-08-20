<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use HasFactory;

    static $ACTIVE  = 1;
    static $DELETED = 0;

    // active plans
    public static function activePlans() {
        return self::where('status', self::$ACTIVE)->where('is_private', 0)->get()->map(function ($plan) {
            // decode templates
            $plan->templates = json_decode($plan->templates, true);

            // return plan
            return $plan;
        });
    }
}
