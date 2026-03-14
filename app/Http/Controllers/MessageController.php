<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMessageRequest;
use App\Http\Resources\MessageResource;
use App\Models\Message;
use Illuminate\Http\Request;

class MessageController extends Controller {
    public function index(Request $request) {
        $query = Message::with(['sender', 'conversation']);

        if ($request->has('conversation_id')) {
            $query->where('conversation_id', $request->conversation_id);
        }

        $messages = $query->orderBy('created_at', 'asc')->get();

        return response()->json([
            'data' => MessageResource::collection($messages),
            'count' => $messages->count(),
        ]);
    }

    public function store(StoreMessageRequest $request) {
        $message = Message::create([
            'conversation_id' => $request->conversation_id,
            'sender_id' => auth()->id(),
            'content' => $request->content,
        ]);

        return response()->json([
            'message' => 'Message envoyé',
            'data' => new MessageResource($message->load(['sender', 'conversation'])),
        ], 201);
    }

    public function show(Message $message) {
        return response()->json(new MessageResource($message->load(['sender', 'conversation'])));
    }

    public function markAsRead(Message $message) {
        if ($message->sender_id === auth()->id()) {
            return response()->json(['message' => 'Vous ne pouvez pas marquer votre propre message'], 403);
        }

        $message->markAsRead();

        return response()->json([
            'message' => 'Message marqué comme lu',
            'data' => new MessageResource($message->load(['sender', 'conversation'])),
        ]);
    }

    public function destroy(Message $message) {
        if ($message->sender_id !== auth()->id()) {
            return response()->json(['message' => 'Non autorisé'], 403);
        }

        $message->delete();

        return response()->json([
            'message' => 'Message supprimé',
        ]);
    }

    public function getUnread() {
        $messages = Message::unread()
            ->with(['sender', 'conversation'])
            ->whereHas('conversation', function ($q) {
                $q->withUser(auth()->id());
            })
            ->get();

        return response()->json([
            'data' => MessageResource::collection($messages),
            'count' => $messages->count(),
        ]);
    }
}
