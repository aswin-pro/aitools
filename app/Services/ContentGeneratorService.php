<?php
namespace App\Services;

use App\Ai\Agents\ContentGenerator;
use App\Http\Requests\User\ContentGenerationRequest;
use App\Models\ContentTemplate;
use App\Models\GeneratedContent;
use Exception;
use Illuminate\Support\Facades\Auth;

class ContentGeneratorService
{
    private mixed $template_details;
    private array $ai_provider_config;
    private ContentGenerationRequest $request;

    // constructor
    public function __construct(ContentGenerationRequest $request, string $template)
    {
        // request
        $this->request = $request;

        // get template details
        $this->template_details = ContentTemplate::getDataByField('unique_slug', $template);

        // get AI provider configuration
        $this->ai_provider_config = getAiProviderConfig('content_generator');
    }

    // generate
    public function generate()
    {
        // Build prompt
        $prompt = $this->buildPrompt();

        try {
            // Generate AI content
            $response = (new ContentGenerator(
                temperature: (float) $this->request->level,
                maxLength: (int) $this->request->max_length,
            ))
                ->prompt(
                    $prompt,
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

            // Generation parameters
            $params = [
                'lang'              => $this->request->lang,
                'max_length'        => $this->request->max_length,
                'results'           => $this->request->results,
                'level'             => (float) $this->request->level,
                'max_tokens'        => (int) $this->request->max_length,
                'top_p'             => 1.0,
                'frequency_penalty' => 0.0,
                'presence_penalty'  => 0.0,
            ];

            // words count
            $words_count = countWords($content);

            // Save generated content
            $generation                = new GeneratedContent();
            $generation->generation_id = $generationId;
            $generation->generated_by  = Auth::id();
            $generation->name          = $this->request->input1;
            $generation->type          = $this->template_details->unique_slug;
            $generation->lang          = $this->request->lang;
            $generation->content       = $content;
            $generation->word_count    = $words_count;
            $generation->parameters    = json_encode($params);
            $generation->save();

            // reduce credits
            reduceCredits('ai_credits', $words_count);

            // return response
            return [
                'content'       => $content,
                'generation_id' => $generationId,
            ];
        } catch (Exception $e) {
            return null;
        }
    }

    // prompt building for user
    public function buildPrompt(): string
    {
        // template prompt
        $prompt = $this->template_details->prompt;

        // dynamic values
        $values = [
            '##tone##'    => $this->request->tone,
            '##lang##'    => $this->request->lang,
            '##results##' => $this->request->results,
        ];

        // dynamic placeholders
        preg_match_all('/##input\d+##/', $prompt, $matches);

        // replace placeholders
        foreach ($matches[0] as $placeholder) {
            $input = trim($placeholder, '#');

            $values[$placeholder] = "<user_data>{$this->request->input($input, '')}</user_data>";
        }

        // build prompt
        $prompt = str_replace(
            array_keys($values),
            array_values($values),
            $prompt
        );

        // return prompt
        return $prompt;
    }
}
