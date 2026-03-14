<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\NotificationResource;
use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller {
    public function index() {
        $notifications = Notification::where('user_id', auth()->id())
            ->latest()
            ->get();

        return response()->json([
            'data' => NotificationResource::collection($notifications),
            'count' => $notifications->count(),
        ]);
    }

    public function show(Notification $notification) {
        if ($notification->user_id !== auth()->id()) {
            return response()->json(['message' => 'Non autorisé'], 403);
        }

        return response()->json(new NotificationResource($notification));
    }

    public function getUnread() {
        $notifications = Notification::where('user_id', auth()->id())
            ->unread()
            ->latest()
            ->get();

        return response()->json([
            'data' => NotificationResource::collection($notifications),
            'count' => $notifications->count(),
        ]);
    }

    public function countUnread() {
        $count = Notification::where('user_id', auth()->id())
            ->unread()
            ->count();

        return response()->json([
            'unread_count' => $count,
        ]);
    }

    public function markAsRead(Notification $notification) {
        if ($notification->user_id !== auth()->id()) {
            return response()->json(['message' => 'Non autorisé'], 403);
        }

        $notification->markAsRead();

        return response()->json([
            'message' => 'Notification marquée comme lue',
            'data' => new NotificationResource($notification),
        ]);
    }

    public function markAllAsRead() {
        Notification::where('user_id', auth()->id())
            ->unread()
            ->update(['is_read' => true]);

        return response()->json([
            'message' => 'Toutes les notifications sont marquées comme lues',
        ]);
    }

    public function destroy(Notification $notification) {
        if ($notification->user_id !== auth()->id()) {
            return response()->json(['message' => 'Non autorisé'], 403);
        }

        $notification->delete();

        return response()->json([
            'message' => 'Notification supprimée',
        ]);
    }

    public function destroyAll() {
        Notification::where('user_id', auth()->id())->delete();

        return response()->json([
            'message' => 'Toutes les notifications ont été supprimées',
        ]);
    }
}
