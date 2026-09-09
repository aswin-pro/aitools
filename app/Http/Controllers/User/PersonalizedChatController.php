<?php
namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\ChatTitleRequest;
use App\Http\Requests\User\PersonalizedChatRequest;
use App\Models\Chat;
use App\Models\ChatAssistant;
use App\Models\ChatMessage;
use App\Services\PersonalizedChatService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class PersonalizedChatController extends Controller
{
    // index
    public function index(Request $request): Response
    {
        // return view
        return Inertia::render('user/personalized-chat/index', [
            'assistants' => fn() => ChatAssistant::dataWithPagination(
                search: $request->search,
                perPage: $request->integer('per_page', 9),
            ),
        ]);
    }

    // chat
    public function chat(string $assistantId, ?string $chatId = null): Response
    {
        // assistant
        $assistant = ChatAssistant::getDataByField('chat_assistant_id', $assistantId);

        // if assistant not fount return
        if (! $assistant) {
            abort(404);
        }

        // chats
        $chats = Chat::getChats(
            assistantId: $assistantId,
            type: 'chat',
            userId: Auth::user()->id,
        );

        // initialize messages
        $messages = [];

        // if chat available get messages also
        if (! is_null($chatId)) {
            // messages
            $messages = ChatMessage::getMessages(chatId: $chatId)->map(function ($message) {
                return [
                    'id'                   => $message->id,
                    'chat_message_id'      => $message->chat_message_id,
                    'chat_id'              => $message->chat_id,
                    'responsed_by'         => $message->responsed_by,
                    'chat_message'         => $message->chat_message,
                    'formatted_created_at' => Carbon::parse($message->created_at)->diffForHumans(),
                ];
            });
        }

        // return view
        return Inertia::render('user/personalized-chat/chat/index', [
            'assistant' => $assistant,
            'chats'     => $chats,
            'messages'  => $messages,
        ]);
    }

    // message
    public function message(PersonalizedChatRequest $request, string $assistantId, ?string $chatId = null): JsonResponse
    {
        // check plan validity
        if (! hasPlanValidity() || ! hasFutureOnPlan('personalized-chat')) {
            return response()->json([
                'message' => 'Please upgrade your plan to use this feature.',
            ], 403);
        }

        // check limit excedded
        if (hasExceededCredits('personalized_chat')) {
            return response()->json([
                'message' => 'credits_exceeded',
            ], 403);
        }

        // response
        $response = new PersonalizedChatService($request, $assistantId, $chatId)->message();

        // if null return error
        if (! $response) {
            return response()->json([
                'message' => 'The AI assistant is temporarily unavailable. Please try again.',
            ], 500);
        }

        // return response
        return response()->json($response);
    }

    // update chat title
    public function updateChatTitle(ChatTitleRequest $request, string $chatId): RedirectResponse
    {
        // Update data
        $chat = Chat::getDataByField(field: 'chat_id', value: $chatId, userId: Auth::user()->id);

        if ($chat) {
            $chat->update([
                'chat_title' => $request->title,
            ]);
        }

        // return back
        return back();
    }

    // delete chat
    public function destroy(string $assistantId, string $chatId): RedirectResponse
    {
        // chat
        $chat = Chat::getDataByField(field: 'chat_id', value: $chatId, userId: Auth::user()->id);

        // chat messages
        ChatMessage::where('chat_id', $chat->chat_id)->delete();

        // delete chat
        $chat->delete();

        // return back
        return to_route('dashboard.user.personalized-chat.chat', [
            'assistant' => $assistantId,
        ]);
    }
}
