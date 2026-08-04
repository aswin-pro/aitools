<?php

namespace App\Classes;

use OpenAI;
use App\Models\Config;
use Illuminate\Support\Facades\Log;

class AiTextSpeech
{
    private const ALLOWED_FORMATS = ['mp3', 'opus', 'aac', 'flac', 'wav', 'pcm'];

    public function generate($request)
    {
        $request->validate([
            'name'         => 'required|string|max:100',
            'voices'       => 'required|string',
            'speed'        => 'required|numeric|min:0.25|max:4.0',
            'audio_format' => 'required|string|in:' . implode(',', self::ALLOWED_FORMATS),
            'content'      => 'required|string',
        ]);

        $config = Config::get();
        $open_ai_key = $config[35]->config_value;
        $client = OpenAI::client($open_ai_key);

        $this->result = null;

        try {
            $response = $client->audio()->speech([
                'model'            => $config[47]->config_value,
                'input'            => $request->content,
                'voice'            => $request->voices,
                'response_format'  => $request->audio_format,
                'speed'            => (float)$request->speed,
            ]);
        } catch (\Throwable $e) {
            Log::error('AiTextSpeech: OpenAI speech generation failed: ' . $e->getMessage());
            return null;
        }

        // Build a safe filename: strip anything that isn't alphanumeric/dash/underscore
        $safeName = preg_replace('/[^a-z0-9\-_]/', '', strtolower(str_replace(' ', '-', $request->name)));
        $safeName = $safeName !== '' ? $safeName : 'audio';

        $audioFilename = $safeName . '-' . uniqid('', true) . '.' . $request->audio_format;

        $destinationDir = public_path('audio');
        if (!is_dir($destinationDir)) {
            mkdir($destinationDir, 0755, true);
        }

        $fullPath = $destinationDir . DIRECTORY_SEPARATOR . $audioFilename;

        $written = file_put_contents($fullPath, $response);

        if ($written === false) {
            Log::error("AiTextSpeech: failed to write audio file to {$fullPath}");
            return null;
        }

        $this->result = 'audio/' . $audioFilename;

        return $this->result;
    }
}
