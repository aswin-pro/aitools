<?php

namespace Plugins\WhatsAppChatButton\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Config;
use App\Models\Setting;
use Illuminate\Http\Request;

class WhatsAppChatButtonController extends Controller
{
    public function whatsAppChatButtonSettings(Request $request)
    {
        $whatsapp_settings = Config::get();

        $settings = Setting::where('id', 1)->first();

        return view()->file(base_path('plugins/WhatsAppChatButton/Views/index.blade.php'), compact('whatsapp_settings', 'settings'));
    }

    public function whatsAppChatButtonSettingsUpdate(Request $request)
    {
        Config::where('config_key', 'show_whatsapp_chatbot')->update([
            'config_value' => $request->show_whatsapp_chatbot,
        ]);

        Config::where('config_key', 'whatsapp_chatbot_mobile_number')->update([
            'config_value' => $request->whatsapp_chatbot_mobile_number,
        ]);

        Config::where('config_key', 'whatsapp_chatbot_message')->update([
            'config_value' => $request->whatsapp_chatbot_message,
        ]);

        return redirect()->route('admin.plugin.whatsapp_chat_button.settings')->with('success', __('WhatsApp Chat Button Settings Updated Successfully!'));
    }
}
