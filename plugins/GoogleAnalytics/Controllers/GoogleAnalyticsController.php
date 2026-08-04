<?php

namespace Plugins\GoogleAnalytics\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class GoogleAnalyticsController extends Controller
{
    public function googleAnalyticsSettings(Request $request)
    {
        $settings = Setting::where('id', 1)->first();

        return view()->file(base_path('plugins/GoogleAnalytics/Views/index.blade.php'), compact('settings'));
    }

    public function googleAnalyticsSettingsUpdate(Request $request)
    {
        Setting::where('id', '1')->update([
            'analytics_id' => $request->analytics_id,
            'google_tag' => $request->google_tag,
        ]);

        return redirect()->route('admin.plugin.google_analytics.settings')->with('success', __('Google OAuth Settings Updated Successfully!'));
    }

}
