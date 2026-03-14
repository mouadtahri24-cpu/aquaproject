<?php
namespace App\Policies;

use App\Models\User;
use App\Models\Session;

class SessionPolicy {
    public function viewAny(User $user): bool {
        return $user->isAdmin() || $user->isCoach() || $user->isParent();
    }

    public function view(User $user, Session $session): bool {
        // Admin peut voir toutes les sessions
        if ($user->isAdmin()) {
            return true;
        }

        // Coach peut voir ses propres sessions
        if ($user->isCoach()) {
            return $session->coach_id === $user->id;
        }

        // Parent peut voir les sessions de ses enfants
        if ($user->isParent()) {
            return $session->group->swimmers()
                ->where('parent_id', $user->id)
                ->exists();
        }

        return false;
    }

    public function create(User $user): bool {
        return $user->isAdmin();
    }

    public function update(User $user, Session $session): bool {
        return $user->isAdmin();
    }

    public function delete(User $user, Session $session): bool {
        return $user->isAdmin();
    }
}
