<?php

namespace Plugins\WhatsAppChatButton\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Config;
use App\Models\Setting;
use Illuminate\Http\Request;
use Inertia\Inertia;

class WhatsAppChatButtonController extends Controller
{
    public function whatsAppChatButtonSettings(Request $request)
    {
        $whatsapp_settings = [
            'show_whatsapp_chatbot' => Config::where(
                'config_key',
                'show_whatsapp_chatbot'
            )->value('config_value'),

            'whatsapp_chatbot_mobile_number' => Config::where(
                'config_key',
                'whatsapp_chatbot_mobile_number'
            )->value('config_value'),

            'whatsapp_chatbot_message' => Config::where(
                'config_key',
                'whatsapp_chatbot_message'
            )->value('config_value'),
        ];

        return Inertia::render(
            'admin/plugins/whatsapp-chat-button',
            compact('whatsapp_settings')
        );
    }

    public function whatsAppChatButtonSettingsUpdate(Request $request)
    {
        $request->validate([
            'show_whatsapp_chatbot' => ['required', 'in:0,1'],
            'whatsapp_chatbot_mobile_number' => [
                'required',
                'string',
                'max:20',
                'regex:/^[0-9]+$/',
            ],
            'whatsapp_chatbot_message' => [
                'required',
                'string',
                'max:500',
            ],
        ]);

        Config::where('config_key', 'show_whatsapp_chatbot')->update([
            'config_value' => $request->show_whatsapp_chatbot,
        ]);

        Config::where('config_key', 'whatsapp_chatbot_mobile_number')->update([
            'config_value' => $request->whatsapp_chatbot_mobile_number,
        ]);

        Config::where('config_key', 'whatsapp_chatbot_message')->update([
            'config_value' => $request->whatsapp_chatbot_message,
        ]);

        return redirect()
            ->route('admin.plugin.whatsapp_chat_button.settings')
            ->with(
                'success',
                __('WhatsApp Chat Button Settings Updated Successfully!')
            );
    }
}
