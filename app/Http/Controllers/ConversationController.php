<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreConversationRequest;
use App\Http\Resources\ConversationResource;
use App\Http\Resources\MessageResource;
use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Http\Request;

class ConversationController extends Controller {
    public function index() {
        $conversations = Conversation::withUser(auth()->id())
            ->with(['participantA', 'participantB', 'messages'])
            ->latest()
            ->get();

        return response()->json([
            'data' => ConversationResource::collection($conversations),
            'count' => $conversations->count(),
        ]);
    }

    public function store(StoreConversationRequest $request) {
        $conversation = Conversation::create([
            'participant_a_id' => auth()->id(),
            'participant_b_id' => $request->participant_b_id,
        ]);

        return response()->json([
            'message' => 'Conversation créée',
            'data' => new ConversationResource($conversation->load(['participantA', 'participantB'])),
        ], 201);
    }

    public function show(Conversation $conversation) {
        // Vérifier que l'utilisateur est participant
        if (!$conversation->hasUser(auth()->id())) {
            return response()->json(['message' => 'Non autorisé'], 403);
        }

        $conversation->load(['participantA', 'participantB', 'messages']);

        return response()->json(new ConversationResource($conversation));
    }

    public function destroy(Conversation $conversation) {
        if (!$conversation->hasUser(auth()->id())) {
            return response()->json(['message' => 'Non autorisé'], 403);
        }

        $conversation->delete();

        return response()->json([
            'message' => 'Conversation supprimée',
        ]);
    }

    public function markAsRead(Conversation $conversation) {
        if (!$conversation->hasUser(auth()->id())) {
            return response()->json(['message' => 'Non autorisé'], 403);
        }

        $conversation->markAsRead(auth()->id());

        return response()->json([
            'message' => 'Messages marqués comme lus',
        ]);
    }
}
