<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAnnouncementRequest;
use App\Http\Requests\UpdateAnnouncementRequest;
use App\Http\Resources\AnnouncementResource;
use App\Models\Announcement;
use Illuminate\Http\Request;

class AnnouncementController extends Controller {
    public function index(Request $request) {
        $query = Announcement::with('creator');

        if ($request->has('published_only') && $request->published_only) {
            $query->published();
        }

        if ($request->has('type')) {
            $query->byType($request->type);
        }

        $announcements = $query->recent()->get();

        return response()->json([
            'data' => AnnouncementResource::collection($announcements),
            'count' => $announcements->count(),
        ]);
    }

    public function store(StoreAnnouncementRequest $request) {
        $announcement = Announcement::create([
            'created_by' => auth()->id(),
            'title' => $request->title,
            'content' => $request->content,
            'type' => $request->type ?? 'announcement',
            'is_published' => $request->is_published ?? true,
            'published_at' => now(),
            'expires_at' => $request->expires_at,
        ]);

        return response()->json([
            'message' => 'Annonce créée',
            'data' => new AnnouncementResource($announcement->load('creator')),
        ], 201);
    }

    public function show(Announcement $announcement) {
        return response()->json(new AnnouncementResource($announcement->load('creator')));
    }

    public function update(UpdateAnnouncementRequest $request, Announcement $announcement) {
        $announcement->update($request->validated());

        return response()->json([
            'message' => 'Annonce mise à jour',
            'data' => new AnnouncementResource($announcement->load('creator')),
        ]);
    }

    public function destroy(Announcement $announcement) {
        $announcement->delete();

        return response()->json([
            'message' => 'Annonce supprimée',
        ]);
    }

    public function getPublished() {
        $announcements = Announcement::published()->recent()->get();
        return response()->json([
            'data' => AnnouncementResource::collection($announcements),
            'count' => $announcements->count(),
        ]);
    }

    public function getByType($type) {
        $announcements = Announcement::byType($type)->published()->recent()->get();
        return response()->json([
            'data' => AnnouncementResource::collection($announcements),
            'count' => $announcements->count(),
        ]);
    }

    public function notifyAll(Request $request, Announcement $announcement) {
        $users = \App\Models\User::where('is_active', true)->get();

        foreach ($users as $user) {
            \App\Models\Notification::create([
                'user_id' => $user->id,
                'type' => 'announcement',
                'title' => $announcement->title,
                'body' => $announcement->content,
            ]);
        }

        return response()->json([
            'message' => 'Notifications envoyées à ' . $users->count() . ' utilisateurs',
        ]);
    }
}
