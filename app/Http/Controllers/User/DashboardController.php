<?php
namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Chat;
use App\Models\GeneratedContent;
use App\Models\GeneratedImage;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('user/overview/index', [
            'summary' => fn() => $this->summary(),
            'charts'  => fn()  => $this->charts(),
        ]);
    }

    // summary
    private function summary(): array
    {
        // plan validity
        $planValidity = Carbon::parse(Auth::user()->plan_validity)->startOfDay();
        $today        = now()->startOfDay();

        $status = match (true) {
            $planValidity->equalTo($today)  => 'Your subscription expires today',
            $planValidity->lessThan($today) => 'Your subscription has expired',
            default                         => 'Your subscription expires in ' . $today->diffInDays($planValidity) . ' days',
        };

        // credits usage
        $credits_usage = creditsUsage();

        return [
            'subscription'     => [
                'plan_name' => getUserPlanByField('name'),
                'validity'  => $status,
            ],
            'ai_credits'       => $credits_usage['ai_credits'],
            'ai_image_credits' => $credits_usage['ai_image_credits'],
        ];
    }

    // charts
    private function charts(): array
    {
        $userId = Auth::id();

        // Generated content monthly words
        $generated = GeneratedContent::selectRaw('MONTH(created_at) as month, SUM(word_count) as total')
            ->where('generated_by', $userId)
            ->whereYear('created_at', now()->year)
            ->groupBy('month')
            ->pluck('total', 'month');

        // Chat monthly words
        $chat = Chat::selectRaw('MONTH(created_at) as month, SUM(word_count) as total')
            ->where('generated_by', $userId)
            ->whereYear('created_at', now()->year)
            ->groupBy('month')
            ->pluck('total', 'month');

        $chartData = collect(range(1, 12))->map(function ($month) use ($generated, $chat) {
            return [
                'month'   => Carbon::create()->month($month)->format('F'),
                'ai_credits' => ($generated[$month] ?? 0) + ($chat[$month] ?? 0),
            ];
        })->values()->all();

        // image credits
        $imageCredits = GeneratedImage::selectRaw('MONTH(created_at) as month, SUM(n) as total')
            ->where('generated_by', $userId)
            ->whereYear('created_at', now()->year)
            ->groupBy('month')
            ->pluck('total', 'month');

        $imageChartData = collect(range(1, 12))->map(function ($month) use ($imageCredits) {
            return [
                'month'   => Carbon::create()->month($month)->format('F'),
                'ai_credits' => ($imageCredits[$month] ?? 0),
            ];
        });

        return [
            'ai_credits_chart' => [
                'data' => $chartData,
            ],
            'ai_image_credits_chart' => [
                'data' => $imageChartData,
            ],
        ];
    }
}
