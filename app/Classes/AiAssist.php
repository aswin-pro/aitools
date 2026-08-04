<?php

namespace App\Classes;

use App\Models\Config;
use Illuminate\Support\Facades\Log;

class AiAssist
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

        $isSystem = isset($request['system']);
        $question = $isSystem ? ($request['message'] ?? '') : ($request->message ?? '');

        if ($isSystem) {
            $messages = [['role' => 'system', 'content' => $question]];
        } else {
            $defaultMessage = "Your name is File Analyzer (personal name) and you're a content reader. Your job is to read a large document, understand it, then answer only questions related to that content. Don't say anything apart from the document content.";

            $messages = [['role' => 'system', 'content' => $defaultMessage]];

            if (isset($request["all_conversations"])) {
                foreach ($request["all_conversations"] as $conversation) {
                    // Map stored value to a valid OpenAI role explicitly —
                    // don't trust the DB column to already contain "user"/"assistant"
                    $role = ($conversation->responsed_by === 'user') ? 'user' : 'assistant';
                    $messages[] = ['role' => $role, 'content' => $conversation->chat_message];
                }
            }

            if (isset($request["content"])) {
                $messages[] = ['role' => 'user', 'content' => "Document Content: " . $request["content"]];
            }

            $messages[] = ['role' => 'user', 'content' => $question];
        }

        if (!in_array($model, self::CHAT_MODELS)) {
            Log::error("AiAssist: model '{$model}' is not in the supported chat-models list.");
            return null;
        }

        $data = [
            'model' => $model,
            'messages' => $messages,
        ];

        try {
            $response = $this->callOpenAiApi("https://api.openai.com/v1/chat/completions", $openAiKey, $data);
        } catch (\Throwable $e) {
            Log::error("AiAssist: OpenAI call failed: " . $e->getMessage());
            return null;
        }

        if ($response === null) {
            Log::error("AiAssist: received unparseable response from OpenAI.");
            return null;
        }

        if (isset($response->error)) {
            Log::error("AiAssist: OpenAI API error: " . json_encode($response->error));
            $this->result = null;
        } elseif (isset($response->choices[0]->message->content)) {
            $this->result = $response->choices[0]->message->content;
        } else {
            Log::error("AiAssist: unexpected response shape: " . json_encode($response));
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
