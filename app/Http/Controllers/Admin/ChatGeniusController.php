<?php

namespace App\Http\Controllers\Admin;

use App\Models\ChatGenius;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\chatgenius\createChatRequest;
use App\Http\Requests\Admin\chatgenius\updateChatRequest;
use App\Models\Chat;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;

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
    public function index(Request $request)
    {
        $perPage = $request->integer('per_page', 10);
        $search = $request->input('search');

        $chatgenius = ChatGenius::query()
            ->where('status', '>=', 0)
            ->when($search, function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where(
                        'chat_genius_name',
                        'like',
                        "%{$search}%"
                    )
                        ->orWhere(
                            'chat_genius_expert',
                            'like',
                            "%{$search}%"
                        )
                        ->orWhere(
                            'chat_genius_description',
                            'like',
                            "%{$search}%"
                        );
                });
            })
            ->orderBy('id', 'desc')
            ->paginate($perPage)
            ->withQueryString();

        return Inertia::render('admin/chat-genius/index', [
            'chatgenius' => $chatgenius,
        ]);
    }

    // public function createChatgenius()
    // {
    //     // Create Chat Genius
    //     return view('admin.pages.chatgenius.create');
    // }

    public function saveChatgenius(createChatRequest $request)
    {
        $uniqueId = uniqid();

        $image = $request->file('chat_genius_image');

        $image->move(
            public_path('images/chatgenius'),
            $uniqueId . '.' . $image->getClientOriginalExtension()
        );

        $chatgeniusImage =
            'images/chatgenius/' .
            $uniqueId . '.' .
            $image->getClientOriginalExtension();

        $chatgenius = new ChatGenius();

        $chatgenius->chat_genius_id = $uniqueId;
        $chatgenius->chat_genius_image = $chatgeniusImage;
        $chatgenius->chat_genius_name = $request->chat_genius_name;
        $chatgenius->chat_genius_expert = $request->chat_genius_expert;
        $chatgenius->chat_genius_description = $request->chat_genius_description;
        $chatgenius->chat_genius_message = $request->chat_genius_message;

        $chatgenius->save();

        return redirect()
            ->route('dashboard.admin.chatgenius')
            ->with(
                'success',
                trans('Chat Assistant created successfully!')
            );
    }

    // public function editChatgenius(Request $request, $id)
    // {
    //     // Find Chat Genius
    //     $chatgenius = ChatGenius::where('chat_genius_id', $id)->first();

    //     if ($chatgenius->id > 55) {
    //         if ($chatgenius == null) {
    //             return redirect()->route('admin.chatgenius')->with('failed', trans('Chat Assistant not found!'));
    //         }

    //         return view('admin.pages.chatgenius.edit', compact('chatgenius'));
    //     } else {
    //         return redirect()->route('admin.chatgenius')->with('failed', trans('Update Chat Assistant not allowed!'));
    //     }
    // }

    public function updateChatgenius(updateChatRequest $request)
    {
        $chatgenius = ChatGenius::where(
            'chat_genius_id',
            $request->input('chat_genius_id')
        )->first();

        if (!$chatgenius) {
            return back()->with(
                'error',
                trans('Chat Assistant not found!')
            );
        }

        /*
     * Update image if a new image is selected
     */
        if ($request->hasFile('chat_genius_image')) {
            $uniqueId = uniqid();

            $image = $request->file('chat_genius_image');

            $imageName =
                $uniqueId . '.' .
                $image->getClientOriginalExtension();

            $image->move(
                public_path('images/chatgenius'),
                $imageName
            );

            $chatgenius->chat_genius_image =
                'images/chatgenius/' . $imageName;
        }

        /*
     * Update Chat Assistant details
     */
        $chatgenius->chat_genius_name =
            $request->input('chat_genius_name');

        $chatgenius->chat_genius_expert =
            $request->input('chat_genius_expert');

        $chatgenius->chat_genius_description =
            $request->input('chat_genius_description');

        $chatgenius->chat_genius_message =
            $request->input('chat_genius_message');

        $chatgenius->save();

        return redirect()
            ->route('dashboard.admin.chatgenius')
            ->with(
                'success',
                trans('Chat Assistant updated successfully!')
            );
    }

    // Chat Genius Actions
    public function actionChatgenius(Request $request)
    {
        // Find Chat Genius
        $chatgenius = ChatGenius::where('chat_genius_id', $request->id)->first();
        // Update Chat Genius
        $chatgenius->status = $chatgenius->status == 1 ? 0 : 1;
        $chatgenius->save();

        return redirect()->route('dashboard.admin.chatgenius')->with('success', trans('Chat Assistant status updated successfully!'));
    }

    // Chat Genius Delete

    public function deleteChatgenius(Request $request)
    {
        $chatgenius = ChatGenius::where(
            'chat_genius_id',
            $request->query('id')
        )->first();

        if (!$chatgenius) {
            return back()->with(
                'failed',
                trans('Chat Assistant not found!')
            );
        }

        // Check whether this Chat Assistant has been used
        $hasChats = Chat::where(
            'chat_genius_id',
            $chatgenius->chat_genius_id
        )->exists();

        if ($hasChats) {
            return back()->with(
                'error',
                trans('This Chat Assistant cannot be deleted because it has already been used.')
            );
        }

        // Permanently delete Chat Assistant
        $chatgenius->delete();

        return back()->with(
            'success',
            trans('Chat Assistant deleted successfully!')
        );
    }

    // public function deleteChatgenius(Request $request)
    // {
    //     // Find Chat Genius
    //     $chatgenius = ChatGenius::where('chat_genius_id', $request->id)->first();

    //     if ($chatgenius->id > 55) {
    //         // Update Chat Genius
    //         $chatgenius->status = -1;
    //         $chatgenius->save();

    //         return redirect()->route('admin.chatgenius')->with('success', trans('Chat Assistant deleted successfully!'));
    //     } else {
    //         return redirect()->route('admin.chatgenius')->with('failed', trans('Delete Chat Assistant not allowed!'));
    //     }
    // }
}
