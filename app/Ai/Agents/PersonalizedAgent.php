<?php
namespace App\Ai\Agents;

use App\Models\ChatAssistant;
use App\Models\ChatMessage;
use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Contracts\Conversational;
use Laravel\Ai\Contracts\HasTools;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Messages\AssistantMessage;
use Laravel\Ai\Messages\Message;
use Laravel\Ai\Messages\UserMessage;
use Laravel\Ai\Promptable;
use Stringable;

class PersonalizedAgent implements Agent, Conversational, HasTools
{
    use Promptable;

    public function __construct(
        protected iterable $conversation,
        protected ChatAssistant $assistant,
    ) {}

    /**
     * Get the instructions that the agent should follow.
     */
    public function instructions(): Stringable | string
    {
        return $this->assistant->chat_assistant_message;
    }

    /**
     * Get the list of messages comprising the conversation so far.
     *
     * @return Message[]
     */
    public function messages(): iterable
    {
        return collect($this->conversation)->map(
            fn(ChatMessage $message) => $message->responsed_by === 'user'
                ? new UserMessage($message->chat_message)
                : new AssistantMessage($message->chat_message)
        );
    }

    /**
     * Get the tools available to the agent.
     *
     * @return Tool[]
     */
    public function tools(): iterable
    {
        return [];
    }
}
