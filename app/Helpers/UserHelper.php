<?php

use App\Models\Config;
use App\Models\CreditUsage;
use App\Models\UserUpload;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

// return user plan
if (! function_exists('userPlan')) {
    function userPlan()
    {
        // return plan
        return json_decode(Auth::user()->plan_details ?? '{}');
    }
}

// return user plan content templates
if (! function_exists('userPlanContentTemplates')) {
    function userPlanContentTemplates()
    {
        // user plan
        $plan = userPlan();

        // return templates
        return $plan->content_templates ?? '{}';
    }
}

// get user plan details by field
if (! function_exists('getUserPlanByField')) {
    function getUserPlanByField(string $field)
    {
        // user plan
        $plan = userPlan();

        // return field value
        return $plan?->{ $field} ?? null;
    }
}

// count words in string
if (! function_exists('countWords')) {
    function countWords(string $content): int
    {
        // count words
        preg_match_all(
            '/[\p{L}\p{N}]+(?:[\'’._:-][\p{L}\p{N}]+)*/u',
            $content,
            $matches
        );

        // return count
        return count($matches[0]);
    }
}

// get maximum words length
if (! function_exists('getMaxWordsLength')) {
    function getMaxWordsLength()
    {
        // return max words length
        return Config::where('config_key', 'share_content')->first()->config_value ?? 500;
    }
}

// Get AI provider configuration
if (! function_exists('getAiProviderConfig')) {
    function getAiProviderConfig(string $type): array
    {
        return match ($type) {
            'content_generator'        => [
                'provider' => 'openai',
                'model'    => 'gpt-4o-mini',
            ],

            'image_generator'          => [
                'provider' => 'openai',
                'model'    => 'gpt-image-2',
            ],

            'code_generator'           => [
                'provider' => 'deepseek',
                'model'    => 'deepseek-chat',
            ],

            'speech_to_text_converter' => [
                'provider' => 'openai',
                'model'    => 'gpt-4o-mini-transcribe',
            ],

            'text_to_speech_converter' => [
                'provider' => 'openai',
                'model'    => 'gpt-4o-mini-tts',
            ],

            'personalized_chat'        => [
                'provider' => 'openai',
                'model'    => 'gpt-4o-mini',
            ],

            'document_analyzer'        => [
                'provider' => 'openai',
                'model'    => 'gpt-4o-mini',
            ],

            'site_analyzer'            => [
                'provider' => 'openai',
                'model'    => 'gpt-5',
            ],

            default                    => [
                'provider' => 'openai',
                'model'    => 'gpt-5-mini',
            ],
        };
    }
}

// get used word credits
if (! function_exists('creditsUsage')) {
    function creditsUsage()
    {
        // credits
        $credits = CreditUsage::where('user_id', Auth::id())
            ->get([
                'plan_ai_credits',
                'purchased_ai_credits',
                'plan_ai_image_credits',
                'purchased_ai_image_credits',
            ]);

        // ai credits
        $aiCredits = $credits->pluck('plan_ai_credits')
            ->merge($credits->pluck('purchased_ai_credits'));

        // ai image credits
        $aiImageCredits = $credits->pluck('plan_ai_image_credits')
            ->merge($credits->pluck('purchased_ai_image_credits'));

        return [
            'ai_credits'       => [
                'total' => $aiCredits->filter(fn($v) => $v > 0)->sum(),
                'used'  => abs($aiCredits->filter(fn($v) => $v < 0)->sum()),
            ],

            'ai_image_credits' => [
                'total' => $aiImageCredits->filter(fn($v) => $v > 0)->sum(),
                'used'  => abs($aiImageCredits->filter(fn($v) => $v < 0)->sum()),
            ],
        ];
    }
}

// has exceeded ai credits
if (! function_exists('hasExceededCredits')) {
    function hasExceededCredits(string $type): bool
    {
        // credits
        $credits = creditsUsage();

        // content
        if ($type === 'content' ||
            $type === 'code' ||
            $type === 'speech_to_text' ||
            $type === 'text_to_speech' ||
            $type === 'personalized_chat' ||
            $type === 'document_analyzer' ||
            $type === 'site_analyzer'
        ) {
            // return bool
            return $credits['ai_credits']['used'] >= $credits['ai_credits']['total'];
        }

        // image
        if ($type === 'image') {
            return $credits['ai_image_credits']['used'] >= $credits['ai_image_credits']['total'];
        }

        // default false
        return false;
    }
}

// add credits
if (! function_exists('addCredits')) {
    function addCredits(string $type, mixed $plan): void
    {
        // user id
        $userId = Auth::id();

        // add ai credits
        if ($type === 'plan') {
            CreditUsage::create([
                'user_id'               => $userId,
                'plan_ai_credits'       => $plan->ai_credits,
                'plan_ai_image_credits' => $plan->ai_image_credits,
            ]);
        }
    }
}

