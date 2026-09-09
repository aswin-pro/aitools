<?php
namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\ChatTitleRequest;
use App\Http\Requests\User\DocumentAnalyzerRequest;
use App\Models\Chat;
use App\Models\ChatMessage;
use App\Services\DocumentAnalyzerService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class DocumentAnalyzerController extends Controller
{
    // chat
    public function index(?string $chatId = null): Response
    {
        // chats
        $chats = Chat::getChats('document_analyzer', 'document_analyzer', Auth::user()->id);

        // messages
        $messages = [];

        // if chatId available get messages
        if (! is_null($chatId)) {
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
        return Inertia::render('user/document-analyzer/index', [
            'chats'    => $chats,
            'messages' => $messages,
        ]);
    }

    // message
    public function message(DocumentAnalyzerRequest $request, ?string $chatId = null): JsonResponse
    {
         // check plan validity
        if (! hasPlanValidity() || ! hasFutureOnPlan('document-analyzer')) {
            return response()->json([
                'message' => 'Please upgrade your plan to use this feature.',
            ], 403);
        }
        
        // check limit excedded
        if (hasExceededCredits('document_analyzer')) {
            return response()->json([
                'message' => 'credits_exceeded',
            ], 403);
        }

        // response
        $response = new DocumentAnalyzerService($request, $chatId)->message();

        // if null return error
        if (! $response) {
            return response()->json([
                'message' => 'The AI Document Analyzer is temporarily unavailable. Please try again.',
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
    public function destroy(string $chatId): RedirectResponse
    {
        // chat
        $chat = Chat::getDataByField(field: 'chat_id', value: $chatId, userId: Auth::user()->id);

        // chat messages
        ChatMessage::where('chat_id', $chat->chat_id)->delete();

        // delete chat
        $chat->delete();

        // return back
        return to_route('dashboard.user.document-analyzer.index');
    }
}
