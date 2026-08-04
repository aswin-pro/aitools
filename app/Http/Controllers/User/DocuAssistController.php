<?php

namespace App\Http\Controllers\User;

use ZipArchive;
use Carbon\Carbon;
use App\Models\Chat;
use App\Models\Plan;
use App\Models\User;
use App\Models\Generate;
use Spatie\PdfToText\Pdf;
use App\Models\ChatMessage;
use Illuminate\Http\Request;
use Smalot\PdfParser\Parser;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DocuAssistController extends Controller
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

    public function indexAllAiDocuAssistant(Request $request)
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
                    if ($plan_details->ai_docsassist == 1) {
                        // Find Chat Genius
                        $docuAssistData = Chat::leftJoin('chat_messages', 'chat_messages.chat_id', '=', 'chats.chat_id')
                            ->where('chats.generate_by', Auth::user()->id)
                            ->where('chats.chat_type', 'docuassist')
                            ->where('chats.status', 1)
                            ->select('chats.*', 'chat_messages.*')
                            ->orderBy('chats.created_at', 'desc')
                            ->get();

                        if (count($docuAssistData) == 0) {
                            // First chat
                            $this->newConversationAiDocuAssistant();
                        }

                        // Find Chat Genius
                        $docuAssistData = Chat::leftJoin('chat_messages', 'chat_messages.chat_id', '=', 'chats.chat_id')
                            ->where('chats.generate_by', Auth::user()->id)
                            ->where('chats.chat_type', 'docuassist')
                            ->where('chats.status', 1)
                            ->select('chats.*', 'chat_messages.*')
                            ->orderBy('chats.created_at', 'desc')
                            ->get();

                        $chats = [];

                        foreach ($docuAssistData as $row) {
                            $chatId = $row->chat_id;

                            // Check if the chat is already added
                            if (!isset($chats[$chatId])) {
                                $chats[$chatId] = [
                                    'chat_id' => $row->chat_id,
                                    'chat_genius_id' => 'docuassist',
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
                            $docuAssistArray = [
                                'chat_genius_id' => 'docuassist',
                                'chat_genius_image' => asset('images/docuassist/docuassist.gif'),
                                'chat_genius_name' => "File Analyzer",
                                'chat_genius_expert' => "File Content Analysis Expert",
                                'chats' => array_values($chats), // Reset keys for the chats array
                            ];

                            return view('user.pages.docuassist.new', compact('docuAssistArray'));
                        } else {
                            return redirect()->route('user.dashboard')->with('failed', trans('File Analyzer not found!. Please contact administrator for more information.'));
                        }
                    } else {
                        return redirect()->route('user.dashboard')->with('failed', trans('Selected plan does not support File Analyzer. So, please upgrade your plan.'));
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

    // Generate DocuAssist
    public function generateAiDocuAssistant(Request $request)
    {
        $chatId = $request->chat_id;
        $directory = public_path('images/docuassist/' . $chatId);
        $message = $request->message;

        // Handle file upload and delete old files
        if ($request->hasFile('uploadFile')) {
            $file = $request->file('uploadFile');
            $filename = "docuassist_" . time() . "_" . $file->getClientOriginalName();
            $file->move($directory, $filename);
            $extension = pathinfo($filename, PATHINFO_EXTENSION);
            $content = '';

            // Extract content based on file type
            if ($extension == 'txt' || $extension == 'docx') {
                $content = file_get_contents($directory . '/' . $filename);
            } elseif ($extension == 'pdf') {
                $parser = new Parser();
                $pdf = $parser->parseFile($directory . '/' . $filename);
                $content = $pdf->getText();
            }

            $message = "First, analyze the " . $content . " content. Then answer the questions I add using this. Only say what is in this content.";
        }

        // Save user message
        $saveUserMessage = new ChatMessage();
        $saveUserMessage->chat_message_id = uniqid();
        $saveUserMessage->chat_id = $chatId;
        $saveUserMessage->responsed_by = 'user';
        $saveUserMessage->chat_message = $request->message;
        $saveUserMessage->save();

        if ($message && is_dir($directory)) {
            $files = array_diff(scandir($directory), ['.', '..']);

            foreach ($files as $file) {
                $filePath = $directory . '/' . $file;
                $extension = pathinfo($file, PATHINFO_EXTENSION);

                if ($extension === 'txt') {
                    $content = file_get_contents($filePath);
                } elseif ($extension === 'docx') {
                    $content = $this->extractTextFromDocx($filePath);
                } elseif ($extension === 'pdf' && file_exists($filePath)) {
                    $parser = new Parser();
                    $pdf = $parser->parseFile($filePath);
                    $content = $pdf->getText();
                }

                if ($content) {
                    $request["all_conversations"] = ChatMessage::where('chat_id', $chatId)->get();
                    $request["content"] = $content;
                    // Generate Chat Genius Response
                    $response = (new \App\Classes\AiAssist())->generate($request);

                    if ($response) {
                        // Save AI message and update word count
                        $aiMessage = ChatMessage::create([
                            'chat_message_id' => uniqid(),
                            'chat_id' => $chatId,
                            'responsed_by' => 'system',
                            'chat_message' => $response
                        ]);
                        $existingWordCount = Chat::where('chat_id', $chatId)->sum('word_count') ?? 0;
                        Chat::where('chat_id', $chatId)->update(['word_count' => $existingWordCount + $this->utf8_str_word_count($response)]);

                        return response()->json(['docuAssistArray' => $aiMessage]);
                    } else {
                        return response()->json(['docuAssistArray' => ['chat_message' => trans('File Analyzer not found!. Please contact administrator for more information.')]]);
                    }
                }
            }
        }

        ChatMessage::create([
            'chat_message_id' => uniqid(),
            'chat_id' => $chatId,
            'responsed_by' => 'system',
            'chat_message' => trans('Please upload a file.')
        ]);

        return response()->json(['docuAssistArray' => ['chat_message_id' => uniqid(), 'chat_message' => trans('Please upload a file.')]]);
    }

    // Generate new DocuAssistant
    public function newConversationAiDocuAssistant()
    {
        // Generate ID
        $generate_id = uniqid();

        // Initial message
        $system = '';
        $message = "Welcome to File Analyzer! How can I assist you today with your document management needs? Whether you need help organizing, analyzing, or extracting data from your files, I’m here to make the process easier!. Say hello to  " . Auth::user()->name . " and ask for the uploaded document (.txt, .docx, .pdf) and then start your conversation.";

        // Generate Chat Genius
        $AiAssist = new \App\Classes\AiAssist();
        $response = $AiAssist->generate(['system' => $system, 'message' => $message]); // AI-generated response

        if ($response) {
            // Save Chat
            $saveChat = new Chat();
            $saveChat->chat_id = $generate_id;
            $saveChat->chat_type = 'docuassist';
            $saveChat->generate_by = Auth::user()->id;
            $saveChat->chat_genius_id = 'docuassist';
            $saveChat->chat_title = "New chat conversation";
            $saveChat->word_count = 0;
            $saveChat->save();

            // Save Chat message
            $saveChatMessage = new ChatMessage();
            $saveChatMessage->chat_message_id = uniqid();
            $saveChatMessage->chat_id = $generate_id;
            $saveChatMessage->chat_message = $response;
            $saveChatMessage->save();

            return redirect()->route('user.all.ai.docuassistant');
        } else {
            // Handle error
            return redirect()->route('user.all.ai.docuassistant')->with('failed', trans('File Analyzer not found!. Please contact administrator for more information.'));
        }
    }

    // Update Chat Details
    public function updateAiDocuAssistantDetails(Request $request)
    {
        $chatId = $request->chatId;
        $newConversation = $request->newChatConversation;

        // Update chat details (adjust based on your model)
        Chat::where('chat_id', $chatId)->where('generate_by', Auth::user()->id)->update(['chat_title' => $newConversation]);

        // Return response
        return response()->json(['success' => true, 'message' => trans('Chat updated successfully')]);
    }

    // Delete chat
    public function deleteAiDocuAssistant(Request $request)
    {
        $chatId = $request->chatId;

        // Delete chat
        Chat::where('chat_id', $chatId)->where('generate_by', Auth::user()->id)->update(['status' => 0]);

        // Return response
        return response()->json(['success' => true, 'message' => trans('Chat deleted successfully')]);
    }


    /**
     * Function to extract text from .docx files
     */
    function extractTextFromDocx($filePath)
    {
        $content = '';
        if (file_exists($filePath)) {
            $zip = new ZipArchive();
            if ($zip->open($filePath) === true) {
                // Open document.xml inside the .docx archive
                $xml = $zip->getFromName('word/document.xml');
                $zip->close();

                // Extract the text content from XML
                $content = strip_tags($xml);
            }
        }
        return $content;
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
