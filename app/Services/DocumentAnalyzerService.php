<?php
namespace App\Services;

use App\Ai\Agents\DocumentAnalyzerAgent;
use App\Http\Requests\User\DocumentAnalyzerRequest;
use App\Models\Chat;
use App\Models\ChatMessage;
use App\Models\UserUpload;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Laravel\Ai\Files\Document;

class DocumentAnalyzerService
{
    private DocumentAnalyzerRequest $request;
    private ?string $chatId;
    private array $ai_provider_config;

    public function __construct(DocumentAnalyzerRequest $request, ?string $chatId = null)
    {
        // request
        $this->request = $request;

        // chat id
        $this->chatId = $chatId;

        // get AI provider configuration
        $this->ai_provider_config = getAiProviderConfig('document_analyzer');
    }

    public function message()
    {
        try {
            // Create chat if needed
            if (! $this->chatId) {
                $chat = Chat::create([
                    'chat_id'        => uniqid(),
                    'chat_type'      => 'document_analyzer',
                    'generated_by'   => Auth::user()->id,
                    'chat_assistant_id' => 'document_analyzer',
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
                'chat_message'    => $this->request->message,
            ]);

            $upload = UserUpload::where('upload_id', $this->request->file)
                ->where('file_type', 'document')
                ->where('user_id', Auth::id())
                ->first();

            $attachments = [];

            if ($upload) {
                $path = Storage::disk('public')->path(
                    str_replace('/storage/', '', $upload->file_url)
                );

                $attachments[] = Document::fromPath($path);
            }

            $response = (new DocumentAnalyzerAgent())->prompt(
                $this->request->message,
                provider: $this->ai_provider_config['provider'],
                model: $this->ai_provider_config['model'],
                attachments: $attachments
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
}
