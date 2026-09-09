<?php
namespace App\Services;

use App\Ai\Agents\SiteAnalyzerAgent;
use App\Models\Chat;
use App\Models\ChatMessage;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class SiteAnalyzerService
{
    private Request $request;
    private ?string $chatId;
    private array $ai_provider_config;

    public function __construct(Request $request, ?string $chatId = null)
    {
        // request
        $this->request = $request;

        // chat id
        $this->chatId = $chatId;

        // get AI provider configuration
        $this->ai_provider_config = getAiProviderConfig('site_analyzer');
    }

    public function message(): ?array
    {
        try {
            // Create chat if needed
            if (! $this->chatId) {
                $chat = Chat::create([
                    'chat_id'        => uniqid(),
                    'chat_type'      => 'site_analyzer',
                    'attachment'     => $this->request->url,
                    'generated_by'   => Auth::user()->id,
                    'chat_assistant_id' => 'site_analyzer',
                    'chat_title'     => 'New chat conversation',
                    'word_count'     => 0,
                ]);
            } else {
                $chat = Chat::getDataByField(field: 'chat_id', value: $this->chatId, userId: Auth::user()->id);
            }

            // Save user message
            $userMessage = ChatMessage::create([
                'chat_message_id' => uniqid(),
                'chat_id'         => $chat->chat_id,
                'responsed_by'    => 'user',
                'chat_message'    => $this->request->message ?? 'Analyze this website',
            ]);

            // prompt
            $prompt = $this->buildPrompt($chat);

            // Send message to AI
            $response = (new SiteAnalyzerAgent())->prompt(
                $prompt,
                provider: $this->ai_provider_config['provider'],
                model: $this->ai_provider_config['model'],
            );

            // message
            $message = (string) $response;

            // Save assistant reply
            $assistantMessage = ChatMessage::create([
                'chat_message_id' => uniqid(),
                'chat_id'         => $chat->chat_id,
                'responsed_by'    => 'system',
                'chat_message'    => $message,
            ]);

            // format created at
            $assistantMessage->formatted_created_at = Carbon::parse($assistantMessage->created_at)->diffForHumans();

            // words count
            $words_count = countWords($message);

            // update wordings
            $chat->word_count = (int) $chat->word_count + $words_count;
            $chat->save();

            // reduce credits
            reduceCredits('ai_credits', $words_count);

            // return response
            return [
                'chat_id'           => $chat->chat_id,
                'user_message'      => $userMessage,
                'assistant_message' => $assistantMessage,
            ];
        } catch (Exception $e) {
            return null;
        }
    }

    // prompt building for user
    public function buildPrompt(mixed $chat): string
    {
        // question
        $question = $this->request->message ?: 'Summarize this website within 3 lines.';

        // Try Jina AI reader first
        try {
            $response = Http::timeout(30)->get('https://r.jina.ai/' . $chat->attachment);

            if (! $response->successful()) {
                $response = Http::timeout(30)->get($chat->attachment);
            }
        } catch (\Throwable $e) {
            $response = Http::timeout(30)->get($chat->attachment);
        }

        $content = $response->body();

        // Remove JavaScript and CSS from the content
        $content = preg_replace('/<script\b[^>]*>.*?<\/script>/is', '', $content);
        $content = preg_replace('/<style\b[^>]*>.*?<\/style>/is', '', $content);

        // return propmpt
        return <<<PROMPT
            Website content:
            {$content}

            User question:
            {$question}
        PROMPT;
    }
}
