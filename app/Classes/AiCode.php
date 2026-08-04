<?php

namespace App\Classes;

use App\Models\Config;
use Orhanerday\OpenAi\OpenAi;
use Illuminate\Support\Facades\Log;

class AiCode
{
    public function generate($request)
    {
        $config = Config::get();

        $open_ai_key = $config[35]->config_value;
        $prompt = $request->description;
        $model = $config[34]->config_value;

        $chatModels = [
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

        $codexModels = [
            "gpt-5.3-codex",
            "gpt-5.2-codex",
            "gpt-5-codex",
        ];

        $this->result = null;

        if (in_array($model, $chatModels)) {
            $data = [
                'model' => $model,
                'messages' => [
                    [
                        "role" => "system",
                        "content" => "You are an expert software engineer. Provide clean, well-commented, production-ready code with explanations."
                    ],
                    [
                        "role" => "user",
                        "content" => $prompt . ". Please provide information about programming."
                    ]
                ],
            ];

            $response = $this->callOpenAi("https://api.openai.com/v1/chat/completions", $data, $open_ai_key);

            if ($response !== null) {
                $result = json_decode($response);
                if ($result && isset($result->choices[0]->message->content)) {
                    $this->result = $result->choices[0]->message->content;
                } elseif (isset($result->error)) {
                    Log::error('OpenAI chat error: ' . json_encode($result->error));
                }
            }
        } elseif (in_array($model, $codexModels)) {
            $data = [
                'model'  => $model, // fixed: use the selected codex model, not a hardcoded one
                'prompt' => "You are an expert software engineer. Provide clean, well-commented, production-ready code with explanations.\n\n" . $prompt . ". Please provide information about programming.",
                'max_tokens'        => 2048,
                'temperature'       => 0.5,
                'top_p'             => 1,
                'frequency_penalty' => 0,
                'presence_penalty'  => 0,
            ];

            $response = $this->callOpenAi("https://api.openai.com/v1/completions", $data, $open_ai_key);

            if ($response !== null) {
                $result = json_decode($response);
                if ($result && isset($result->choices[0]->text)) {
                    $this->result = $result->choices[0]->text;
                } elseif (isset($result->error)) {
                    Log::error('OpenAI codex error: ' . json_encode($result->error));
                }
            }
        } else {
            // Legacy completions fallback via SDK
            $open_ai = new OpenAi($open_ai_key);

            $complete = $open_ai->completion([
                'model'             => $model,
                'prompt'            => $prompt,
                'temperature'       => $request->temperature ?? 0.7,
                'max_tokens'        => $request->max_tokens ?? 1024,
                'top_p'             => $request->top_p ?? 1,
                "frequency_penalty" => $request->frequency_penalty ?? 0,
                "presence_penalty"  => $request->presence_penalty ?? 0,
            ]);

            $result = json_decode($complete);
            if ($result && isset($result->choices[0]->text)) {
                $this->result = $result->choices[0]->text;
            } elseif (isset($result->error)) {
                Log::error('OpenAI legacy completion error: ' . json_encode($result->error));
            }
        }

        return $this->result;
    }

    private function callOpenAi(string $url, array $data, string $apiKey): ?string
    {
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL            => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING       => "",
            CURLOPT_MAXREDIRS      => 10,
            CURLOPT_TIMEOUT        => 30, // seconds, fixed from 30000
            CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST  => "POST",
            CURLOPT_POSTFIELDS     => json_encode($data),
            CURLOPT_HTTPHEADER     => [
                'Content-Type: application/json',
                'Authorization: Bearer ' . $apiKey,
            ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        if ($err) {
            Log::error("cURL Error: " . $err);
            return null;
        }

        return $response;
    }
}
