<?php
namespace App\Policies;

use App\Models\User;
use App\Models\Notification;

class NotificationPolicy {
    public function viewAny(User $user): bool {
        return true;
    }

    public function view(User $user, Notification $notification): bool {
        // L'utilisateur ne peut voir que ses propres notifications
        return $notification->user_id === $user->id;
    }

    public function create(User $user): bool {
        // Seul l'Admin peut créer des notifications
        return $user->isAdmin();
    }

    public function update(User $user, Notification $notification): bool {
        // Les notifications ne se modifient pas
        return false;
    }

    public function delete(User $user, Notification $notification): bool {
        // Le user peut supprimer sa propre notification
        // L'Admin peut supprimer toutes
        return $notification->user_id === $user->id || $user->isAdmin();
    }

    public function markAsRead(User $user, Notification $notification): bool {
        // L'utilisateur ne peut marquer que ses propres notifications
        return $notification->user_id === $user->id;
    }
}
