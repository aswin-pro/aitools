<?php

namespace Plugins\GoogleAdSense\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Setting;
use Inertia\Inertia;

class GoogleAdSenseController extends Controller
{
    public function googleAdSenseSettings(Request $request)
    {
        $settings = Setting::where('id', 1)->first();

        return Inertia::render('admin/plugins/google-adsense', [
            'settings' => $settings,
        ]);
    }

    public function googleAdSenseSettingsUpdate(Request $request)
    {
        Setting::where('id', 1)->update([
            'adsense_code' => $request->adsense_code,
        ]);

        return redirect()
            ->route('admin.plugin.google_adsense.settings')
            ->with('success', __('Google AdSense Settings Updated Successfully!'));
    }
}
