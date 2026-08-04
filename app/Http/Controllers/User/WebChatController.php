<?php

namespace App\Http\Controllers\User;

use Carbon\Carbon;
use App\Models\Chat;
use App\Models\Plan;
use App\Models\User;
use App\Models\Generate;
use App\Models\ChatMessage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Symfony\Component\DomCrawler\Crawler;

class WebChatController extends Controller
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

    //  Generate new WebChat
    public function indexAllAiWebChat(Request $request)
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
                    if ($plan_details->ai_webchat == 1) {
                        // Find Chat Genius
                        $webChatData = Chat::leftJoin('chat_messages', 'chat_messages.chat_id', '=', 'chats.chat_id')
                            ->where('chats.generate_by', Auth::user()->id)
                            ->where('chats.chat_type', 'webchat')
                            ->where('chats.status', 1)
                            ->select('chats.*', 'chat_messages.*')
                            ->orderBy('chats.created_at', 'desc')
                            ->get();

                        if (count($webChatData) == 0) {
                            // First chat
                            $this->newConversationAiWebChat();
                        }

                        // Find web assistant chats
                        $webChatData = Chat::leftJoin('chat_messages', 'chat_messages.chat_id', '=', 'chats.chat_id')
                            ->where('chats.generate_by', Auth::user()->id)
                            ->where('chats.chat_type', 'webchat')
                            ->where('chats.status', 1)
                            ->select('chats.*', 'chat_messages.*')
                            ->orderBy('chats.created_at', 'desc')
                            ->get();

                        $chats = [];

                        foreach ($webChatData as $row) {
                            $chatId = $row->chat_id;

                            // Check if the chat is already added
                            if (!isset($chats[$chatId])) {
                                $chats[$chatId] = [
                                    'chat_id' => $row->chat_id,
                                    'chat_genius_id' => 'webchat',
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

                        // Prepare the final array
                        if (count($chats) > 0) {
                            $webChatArray = [
                                'chat_genius_id' => 'webchat',
                                'chat_genius_image' => asset('images/webchat/webchat.gif'),
                                'chat_genius_name' => "WebChat",
                                'chat_genius_expert' => "Document Management Expert",
                                'chats' => array_values($chats), // Reset keys for the chats array
                            ];

                            return view('user.pages.webchat.new', compact('webChatArray'));
                        } else {
                            return redirect()->route('user.dashboard')->with('failed', trans('Web Analyzer not found!. Please contact administrator for more information.'));
                        }
                    } else {
                        return redirect()->route('user.dashboard')->with('failed', trans('Selected plan does not support Web Analyzer. So, please upgrade your plan.'));
                    }
                } else {
                    // Redirect
                    return redirect()->route('user.dashboard')->with('failed', trans('Maximum words limit is exceeded, Please upgrade your plan.'));
                }
            } else {
                // Redirect
                return redirect()->route('user.dashboard')->with('failed', trans('Your plan is over. Choose your plan renewal or new package and use it.'));
            }
        } else {
            // Page redirect in plan is not activated
            return redirect()->route('user.plans');
        }
    }

    // Generate WebChat
    public function generateAiWebChat(Request $request)
    {
        $chatId = $request->chat_id;
        $message = $request->message;

        // Save user message
        $this->saveChatMessage($chatId, 'user', $message);

        // Regex to match URLs starting with https://
        $pattern = '/https:\/\/[^\s]+/i';

        // Initialize website link
        $website_link = '';

        if ($message !== '') {
            // Find website link from Chat
            $find_url = Chat::where('chat_id', $chatId)->where('generate_by', Auth::user()->id)->first();

            // Check for existing URL
            if (preg_match_all($pattern, $find_url->chat_genius_id, $matches)) {
                $website_link = $find_url->chat_genius_id;
            } else {
                // Use preg_match_all to find all matches in the message
                preg_match_all($pattern, $message, $matches);
                if (!empty($matches[0])) {
                    $website_link = $matches[0][0]; // Take the first URL found

                    Chat::where('chat_id', $chatId)->where('generate_by', Auth::user()->id)->update(['chat_genius_id' => $website_link]);
                }
            }

            if (preg_match_all($pattern, $website_link, $matches)) {
                // Fetch the content of the website
                $response = Http::get($website_link);

                // Check if the request was successful
                if ($response->successful()) {
                    $htmlContent = $response->body();

                    // Extract important elements
                    $overview = $this->extractOverview($htmlContent);

                    // Formatting Headings as a comma-separated string
                    $headingsText = implode(', ', $overview['headings']);

                    // Formatting Paragraphs as a comma-separated string
                    $paragraphsText = implode(', ', $overview['paragraphs']);

                    // Formatting Links as "text (url)" pairs
                    $linksText = implode(', ', array_map(function ($link) {
                        return "{$link['text']} ({$link['url']})";
                    }, $overview['links']));

                    // Create the final formatted string
                    $formattedText = "Title: {$overview['title']}, Description: {$overview['description']}, Headings: {$headingsText}, Paragraphs: {$paragraphsText}, Links: {$linksText}";

                    $request["all_conversations"] = ChatMessage::where('chat_id', $chatId)->get();
                    $request["content"] = $formattedText;

                    // Generate Chat Genius response
                    $aiChat = new \App\Classes\AiWebAssist();
                    $response = $aiChat->generate($request->merge(['message' => $message]));
                } else {
                    // Handle case for empty message
                    $responseMessage = trans("Type the url of the website you want to analyze. For example: https://www.example.com and the questions you want to ask.");

                    return response()->json(['webChatArray' => $this->prepareChatMessage('system', $responseMessage)]);
                }
            } else {
                $responseMessage = trans("Please provide a valid URL. For example: https://www.example.com");

                return response()->json(['webChatArray' => $this->prepareChatMessage('system', $responseMessage)]);
            }

            if ($response) {
                // Save AI message
                $this->saveChatMessage($chatId, 'system', $response);

                // Existing word counts
                $existingWordCount = 0;

                $wordCount = Chat::where('chat_id', $chatId)->sum('word_count');
                if ($wordCount) {
                    $existingWordCount = $wordCount;
                }

                // Update word count
                Chat::where('chat_id', $chatId)->update([
                    'word_count' => (int)$existingWordCount + (int)$this->utf8_str_word_count($response)
                ]);

                return response()->json(['webChatArray' => $this->prepareChatMessage('system', $response)]);
            } else {
                return response()->json(['webChatArray' => $this->prepareChatMessage('system', trans("Web Analyzer not found!. Please contact administrator for more information."))]);
            }
        } else {
            // Handle case for empty message
            $responseMessage = trans("Type the url of the website you want to analyze. For example: https://www.example.com and the questions you want to ask.");
            $this->saveChatMessage($chatId, 'system', $responseMessage);
            return response()->json(['webChatArray' => $this->prepareChatMessage('system', $responseMessage)]);
        }
    }

    // Generate new WebChat
    public function newConversationAiWebChat()
    {
        // Generate ID
        $generate_id = uniqid();

        // Initial message
        $system = '';
        $message = "Your name is WebAssistant and your job is to read the content on the website and understand it and answer the questions I ask and also answer the questions I ask about the website content, seo details and more. Don't say anything apart from the content. Say hello to  " . Auth::user()->name . " and ask for the website link (with http or https) and then start your conversation.";

        // Generate Chat Genius
        $AiWebAssist = new \App\Classes\AiWebAssist();
        $response = $AiWebAssist->generate(['system' => $system, 'message' => $message]); // AI-generated response

        if ($response) {
            // Save Chat
            $saveChat = new Chat();
            $saveChat->chat_id = $generate_id;
            $saveChat->chat_type = 'webchat';
            $saveChat->generate_by = Auth::user()->id;
            $saveChat->chat_genius_id = 'webchat';
            $saveChat->chat_title = "New chat conversation";
            $saveChat->word_count = 0;
            $saveChat->save();

            // Save Chat message
            $saveChatMessage = new ChatMessage();
            $saveChatMessage->chat_message_id = uniqid();
            $saveChatMessage->chat_id = $generate_id;
            $saveChatMessage->chat_message = $response;
            $saveChatMessage->save();

            return redirect()->route('user.all.ai.webchat');
        } else {
            // Handle error
            return redirect()->route('user.all.ai.webchat')->with('failed', trans('Web Analyzer not found!. Please contact administrator for more information.'));
        }
    }


    // Update Chat Details
    public function updateAiWebChatDetails(Request $request)
    {
        $chatId = $request->chatId;
        $newConversation = $request->newChatConversation;

        // Update chat details (adjust based on your model)
        Chat::where('chat_id', $chatId)->where('generate_by', Auth::user()->id)->update(['chat_title' => $newConversation]);

        // Return response
        return response()->json(['success' => true, 'message' => trans('Chat updated successfully')]);
    }

    // Delete chat
    public function deleteAiWebChat(Request $request)
    {
        $chatId = $request->chatId;

        // Delete chat
        Chat::where('chat_id', $chatId)->where('generate_by', Auth::user()->id)->update(['status' => 0]);

        // Return response
        return response()->json(['success' => true, 'message' => trans('Chat deleted successfully')]);
    }



    // Function to extract title, description, headings, paragraphs, and links
    private function extractOverview($htmlContent)
    {
        // Initialize Crawler for parsing HTML
        $crawler = new Crawler($htmlContent);

        // Initialize overview array
        $overview = [];

        // Get the title of the website
        $overview['title'] = $crawler->filter('title')->count() ? $crawler->filter('title')->text() : 'No title found';

        // Get meta description
        $description = $crawler->filterXPath("//meta[@name='description']")->attr('content') ?? 'No description found';
        $overview['description'] = $description;

        // Extract headings (h1, h2, h3)
        $overview['headings'] = [];
        $crawler->filter('h1, h2, h3')->each(function (Crawler $node) use (&$overview) {
            $overview['headings'][] = $node->text();
        });

        // Extract first few paragraphs
        $overview['paragraphs'] = [];
        $crawler->filter('p')->each(function (Crawler $node) use (&$overview) {
            $overview['paragraphs'][] = $node->text();
        });

        // Extract links
        $overview['links'] = [];
        $crawler->filter('a')->each(function (Crawler $node) use (&$overview) {
            $overview['links'][] = [
                'text' => $node->text(),
                'url' => $node->attr('href')
            ];
        });

        return $overview;
    }


    // Helper function to save chat messages
    private function saveChatMessage($chatId, $respondedBy, $chatMessage)
    {
        $aiMessage = new ChatMessage();
        $aiMessage->chat_message_id = uniqid();
        $aiMessage->chat_id = $chatId;
        $aiMessage->responsed_by = $respondedBy;
        $aiMessage->chat_message = $chatMessage;
        $aiMessage->save();
    }

    // Helper function to prepare the chat message array for response
    private function prepareChatMessage($respondedBy, $chatMessage)
    {
        return [
            'chat_message_id' => uniqid(),
            'responsed_by' => $respondedBy,
            'chat_message' => $chatMessage,
        ];
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
