<?php

namespace App\Http\Controllers\Admin;

use App\Models\ChatGenius;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

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

    // Chat Genius
    public function index()
    {
        // Get all Chat Genius
        $chatgenius = ChatGenius::where('status', '>=', 0)->orderBy('id', 'desc')->get();

        return view('admin.pages.chatgenius.index', compact('chatgenius'));
    }

    public function createChatgenius()
    {
        // Create Chat Genius
        return view('admin.pages.chatgenius.create');
    }

    public function saveChatgenius(Request $request)
    {
        // Validate form
        $sizeLimit = env("SIZE_LIMIT");
        $uniqueId = uniqid();

        $validator = Validator::make($request->all(), [
            'chat_genius_image' => 'required|mimes:jpg,jpeg,png,webp|max:' . $sizeLimit,
            'chat_genius_name' => 'required|min:2|max:200',
            'chat_genius_expert' => 'required|min:2|max:200',
            'chat_genius_description' => 'required|min:2',
            'chat_genius_message' => 'required|min:2'
        ], [
            // Custom error messages
            'chat_genius_image.required' => 'The image is required.',
            'chat_genius_image.mimes' => 'The image must be a file of type: jpg, jpeg, png, webp.',
            'chat_genius_image.max' => 'The image may not be greater than ' . $sizeLimit / 1024 . ' MB.',
            'chat_genius_name.required' => 'The name field is required.',
            'chat_genius_expert.required' => 'The expert field is required.',
            'chat_genius_description.required' => 'The description is required.',
            'chat_genius_message.required' => 'The message is required.'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', trans('Validation failed! Please check the form.'));
        }


        // Upload Chat Genius Image
        $request->file('chat_genius_image')->move(public_path('images/chatgenius'), $uniqueId . '.' . $request->file('chat_genius_image')->getClientOriginalExtension());
        $chatgeniusImage = 'images/chatgenius/' . $uniqueId . '.' . $request->file('chat_genius_image')->getClientOriginalExtension();
        // Save Chat Genius
        $chatgenius = new ChatGenius;
        $chatgenius->chat_genius_id = $uniqueId;
        $chatgenius->chat_genius_image = $chatgeniusImage;
        $chatgenius->chat_genius_name = $request->input('chat_genius_name');
        $chatgenius->chat_genius_expert = $request->input('chat_genius_expert');
        $chatgenius->chat_genius_description = $request->input('chat_genius_description');
        $chatgenius->chat_genius_message = $request->input('chat_genius_message');
        $chatgenius->save();

        return redirect()->route('admin.chatgenius')->with('success', trans('Chat Assistant created successfully!'));
    }

    public function editChatgenius(Request $request, $id)
    {
        // Find Chat Genius
        $chatgenius = ChatGenius::where('chat_genius_id', $id)->first();

        if ($chatgenius->id > 55) {
            if ($chatgenius == null) {
                return redirect()->route('admin.chatgenius')->with('failed', trans('Chat Assistant not found!'));
            }

            return view('admin.pages.chatgenius.edit', compact('chatgenius'));
        } else {
            return redirect()->route('admin.chatgenius')->with('failed', trans('Update Chat Assistant not allowed!'));
        }
    }

    public function updateChatgenius(Request $request)
    {
        // Validate form
        $sizeLimit = env("SIZE_LIMIT");
        $uniqueId = uniqid();

        // Validate form
        $validator = Validator::make($request->all(), [
            'chat_genius_name' => 'required|min:2|max:200',
            'chat_genius_expert' => 'required|min:2|max:200',
            'chat_genius_description' => 'required|min:2',
            'chat_genius_message' => 'required|min:2'
        ], [
            // Custom error messages
            'chat_genius_name.required' => trans('The name field is required.'),
            'chat_genius_expert.required' => trans('The expert field is required.'),
            'chat_genius_description.required' => trans('The description is required.'),
            'chat_genius_message.required' => trans('The message is required.')
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', trans('Validation failed! Please check the form.'));
        }

        if ($request->file('chat_genius_image')) {
            // Validate form
            $validator = Validator::make($request->all(), [
                'chat_genius_image' => 'required|mimes:jpg,jpeg,png,webp|max:' . $sizeLimit,
            ], [
                // Custom error messages
                'chat_genius_image.required' => trans('The image is required.'),
                'chat_genius_image.mimes' => trans('The image must be a file of type: jpg, jpeg, png, webp.'),
                'chat_genius_image.max' => trans('The image may not be greater than ' . $sizeLimit / 1024 . ' MB.')
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput()
                    ->with('error', trans('Validation failed! Please check the form.'));
            }

            // Upload Chat Genius Image
            $request->file('chat_genius_image')->move(public_path('images/chatgenius'), $uniqueId . '.' . $request->file('chat_genius_image')->getClientOriginalExtension());
            $chatgeniusImage = 'images/chatgenius/' . $uniqueId . '.' . $request->file('chat_genius_image')->getClientOriginalExtension();

            // Update Chat Genius Image
            $chatgenius = ChatGenius::where('chat_genius_id', $request->input('chat_genius_id'))->first();
            $chatgenius->chat_genius_image = $chatgeniusImage;
            $chatgenius->save();
        }

        // Update Chat Genius
        $chatgenius = ChatGenius::where('chat_genius_id', $request->input('chat_genius_id'))->first();
        $chatgenius->chat_genius_name = $request->input('chat_genius_name');
        $chatgenius->chat_genius_expert = $request->input('chat_genius_expert');
        $chatgenius->chat_genius_description = $request->input('chat_genius_description');
        $chatgenius->chat_genius_message = $request->input('chat_genius_message');
        $chatgenius->save();

        return redirect()->route('admin.chatgenius')->with('success', trans('Chat Assistant updated successfully!'));
    }

    // Chat Genius Actions
    public function actionChatgenius(Request $request)
    {
        // Find Chat Genius
        $chatgenius = ChatGenius::where('chat_genius_id', $request->id)->first();
        // Update Chat Genius
        $chatgenius->status = $chatgenius->status == 1 ? 0 : 1;
        $chatgenius->save();

        return redirect()->route('admin.chatgenius')->with('success', trans('Chat Assistant status updated successfully!'));
    }

    // Chat Genius Delete
    public function deleteChatgenius(Request $request)
    {
        // Find Chat Genius
        $chatgenius = ChatGenius::where('chat_genius_id', $request->id)->first();

        if ($chatgenius->id > 55) {
            // Update Chat Genius
            $chatgenius->status = -1;
            $chatgenius->save();

            return redirect()->route('admin.chatgenius')->with('success', trans('Chat Assistant deleted successfully!'));
        } else {
            return redirect()->route('admin.chatgenius')->with('failed', trans('Delete Chat Assistant not allowed!'));
        }
    }
}
