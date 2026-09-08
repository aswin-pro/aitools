<?php

namespace App\Http\Controllers\Admin;

use App\Models\ChatGenius;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\chatgenius\createChatRequest;
use App\Http\Requests\Admin\chatgenius\updateChatRequest;
use App\Models\Chat;
use App\Models\ChatAssistant;
use Inertia\Inertia;

class ChatAssitantController extends Controller
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

        $chatgenius = ChatAssistant::query()
            ->where('status', '>=', 0)
            ->when($search, function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where(
                        'chat_assistant_name',
                        'like',
                        "%{$search}%"
                    )
                        ->orWhere(
                            'chat_assistant_expert',
                            'like',
                            "%{$search}%"
                        )
                        ->orWhere(
                            'chat_assistant_description',
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


    public function saveChatgenius(createChatRequest $request)
    {
        $uniqueId = uniqid();

        $image = $request->file('chat_assistant_image');

        $image->move(
            public_path('images/chatgenius'),
            $uniqueId . '.' . $image->getClientOriginalExtension()
        );

        $chatgeniusImage =
            'images/chatgenius/' .
            $uniqueId . '.' .
            $image->getClientOriginalExtension();

        $chatgenius = new ChatAssistant();

        $chatgenius->chat_assistant_id = $uniqueId;
        $chatgenius->chat_assistant_image = $chatgeniusImage;
        $chatgenius->chat_assistant_name = $request->chat_assistant_name;
        $chatgenius->chat_assistant_expert = $request->chat_assistant_expert;
        $chatgenius->chat_assistant_description = $request->chat_assistant_description;
        $chatgenius->chat_assistant_message = $request->chat_assistant_message;

        $chatgenius->save();

        return redirect()
            ->route('dashboard.admin.chatgenius')
            ->with(
                'success',
                'Chat Assistant created successfully!'
            );
    }


    public function updateChatgenius(updateChatRequest $request)
    {
        $chatgenius = ChatAssistant::where(
            'chat_assistant_id',
            $request->input('chat_assistant_id')
        )->first();

        if (!$chatgenius) {
            return back()->with(
                'error',
                'Chat Assistant not found!'
            );
        }

    
        if ($request->hasFile('chat_assistant_image')) {
            $uniqueId = uniqid();

            $image = $request->file('chat_assistant_image');

            $imageName =
                $uniqueId . '.' .
                $image->getClientOriginalExtension();

            $image->move(
                public_path('images/chatgenius'),
                $imageName
            );

            $chatgenius->chat_assistant_image =
                'images/chatgenius/' . $imageName;
        }

   
        $chatgenius->chat_assistant_name =
            $request->input('chat_assistant_name');

        $chatgenius->chat_assistant_expert =
            $request->input('chat_assistant_expert');

        $chatgenius->chat_assistant_description =
            $request->input('chat_assistant_description');

        $chatgenius->chat_assistant_message =
            $request->input('chat_assistant_message');

        $chatgenius->save();

        return redirect()
            ->route('dashboard.admin.chatgenius')
            ->with(
                'success',
                'Chat Assistant updated successfully!'
            );
    }

    // Chat Genius Actions
    public function actionChatgenius(Request $request)
    {
        $chatassistant = ChatAssistant::where('chat_assistant_id', $request->id)->first();
        $chatassistant->status = $chatassistant->status == 1 ? 0 : 1;
        $chatassistant->save();

        return redirect()->route('dashboard.admin.chatgenius')->with('success', 'Chat Assistant status updated successfully!');
    }

    // Chat Genius Delete

    public function deleteChatgenius(Request $request)
    {
        $chatgenius = ChatAssistant::where(
            'chat_assistant_id',
            $request->query('id')
        )->first();

        if (!$chatgenius) {
            return back()->with(
                'failed',
                'Chat Assistant not found!'
            );
        }

        $hasChats = Chat::where(
            'chat_assistant_id',
            $chatgenius->chat_assistant_id
        )->exists();

        if ($hasChats) {
            return back()->with(
                'error',
                'This Chat Assistant cannot be deleted because it has already been used.'
            );
        }

        $chatgenius->delete();

        return back()->with(
            'success',
            'Chat Assistant deleted successfully!'
        );
    }
}
