<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEventRequest;
use App\Http\Requests\UpdateEventRequest;
use App\Http\Resources\EventResource;
use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller {
    public function index() {
        $events = Event::all();

        return response()->json([
            'data' => EventResource::collection($events),
            'count' => $events->count(),
        ]);
    }

    public function store(StoreEventRequest $request) {
        $event = Event::create([
            'name' => $request->name,
            'distance' => $request->distance,
            'stroke' => $request->stroke,
        ]);

        return response()->json([
            'message' => 'Épreuve créée',
            'data' => new EventResource($event),
        ], 201);
    }

    public function show(Event $event) {
        return response()->json(new EventResource($event));
    }

    public function update(UpdateEventRequest $request, Event $event) {
        $event->update($request->validated());

        return response()->json([
            'message' => 'Épreuve mise à jour',
            'data' => new EventResource($event),
        ]);
    }

    public function destroy(Event $event) {
        $event->delete();

        return response()->json([
            'message' => 'Épreuve supprimée',
        ]);
    }

    public function getByStroke($stroke) {
        $events = Event::byStroke($stroke)->get();

        return response()->json([
            'data' => EventResource::collection($events),
            'count' => $events->count(),
        ]);
    }
}
