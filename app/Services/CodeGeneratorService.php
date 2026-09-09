<?php
namespace App\Services;

use App\Ai\Agents\CodeGenerator;
use App\Http\Requests\User\CodeGenerationRequest;
use App\Models\GeneratedContent;
use Exception;
use Illuminate\Support\Facades\Auth;

class CodeGeneratorService
{
    private CodeGenerationRequest $request;
    private array $ai_provider_config;

    public function __construct(CodeGenerationRequest $request)
    {
        // request
        $this->request = $request;

        // get AI provider configuration
        $this->ai_provider_config = getAiProviderConfig('code_generator');
    }

    public function generate()
    {
        // Build prompt
        $prompt = $this->buildPrompt();

        try {
            // Generate AI code
            $response = (new CodeGenerator())
                ->prompt(
                    $prompt,
                    provider: $this->ai_provider_config['provider'],
                    model: $this->ai_provider_config['model'],
                );

            // convert to string
            $code = (string) $response;

            // check code
            if (blank($code)) {
                return null;
            }

            // Generation ID
            $generationId = uniqid();

            $words_count = countWords($code);

            // Save generated code
            $generation                = new GeneratedContent();
            $generation->generation_id = $generationId;
            $generation->generated_by  = Auth::id();
            $generation->name          = $this->request->prompt;
            $generation->type          = GeneratedContent::CODE_GENERATOR;
            $generation->lang          = "en";
            $generation->content       = $code;
            $generation->word_count    = $words_count;
            $generation->parameters    = "";
            $generation->save();

            // reduce credits
            reduceCredits('ai_credits', $words_count);

            // return response
            return [
                'code'          => $code,
                'generation_id' => $generationId,
            ];
        } catch (Exception $e) {
            return null;
        }
    }

    // prompt building for user
    public function buildPrompt(): string
    {
        // Return prompt
        return 
        <<<INSTRUCTIONS
            <user_data>
                {$this->request->prompt}
            </user_data>
        INSTRUCTIONS;
    }
}
