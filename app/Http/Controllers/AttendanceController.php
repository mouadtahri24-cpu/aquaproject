<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAttendanceRequest;
use App\Http\Requests\UpdateAttendanceRequest;
use App\Http\Resources\AttendanceResource;
use App\Models\Attendance;
use Illuminate\Http\Request;

class AttendanceController extends Controller {
    public function index(Request $request) {
        $query = Attendance::with(['session', 'swimmer']);

        if ($request->has('session_id')) {
            $query->where('session_id', $request->session_id);
        }

        if ($request->has('swimmer_id')) {
            $query->where('swimmer_id', $request->swimmer_id);
        }

        $attendances = $query->get();

        return response()->json([
            'data' => AttendanceResource::collection($attendances),
            'count' => $attendances->count(),
        ]);
    }

    public function store(StoreAttendanceRequest $request) {
        $attendance = Attendance::updateOrCreate(
            [
                'session_id' => $request->session_id,
                'swimmer_id' => $request->swimmer_id,
            ],
            [
                'status' => $request->status,
                'reason' => $request->reason,
            ]
        );

        return response()->json([
            'message' => 'Présence enregistrée',
            'data' => new AttendanceResource($attendance->load(['session', 'swimmer'])),
        ], 201);
    }

    public function show(Attendance $attendance) {
        return response()->json(new AttendanceResource($attendance->load(['session', 'swimmer'])));
    }

    public function update(UpdateAttendanceRequest $request, Attendance $attendance) {
        $attendance->update($request->validated());

        return response()->json([
            'message' => 'Présence mise à jour',
            'data' => new AttendanceResource($attendance->load(['session', 'swimmer'])),
        ]);
    }

    public function destroy(Attendance $attendance) {
        $attendance->delete();

        return response()->json([
            'message' => 'Présence supprimée',
        ]);
    }

    public function getBySession($sessionId) {
        $attendances = Attendance::where('session_id', $sessionId)->with(['session', 'swimmer'])->get();
        return response()->json([
            'data' => AttendanceResource::collection($attendances),
            'count' => $attendances->count(),
        ]);
    }

    public function getBySwimmer($swimmerId) {
        $attendances = Attendance::where('swimmer_id', $swimmerId)->with(['session', 'swimmer'])->get();
        return response()->json([
            'data' => AttendanceResource::collection($attendances),
            'count' => $attendances->count(),
        ]);
    }
}
