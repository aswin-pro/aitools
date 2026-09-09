<?php
namespace App\Services;

use App\Http\Requests\User\ImageGenerationRequest;
use App\Models\GeneratedImage;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Ai\Image;

class ImageGeneratorService
{
    private ImageGenerationRequest $request;
    private array $ai_provider_config;

    public function __construct(ImageGenerationRequest $request)
    {
        // request
        $this->request = $request;

        // get AI provider configuration
        $this->ai_provider_config = getAiProviderConfig('image_generator');
    }

    public function generate()
    {
        // Build prompt
        $prompt = $this->buildPrompt();

        // set manual timing
        set_time_limit(120);

        try {
            // Generate Image
            $response = Image::of($prompt)
                ->quality('medium')
                ->timeout(120);

            // set size
            $image = match ($this->request->size) {
                '1:1'  => $response->square(),
                '9:16' => $response->portrait(),
                '16:9' => $response->landscape(),
            };

            // generate
            $response = $response->generate(
                provider: $this->ai_provider_config['provider'],
                model: $this->ai_provider_config['model'],
            );

            // get image
            $imageContent = base64_decode($response->images->first()->image);

            // filename
            $filename = Str::uuid() . '.png';

            // store image
            $path = app(AssetUploadService::class)->uploadAsset(
                $filename,
                $imageContent,
                'image'
            );

            // Generation ID
            $generationId = uniqid();

            // add image
            $image                = new GeneratedImage();
            $image->generation_id = $generationId;
            $image->generated_by  = Auth::user()->id;
            $image->name          = $this->request->prompt;
            $image->type          = $this->request->style;
            $image->prompt        = $prompt;
            $image->n             = 1;
            $image->size          = $this->request->size;
            $image->format        = 'url';
            $image->result        = [$path];
            $image->save();

            // reduce credits
            reduceCredits('ai_image_credits', 1);

            // return image
            return [
                'image' => $path,
            ];
        } catch (Exception $e) {
            return null;
        }
    }

    // prompt building for user
    public function buildPrompt(): string
    {
        // build prompt
        $prompt = sprintf(
            'Create only one image based on the following description: "%s". Apply the "%s" visual style while keeping the subject, composition, and key details faithful to the description.',
            $this->request->prompt,
            $this->request->style,
        );

        // return prompt
        return $prompt;
    }
}