// reduce credits
if (! function_exists('reduceCredits')) {
    function reduceCredits(string $type, int $count): void
    {
        // user id
        $userId = Auth::id();

        // reduce ai credits
        if ($type === 'ai_credits') {
            // available plan credits
            $availablePlanCredits = CreditUsage::where('user_id', $userId)->sum('plan_ai_credits');

            // available purchased credits
            $availablePurchasedCredits = CreditUsage::where('user_id', $userId)->sum('purchased_ai_credits');

            // initialize deductions
            $planDeduction      = 0;
            $purchasedDeduction = 0;

            // credits
            $wordsPerCredit = (int) getConfigValue('words_per_credit');
            $credits        = (int) floor($count / $wordsPerCredit);

            // Consume plan credits first
            if ($availablePlanCredits > 0) {
                $planDeduction  = min($credits, $availablePlanCredits);
                $credits       -= $planDeduction;
            }

            // Consume purchased credits if tokens remain
            if ($credits > 0 && $availablePurchasedCredits > 0) {
                $purchasedDeduction  = min($credits, $availablePurchasedCredits);
                $credits            -= $purchasedDeduction;
            }

            // Save only if any credits were deducted
            if ($planDeduction > 0 || $purchasedDeduction > 0) {
                CreditUsage::create([
                    'user_id'              => $userId,
                    'plan_ai_credits'      => -$planDeduction,
                    'purchased_ai_credits' => -$purchasedDeduction,
                ]);
            }
        } else if ($type === 'ai_image_credits') {
            // available plan credits
            $availablePlanCredits = CreditUsage::where('user_id', $userId)->sum('plan_ai_image_credits');

            // available purchased credits
            $availablePurchasedCredits = CreditUsage::where('user_id', $userId)->sum('purchased_ai_image_credits');

            // initialize deductions
            $planDeduction      = 0;
            $purchasedDeduction = 0;

            // Consume plan credits first
            if ($availablePlanCredits > 0) {
                $planDeduction  = min($count, $availablePlanCredits);
                $count         -= $planDeduction;
            }

            // Consume purchased credits if tokens remain
            if ($count > 0 && $availablePurchasedCredits > 0) {
                $purchasedDeduction  = min($count, $availablePurchasedCredits);
                $count              -= $purchasedDeduction;
            }

            // Save only if any credits were deducted
            if ($planDeduction > 0 || $purchasedDeduction > 0) {
                CreditUsage::create([
                    'user_id'                    => $userId,
                    'plan_ai_image_credits'      => -$planDeduction,
                    'purchased_ai_image_credits' => -$purchasedDeduction,
                ]);
            }
        }
    }
}

// Has feature access on current plan
if (! function_exists('hasFutureOnPlan')) {
    function hasFutureOnPlan(string $page): bool
    {
        return match ($page) {
            'code-generator' => (int) (getUserPlanByField('code_generator') ?? 0) === 1,
            'speech-to-text' => (int) (getUserPlanByField('speech_to_text') ?? 0) === 1,
            'text-to-speech' => (int) (getUserPlanByField('text_to_speech') ?? 0) === 1,
            'personalized-chat' => (int) (getUserPlanByField('personalized_chat') ?? 0) === 1,
            'document-analyzer' => (int) (getUserPlanByField('document_analyzer') ?? 0) === 1,
            'site-analyzer' => (int) (getUserPlanByField('site_analyzer') ?? 0) === 1,
            default          => false,
        };
    }
}

// has plan validity
if (! function_exists('hasPlanValidity')) {
    function hasPlanValidity(): bool
    {
        if (! Auth::check() || empty(Auth::user()->plan_validity)) {
            return false;
        }

        return Carbon::parse(Auth::user()->plan_validity)->isFuture();
    }
}

// get user used storage
if (! function_exists('getUsedStorage')) {
    function getUsedStorage()
    {
        // user id
        $userId = Auth::id();

        // return size
        return UserUpload::where('user_id', $userId)->sum('file_size');
    }
}

// has exceeded upload limit
if (! function_exists('hasExceededUploadLimit')) {
    function hasExceededUploadLimit(int $size): bool
    {
        // used storage
        $usedStorage = getUsedStorage();

        // Total
        $totalStorage = (int) $usedStorage + (int) $size;
        $totalStorage = round($totalStorage / 1024 / 1024, 2);

        // upload limit
        $uploadLimit = (float) getConfigValue('document_upload_limit');

        // return bool
        return $totalStorage > $uploadLimit;
    }
}
