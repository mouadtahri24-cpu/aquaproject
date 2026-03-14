<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSessionRequest;
use App\Http\Requests\UpdateSessionRequest;
use App\Http\Resources\SessionResource;
use App\Models\Session;
use Illuminate\Http\Request;

class SessionController extends Controller {
    public function index() {
        $sessions = Session::with(['group', 'coach'])->get();
        return response()->json([
            'data' => SessionResource::collection($sessions),
            'count' => $sessions->count(),
        ]);
    }

    public function store(StoreSessionRequest $request) {
        $session = Session::create([
            'group_id' => $request->group_id,
            'coach_id' => $request->coach_id,
            'session_date' => $request->session_date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'type' => $request->type,
            'objective' => $request->objective,
        ]);

        return response()->json([
            'message' => 'Séance créée avec succès',
            'data' => new SessionResource($session->load(['group', 'coach'])),
        ], 201);
    }

    public function show(Session $session) {
        return response()->json(new SessionResource($session->load(['group', 'coach'])));
    }

    public function update(UpdateSessionRequest $request, Session $session) {
        $session->update($request->validated());

        return response()->json([
            'message' => 'Séance mise à jour',
            'data' => new SessionResource($session->load(['group', 'coach'])),
        ]);
    }

    public function destroy(Session $session) {
        $session->delete();

        return response()->json([
            'message' => 'Séance supprimée',
        ]);
    }

    public function getByGroup($groupId) {
        $sessions = Session::where('group_id', $groupId)->with(['group', 'coach'])->get();
        return response()->json([
            'data' => SessionResource::collection($sessions),
            'count' => $sessions->count(),
        ]);
    }

    public function getByCoach($coachId) {
        $sessions = Session::where('coach_id', $coachId)->with(['group', 'coach'])->get();
        return response()->json([
            'data' => SessionResource::collection($sessions),
            'count' => $sessions->count(),
        ]);
    }

    public function getUpcoming() {
        $sessions = Session::upcoming()->with(['group', 'coach'])->get();
        return response()->json([
            'data' => SessionResource::collection($sessions),
            'count' => $sessions->count(),
        ]);
    }
}
