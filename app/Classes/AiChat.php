<?php

namespace App\Classes;

use App\Models\Config;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AiChat
{
    protected $result;

    private const CHAT_MODELS = [
        "gpt-5.4",
        "gpt-5.4-pro",
        "gpt-5.4-mini",
        "gpt-5.4-nano",
        "gpt-5.3",
        "gpt-5.2",
        "gpt-5.2-pro",
        "gpt-5.1",
        "gpt-5",
        "gpt-5-mini",
        "gpt-5-nano",
        "gpt-5-chat-latest",
        "gpt-5-codex",
        "gpt-5-pro",
        "gpt-4.5-preview",
        "gpt-4.1",
        "gpt-4.1-mini",
        "gpt-4.1-nano",
        "o4-mini",
        "o3",
        "o3-pro",
        "o3-mini",
        "o1",
        "o1-mini",
        "o1-preview",
        "gpt-oss-120b",
        "gpt-oss-20b",
        "gpt-4o",
        "gpt-4o-mini",
        "gpt-4-turbo",
        "gpt-4",
        "gpt-4-32k",
        "gpt-4-vision-preview",
        "gpt-4-1106-preview",
        "gpt-3.5-turbo",
        "gpt-3.5-turbo-16k",
        "gpt-3.5-turbo-1106",
    ];

    public function generate($request)
    {
        $config = Config::all()->keyBy('id');

        $openAiKey = $config[36]->config_value;
        $model = $config[35]->config_value;

        $this->result = null;

        if (isset($request['system'])) {
            $question = $request['message'] ?? '';
            $user = Auth::user();

            if (!$user) {
                Log::error("AiChat: 'system' branch reached with no authenticated user.");
                return null;
            }

            // Keep the actual instruction in 'system', keep raw user input in 'user'
            // so untrusted text can't masquerade as a trusted system instruction.
            $messages = [
                ['role' => 'system', 'content' => 'Greet the user by name, then respond to their message.'],
                ['role' => 'user', 'content' => 'My name is "' . $user->name . '". My message is: "' . $question . '"'],
            ];
        } else {
            $question = $request->message ?? '';
            $messages = [];

            if (isset($request["chatGeniusDetails"])) {
                $messages[] = ['role' => 'system', 'content' => $request["chatGeniusDetails"]->chat_genius_message];
            }

            if (isset($request["all_conversations"])) {
                foreach ($request["all_conversations"] as $conversation) {
                    $role = ($conversation->responsed_by === 'user') ? 'user' : 'assistant';
                    $messages[] = ['role' => $role, 'content' => $conversation->chat_message];
                }
            }

            $messages[] = ['role' => 'user', 'content' => $question];
        }

        if (!in_array($model, self::CHAT_MODELS)) {
            Log::error("AiChat: model '{$model}' is not in the supported chat-models list.");
            return null;
        }

        $data = [
            'model' => $model,
            'messages' => $messages,
        ];

        try {
            $response = $this->callOpenAiApi("https://api.openai.com/v1/chat/completions", $openAiKey, $data);
        } catch (\Throwable $e) {
            Log::error("AiChat: OpenAI call failed: " . $e->getMessage());
            return null;
        }

        if ($response === null) {
            Log::error("AiChat: received unparseable response from OpenAI.");
            return null;
        }

        if (isset($response->error)) {
            Log::error("AiChat: OpenAI API error: " . json_encode($response->error));
            $this->result = null;
        } elseif (isset($response->choices[0]->message->content)) {
            $this->result = $response->choices[0]->message->content;
        } else {
            Log::error("AiChat: unexpected response shape: " . json_encode($response));
            $this->result = null;
        }

        return $this->result;
    }

    private function callOpenAiApi($url, $apiKey, $data)
    {
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30, // seconds, fixed from 30000
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'Authorization: Bearer ' . $apiKey,
            ],
        ]);

        $response = curl_exec($curl);
        $error = curl_error($curl);
        curl_close($curl);

        if ($error) {
            throw new \Exception("cURL Error #:" . $error);
        }

        return json_decode($response);
    }
}
