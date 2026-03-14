<?php
namespace App\Policies;

use App\Models\User;
use App\Models\Announcement;

class AnnouncementPolicy {
    public function viewAny(User $user): bool {
        return true; // Tous peuvent voir
    }

    public function view(User $user, Announcement $announcement): bool {
        // Tous peuvent voir les annonces publiées
        if ($announcement->is_published && !$announcement->is_expired) {
            return true;
        }

        // Le créateur peut voir même si non publiée
        return $announcement->created_by === $user->id;
    }

    public function create(User $user): bool {
        return $user->isAdmin() || $user->isCoach();
    }

    public function update(User $user, Announcement $announcement): bool {
        // Seul le créateur (Admin/Coach) peut modifier
        return $announcement->created_by === $user->id;
    }

    public function delete(User $user, Announcement $announcement): bool {
        // Admin peut supprimer toutes les annonces
        if ($user->isAdmin()) {
            return true;
        }

        // Coach ne peut supprimer que ses propres annonces
        return $announcement->created_by === $user->id && $user->isCoach();
    }
}
