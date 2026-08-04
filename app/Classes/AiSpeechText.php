<?php

namespace App\Classes;

use OpenAI;
use App\Models\Config;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class AiSpeechText
{
    public function generate($request)
    {
        // Audio validation
        $request->validate([
            'audio' => 'required|mimes:mp3,mp4,mpeg,mpga,m4a,wav,webm|max:' . config('app.audio_size_limit', 25600),
        ]);

        // Upload audio with a collision-resistant filename
        $audio = $request->file('audio');
        $filename = uniqid('audio_', true) . '.' . $audio->getClientOriginalExtension();
        $destinationPath = public_path('/audio');
        $audio->move($destinationPath, $filename);
        $filePath = public_path('audio/' . $filename);

        $this->result = null;

        try {
            $config = Config::get();
            $open_ai_key = $config[35]->config_value;
            $client = OpenAI::client($open_ai_key);

            $response = $client->audio()->transcribe([
                'model' => 'gpt-4o-transcribe',
                'file' => fopen($filePath, 'r'),
                'response_format' => 'text',
            ]);

            $this->result = $response->text ?? null;
        } catch (\Throwable $e) {
            Log::error('AiSpeechText transcription failed: ' . $e->getMessage());
            $this->result = null;
        } finally {
            // Always clean up the temp file, success or failure
            if (File::exists($filePath)) {
                File::delete($filePath);
            }
        }

        return $this->result;
    }
}
