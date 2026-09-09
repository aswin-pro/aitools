<?php
namespace App\Services;

use App\Http\Requests\User\TextToSpeechConversionRequest;
use App\Models\GeneratedContent;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Ai\Audio;

class TextToSpeechConverterService
{
    private TextToSpeechConversionRequest $request;
    private array $ai_provider_config;

    public function __construct(TextToSpeechConversionRequest $request)
    {
        // request
        $this->request = $request;

        // get AI provider configuration
        $this->ai_provider_config = getAiProviderConfig('text_to_speech_converter');
    }

    public function generate()
    {
        // set manual timing
        set_time_limit(120);

        try {
            // response
            $response = Audio::of($this->request->text)
                ->voice($this->request->voice)
                ->withProviderOptions([
                    'speed'           => (float) $this->request->speed,
                    'response_format' => $this->request->audio_format,
                ])
                ->timeout(120)
                ->generate(
                    provider: $this->ai_provider_config['provider'],
                    model: $this->ai_provider_config['model'],
                );

            // filename
            $filename = Str::uuid() . '.' . $this->request->audio_format;

            // store image
            $path = app(AssetUploadService::class)->uploadAsset(
                $filename,
                $response->content(),
                'audio'
            );

            // Generation ID
            $generationId = uniqid();

            // Generation parameters
            $params = [
                'text'         => $this->request->text,
                'voice'        => $this->request->voice,
                'speed'        => $this->request->speed,
                'audio_format' => $this->request->audio_format,
            ];

            // words count
            $words_count = countWords($this->request->text);

            // Save generated content
            $generation                = new GeneratedContent();
            $generation->generation_id = $generationId;
            $generation->generated_by  = Auth::id();
            $generation->name          = $this->request->name;
            $generation->type          = GeneratedContent::TEXT_TO_SPEECH;
            $generation->lang          = "en";
            $generation->content       = $path;
            $generation->word_count    = $words_count;
            $generation->parameters    = json_encode($params);
            $generation->save();

            // reduce credits
            reduceCredits('ai_credits', $words_count);

            // return response
            return [
                'name'          => $this->request->name,
                'conversion'    => $path,
                'generation_id' => $generationId,
            ];

        } catch (Exception $e) {
            return null;
        }
    }
}
