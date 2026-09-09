<?php
namespace App\Services;

use App\Http\Requests\User\SpeechToTextConversionRequest;
use App\Models\GeneratedContent;
use Exception;
use Illuminate\Support\Facades\Auth;
use Laravel\Ai\Transcription;

class SpeechToTextConverterService
{
    private SpeechToTextConversionRequest $request;
    private array $ai_provider_config;

    public function __construct(SpeechToTextConversionRequest $request)
    {
        // request
        $this->request = $request;

        // get AI provider configuration
        $this->ai_provider_config = getAiProviderConfig('speech_to_text_converter');
    }

    public function generate()
    {
        // Parameters
        $file     = $this->request->file('file');
        $fileName = $file->getClientOriginalName();

        // set manual timing
        set_time_limit(120);

        try {
            // Generate Image
            $response = Transcription::fromUpload($file)
                ->timeout(120)
                ->generate(
                    provider: $this->ai_provider_config['provider'],
                    model: $this->ai_provider_config['model'],
                );

            // convert to string
            $content = (string) $response;

            // check content
            if (blank($content)) {
                return null;
            }

            // Generation ID
            $generationId = uniqid();

            // words count
            $words_count = countWords($content);

            // Save generated content
            $generation                = new GeneratedContent();
            $generation->generation_id = $generationId;
            $generation->generated_by  = Auth::id();
            $generation->name          = $fileName;
            $generation->type          = GeneratedContent::SPEECH_TO_TEXT;
            $generation->lang          = "en";
            $generation->content       = $content;
            $generation->word_count    = $words_count;
            $generation->parameters    = "";
            $generation->save();

            // reduce credits
            reduceCredits('ai_credits', $words_count);

            // return response
            return [
                'conversion'    => $content,
                'generation_id' => $generationId,
            ];
        } catch (Exception $e) {
            return null;
        }
    }
}
