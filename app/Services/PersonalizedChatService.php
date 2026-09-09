<?php
namespace App\Services;

use App\Ai\Agents\PersonalizedAgent;
use App\Http\Requests\User\PersonalizedChatRequest;
use App\Models\Chat;
use App\Models\ChatAssistant;
use App\Models\ChatMessage;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;

class PersonalizedChatService
{
    private PersonalizedChatRequest $request;
    private string $assistantId;
    private ?string $chatId;
    private array $ai_provider_config;

    public function __construct(PersonalizedChatRequest $request, string $assistantId, ?string $chatId = null)
    {
        // request
        $this->request = $request;

        // assistant id
        $this->assistantId = $assistantId;

        // chat id
        $this->chatId = $chatId;

        // get AI provider configuration
        $this->ai_provider_config = getAiProviderConfig('personalized_chat');
    }

    public function message()
    {
        try {
            // assistant
            $assistant = ChatAssistant::getDataByField('chat_assistant_id', $this->assistantId);

            // Create chat if needed
            if (! $this->chatId) {
                $chat = Chat::create([
                    'chat_id'        => uniqid(),
                    'chat_type'      => 'chat',
                    'generated_by'   => Auth::user()->id,
                    'chat_assistant_id' => $this->assistantId,
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
                'responsed_by'    => ChatMessage::RESPONSED_BY_USER,
                'chat_message'    => $this->request->message,
            ]);

            // Load past conversations
            $conversation = ChatMessage::getPastConversations($chat->chat_id);

            // Ask Laravel AI
            $response = (new PersonalizedAgent($conversation, $assistant))
                ->prompt(
                    $this->request->message,
                    provider: $this->ai_provider_config['provider'],
                    model: $this->ai_provider_config['model'],
                );

            // message
            $message = (string) $response;

            // Save assistant reply
            $assistantMessage = ChatMessage::create([
                'chat_message_id' => uniqid(),
                'chat_id'         => $chat->chat_id,
                'responsed_by'    => ChatMessage::RESPONSED_BY_SYSTEM,
                'chat_message'    => $message,
            ]);

            // format created at
            $assistantMessage->formatted_created_at = Carbon::parse($assistantMessage->created_at)->diffForHumans();

            // words count
            $words_count = countWords($message);

            // update wordings
            $chat->word_count = (int) $chat->word_count + (int) $words_count;
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
