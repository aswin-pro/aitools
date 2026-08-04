<?php

namespace App\Http\Controllers\User;

use Carbon\Carbon;
use App\Models\Chat;
use App\Models\Plan;
use App\Models\User;
use App\Models\Generate;
use App\Models\ChatGenius;
use App\Models\ChatMessage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ChatGeniusController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    // Chat assistant
    public function indexAllAiChatGenius()
    {
        // Get all Chat assistant
        $chatgenius = ChatGenius::where('status', 1)->orderBy('id', 'desc')->get();

        return view('user.pages.chatgenius.index', compact('chatgenius'));
    }

    public function indexNewAiChatGenius(Request $request, $slug)
    {
        // Check active plans
        $active_plan = Plan::where('id', Auth::user()->plan_id)->first();

        // Check plan
        if ($active_plan != null) {
            // Get user used word count
            $generateWordCount = Generate::where('generate_by', Auth::user()->id)->sum('word_count');
            $chatWordCount = Chat::where('generate_by', Auth::user()->id)->sum('word_count');

            $usesWordsCount = $generateWordCount + $chatWordCount;

            // Get plan details
            $plan = User::where('id', Auth::user()->id)->first();
            $plan_details = json_decode($plan->plan_details);

            // Check validity
            $validity = User::where('id', Auth::user()->id)->whereDate('plan_validity', '>=', Carbon::now())->count();

            // Check maximum words
            $max_words = $plan_details->max_words;

            // Check user used word count
            if ($validity == 1) {
                if ($usesWordsCount < (int)$max_words) {
                    // Check premium plan
                    if ($plan_details->ai_chatgenius == 1) {
                        // Find Chat assistant
                        $chatgeniusData = ChatGenius::join('chats', 'chats.chat_genius_id', '=', 'chat_geniuses.chat_genius_id')
                            ->leftJoin('chat_messages', 'chat_messages.chat_id', '=', 'chats.chat_id')
                            ->where('chats.generate_by', Auth::user()->id)
                            ->where('chat_geniuses.chat_genius_id', $slug)
                            ->where('chats.chat_type', 'chat')
                            ->where('chats.status', 1)
                            ->select('chat_geniuses.*', 'chats.*', 'chat_messages.*')
                            ->orderBy('chats.created_at', 'desc')
                            ->get();

                        if (count($chatgeniusData) == 0) {
                            // First chat
                            $this->newConversationAiChatGenius($slug);
                        }

                        // Find Chat assistant
                        $chatgeniusData = ChatGenius::join('chats', 'chats.chat_genius_id', '=', 'chat_geniuses.chat_genius_id')
                            ->leftJoin('chat_messages', 'chat_messages.chat_id', '=', 'chats.chat_id')
                            ->where('chats.generate_by', Auth::user()->id)
                            ->where('chat_geniuses.chat_genius_id', $slug)
                            ->where('chats.chat_type', 'chat')
                            ->where('chats.status', 1)
                            ->select('chat_geniuses.*', 'chats.*', 'chat_messages.*')
                            ->orderBy('chats.created_at', 'desc')
                            ->get();

                        // Check if the chatgenius data is not null
                        if (count($chatgeniusData) != 0) {

                            $chatgeniusData = ChatGenius::join('chats', 'chats.chat_genius_id', '=', 'chat_geniuses.chat_genius_id')
                                ->leftJoin('chat_messages', 'chat_messages.chat_id', '=', 'chats.chat_id')
                                ->where('chats.generate_by', Auth::user()->id)
                                ->where('chat_geniuses.chat_genius_id', $slug)
                                ->where('chats.chat_type', 'chat')
                                ->where('chats.status', 1)
                                ->select('chat_geniuses.*', 'chats.*', 'chat_messages.*')
                                ->orderBy('chats.created_at', 'desc')
                                ->get();

                            $chats = [];

                            foreach ($chatgeniusData as $row) {
                                $chatId = $row->chat_id;

                                // Check if the chat is already added
                                if (!isset($chats[$chatId])) {
                                    $chats[$chatId] = [
                                        'chat_id' => $row->chat_id,
                                        'chat_genius_id' => $row->chat_genius_id,
                                        'chat_user_id' => $row->generate_by,
                                        'chat_title' => $row->chat_title,
                                        'chat_messages' => [],
                                    ];
                                }

                                // Add chat message if available
                                if ($row->chat_message_id) {
                                    // Ensure the chat_messages array is initialized
                                    if (!isset($chats[$chatId]['chat_messages'])) {
                                        $chats[$chatId]['chat_messages'] = [];
                                    }

                                    // Add new message at the beginning of the array
                                    array_unshift($chats[$chatId]['chat_messages'], [
                                        'chat_message_id' => $row->chat_message_id,
                                        'responsed_by' => $row->responsed_by,
                                        'message_content' => $row->chat_message,
                                        'created_at' => $row->created_at,
                                    ]);

                                    // Sort chat messages by created_at in ascending order
                                    usort($chats[$chatId]['chat_messages'], function ($a, $b) {
                                        return strtotime($a['created_at']) - strtotime($b['created_at']);
                                    });
                                }
                            }

                            if (count($chats) > 0) {
                                // Prepare the final array
                                $chatgeniusArray = [
                                    'chat_genius_id' => $slug,
                                    'chat_genius_image' => $chatgeniusData[0]->chat_genius_image,
                                    'chat_genius_name' => $chatgeniusData[0]->chat_genius_name,
                                    'chat_genius_expert' => $chatgeniusData[0]->chat_genius_expert,
                                    'chats' => array_values($chats), // Reset keys for the chats array 
                                ];

                                return view('user.pages.chatgenius.new', compact('chatgeniusArray'));
                            } else {
                                return redirect()->route('user.dashboard')->with('failed', trans('Chat assistant not found. Please contact administrator for more information.'));
                            }
                        } else {
                            return redirect()->route('user.all.ai.chatgenius')->with('failed', trans('Chat assistant not found!'));
                        }
                    } else {
                        return redirect()->route('user.dashboard')->with('failed', trans('Selected plan does not support Chat assistant. So, please upgrade your plan.'));
                    }
                } else {
                    // Redirect
                    return redirect()->route('user.all.ai.chatgenius')->with('failed', trans('Maximum words limit is exceeded, Please upgrade your plan.'));
                }
            } else {
                // Redirect
                return redirect()->route('user.all.ai.chatgenius')->with('failed', trans('Your plan is over. Choose your plan renewal or new package and use it.'));
            }
        } else {
            // Page redirect in plan is not activated
            return redirect()->route('user.plans');
        }
    }

    // Generate Chat assistant
    public function generateAiChatGenius(Request $request)
    {
        if ($request->message) {
            // Save user message
            $aiMessage = new ChatMessage;
            $aiMessage->chat_message_id = uniqid();
            $aiMessage->chat_id = $request->chat_id;
            $aiMessage->responsed_by = 'user';
            $aiMessage->chat_message = $request->message;
            $aiMessage->save();

            // Get all conversations
            $allCoversations = ChatMessage::where('chat_id', $request->chat_id)->get();
            $request["all_conversations"] = $allCoversations;

            // Get Chat Details
            $chatDetails = Chat::where('chat_id', $request->chat_id)->first();

            // Get Chat assistant Details
            $chatGeniusDetails = ChatGenius::where('chat_genius_id', $chatDetails->chat_genius_id)->first();
            $request["chatGeniusDetails"] = $chatGeniusDetails;

            // Generate Chat assistant
            $aiChat = new \App\Classes\AiChat();
            $response = $aiChat->generate($request); // AI-generated response

            if ($response) {
                // Save AI message
                $aiMessage = new ChatMessage;
                $aiMessage->chat_message_id = uniqid();
                $aiMessage->chat_id = $request->chat_id;
                $aiMessage->responsed_by = 'system';
                $aiMessage->chat_message = $response;
                $aiMessage->save();

                // Existing word counts
                $existingWordCount = 0;

                $wordCount = Chat::where('chat_id', $request->chat_id)->sum('word_count');
                if ($wordCount) {
                    $existingWordCount = $wordCount;
                }

                // Update word count
                Chat::where('chat_id', $request->chat_id)->update([
                    'word_count' => (int)$existingWordCount + (int)$this->utf8_str_word_count($response)
                ]);

                // Return the chat message
                $chatgeniusArray = $aiMessage;

                // Return the structured response
                return response()->json(['chatgeniusArray' => $chatgeniusArray]);
            } else {
                return response()->json(['chatgeniusArray' => ['chat_message' => trans('Currently, chatbots are not available for this model. Please contact administrator for more information.')]]);
            }
        } else {
            return response()->json(['chatgeniusArray' => ['chat_message' => trans('Please enter a message.')]]);
        }
    }

    // Generate new Chat assistant
    public function newConversationAiChatGenius($slug)
    {
        // Get single Chat assistant
        $chatGeniusDetails = ChatGenius::where('chat_genius_id', $slug)->first();

        // Generate ID
        $generate_id = uniqid();

        // Initial message
        $system = 'system';
        $message = $chatGeniusDetails->chat_genius_message;

        // Generate Chat assistant
        $aiChat = new \App\Classes\AiChat();
        $response = $aiChat->generate(['system' => $system, 'message' => $message]); // AI-generated response

        if ($response) {
            // Save Chat
            $saveChat = new Chat();
            $saveChat->chat_id = $generate_id;
            $saveChat->chat_type = 'chat';
            $saveChat->generate_by = Auth::user()->id;
            $saveChat->chat_genius_id = $slug;
            $saveChat->chat_title = "New chat conversation";
            $saveChat->word_count = 0;
            $saveChat->save();

            // Save Chat message
            $saveChatMessage = new ChatMessage();
            $saveChatMessage->chat_message_id = uniqid();
            $saveChatMessage->chat_id = $generate_id;
            $saveChatMessage->chat_message = $response;
            $saveChatMessage->save();

            return redirect()->route('user.new.ai.chatgenius', $slug);
        } else {
            // Handle error
            return redirect()->route('user.all.ai.chatgenius')->with('failed', trans('Chat generation failed!. Please contact administrator for more information.'));
        }
    }

    // Update Chat Details
    public function updateAiChatGeniusDetails(Request $request)
    {
        $chatId = $request->chatId;
        $newConversation = $request->newChatConversation;

        // Update chat details (adjust based on your model)
        Chat::where('chat_id', $chatId)->where('generate_by', Auth::user()->id)->update(['chat_title' => $newConversation]);

        // Return response
        return response()->json(['success' => true, 'message' => trans('Chat updated successfully')]);
    }

    // Delete chat
    public function deleteAiChatGenius(Request $request)
    {
        $chatId = $request->chatId;

        // Delete chat
        Chat::where('chat_id', $chatId)->where('generate_by', Auth::user()->id)->update(['status' => 0]);

        // Return response
        return response()->json(['success' => true, 'message' => trans('Chat deleted successfully')]);
    }

    // Word count
    function utf8_str_word_count($string, $format = 0, $charlist = null)
    {
        if ($charlist === null) {
            $regex = '/\\pL[\\pL\\p{Mn}\'-]*/u';
        } else {
            $split = array_map(
                'preg_quote',
                preg_split('//u', $charlist, -1, PREG_SPLIT_NO_EMPTY)
            );
            $regex = sprintf(
                '/(\\pL|%1$s)([\\pL\\p{Mn}\'-]|%1$s)*/u',
                implode('|', $split)
            );
        }
        switch ($format) {
            default:
            case 0:
                // For PHP >= 5.4.0 this is fine:
                return preg_match_all($regex, $string);

                // For PHP < 5.4 it's necessary to do this:
                // $results = null;
                // return preg_match_all($regex, $string, $results);
            case 1:
                $results = null;
                preg_match_all($regex, $string, $results);
                return $results[0];
            case 2:
                $results = null;
                preg_match_all($regex, $string, $results, PREG_OFFSET_CAPTURE);
                return empty($results[0])
                    ? array()
                    : array_combine(
                        array_map('end', $results[0]),
                        array_map('reset', $results[0])
                    );
        }
    }
}
