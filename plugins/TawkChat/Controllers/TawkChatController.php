<?php

namespace Plugins\TawkChat\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Spatie\Color\Validate;

class TawkChatController extends Controller
{
    public function tawkChatSettings(Request $request)
    {
        $settings = Setting::where('id', 1)->first();

        return Inertia::render('admin/plugins/tawk-chat', [
            'settings' => $settings,
        ]);
    }

    public function tawkChatSettingsUpdate(Request $request)
    {
        $validated = $request -> validate([
            'tawk_chat_key' => 'required|string|max:255',
        ]);

        Setting::where('id', 1)->update([
            'tawk_chat_key' => $request['tawk_chat_key'],
        ]);

        return redirect()->route('admin.plugin.tawkchat.settings')->with('success', __('Tawk.to Settings Updated Successfully!'));
    }
}
