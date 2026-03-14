<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePerformanceRequest;
use App\Http\Requests\UpdatePerformanceRequest;
use App\Http\Resources\PerformanceResource;
use App\Models\Performance;
use Illuminate\Http\Request;

class PerformanceController extends Controller {
    public function index(Request $request) {
        $query = Performance::with(['swimmer', 'event', 'session']);

        if ($request->has('swimmer_id')) {
            $query->where('swimmer_id', $request->swimmer_id);
        }

        if ($request->has('event_id')) {
            $query->where('event_id', $request->event_id);
        }

        $performances = $query->orderBy('created_at', 'desc')->get();

        return response()->json([
            'data' => PerformanceResource::collection($performances),
            'count' => $performances->count(),
        ]);
    }

    public function store(StorePerformanceRequest $request) {
        $performance = Performance::create([
            'swimmer_id' => $request->swimmer_id,
            'event_id' => $request->event_id,
            'session_id' => $request->session_id,
            'month' => $request->month,
            'time_seconds' => $request->time_seconds,
            'notes' => $request->notes,
            'is_personal_record' => false,
        ]);

        // Vérifier si c'est un record personnel
        $performance->checkPersonalRecord();

        return response()->json([
            'message' => 'Performance enregistrée',
            'data' => new PerformanceResource($performance->load(['swimmer', 'event', 'session'])),
        ], 201);
    }

    public function show(Performance $performance) {
        return response()->json(new PerformanceResource($performance->load(['swimmer', 'event', 'session'])));
    }

    public function update(UpdatePerformanceRequest $request, Performance $performance) {
        $performance->update($request->validated());

        // Vérifier record personnel après mise à jour
        $performance->checkPersonalRecord();

        return response()->json([
            'message' => 'Performance mise à jour',
            'data' => new PerformanceResource($performance->load(['swimmer', 'event', 'session'])),
        ]);
    }

    public function destroy(Performance $performance) {
        $performance->delete();

        return response()->json([
            'message' => 'Performance supprimée',
        ]);
    }

    public function getBySwimmer($swimmerId) {
        $performances = Performance::where('swimmer_id', $swimmerId)->with(['swimmer', 'event', 'session'])->orderBy('created_at', 'desc')->get();
        return response()->json([
            'data' => PerformanceResource::collection($performances),
            'count' => $performances->count(),
        ]);
    }

    public function getPersonalRecords($swimmerId) {
        $records = Performance::where('swimmer_id', $swimmerId)
            ->where('is_personal_record', true)
            ->with(['swimmer', 'event'])
            ->get();

        return response()->json([
            'data' => PerformanceResource::collection($records),
            'count' => $records->count(),
        ]);
    }

    public function getBestByEvent($swimmerId, $eventId) {
        $best = Performance::where('swimmer_id', $swimmerId)
            ->where('event_id', $eventId)
            ->orderBy('time_seconds', 'asc')
            ->first();

        if (!$best) {
            return response()->json(['message' => 'Aucune performance trouvée'], 404);
        }

        return response()->json(new PerformanceResource($best->load(['swimmer', 'event', 'session'])));
    }
}
