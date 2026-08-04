<?php

namespace App\Classes;

use App\Models\Config;
use App\Models\CustomTemplate;
use Orhanerday\OpenAi\OpenAi;
use Illuminate\Support\Facades\Log;

class AiTools
{
    public function generate($request)
    {
        $config = Config::get();

        $open_ai_key = $config[35]->config_value;
        $open_ai = new OpenAi($open_ai_key);
        $level = (float)$request->level;
        $topP = 1.0;
        $frequencyPenalty = 0.0;
        $presencePenalty = 0.0;
        $maxTokens = $request->max_length ? (int)$request->max_length : 600;

        $this->result = null;

        // Template
        $template_details = CustomTemplate::join('custom_template_fields', 'custom_templates.id', '=', 'custom_template_fields.template_id')
            ->select('custom_templates.*', 'custom_template_fields.*')
            ->where('custom_templates.unique_slug', $request->type)
            ->where('custom_templates.status', 1)
            ->get();

        if ($template_details->isEmpty()) {
            Log::error("AiTools: no active template found for slug '{$request->type}'");
            return null;
        }

        $prompt = $template_details[0]->prompt;
        $prompt = str_replace("##results##", $request->results, $prompt);
        $prompt = str_replace("##tone##", $request->tone, $prompt);
        $prompt = str_replace("##lang##", $request->lang, $prompt);

        for ($i = 0; $i < count($template_details); $i++) {
            $placeholder = "##input" . ($i + 1) . "##";
            $replacement = $request->input("input" . ($i + 1), ''); // avoid null -> str_replace
            $prompt = str_replace($placeholder, $replacement, $prompt);
        }

        $allowedModels = [
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
            // NOTE: "gpt-5-codex" intentionally left out here — decide with AiCode.php
            // which endpoint it should use, then handle it consistently in both places.
        ];

        $model = $config[34]->config_value;

        if (in_array($model, $allowedModels)) {
            $data = [
                'model' => $model,
                'messages' => [["role" => "user", "content" => $prompt]],
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
        } else {
            $complete = $open_ai->completion([
                'model' => $model,
                'prompt' => $prompt,
                'temperature' => $level,
                'max_tokens' => $maxTokens,
                'top_p' => $topP,
                "frequency_penalty" => $frequencyPenalty,
                "presence_penalty" => $presencePenalty,
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
            CURLOPT_TIMEOUT        => 30, // seconds, fixed
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
