<?php
namespace App\Policies;

use App\Models\User;
use App\Models\Swimmer;

class SwimmerPolicy {
    public function viewAny(User $user): bool {
        return $user->isAdmin() || $user->isCoach();
    }

    public function view(User $user, Swimmer $swimmer): bool {
        // Admin peut voir tous les nageurs
        if ($user->isAdmin()) {
            return true;
        }

        // Coach peut voir les nageurs de ses groupes
        if ($user->isCoach()) {
            return $swimmer->group->coach_id === $user->id;
        }

        // Parent peut voir ses propres enfants
        if ($user->isParent()) {
            return $swimmer->parent_id === $user->id;
        }

        return false;
    }

    public function create(User $user): bool {
        return $user->isAdmin();
    }

    public function update(User $user, Swimmer $swimmer): bool {
        return $user->isAdmin();
    }

    public function delete(User $user, Swimmer $swimmer): bool {
        return $user->isAdmin();
    }

    public function restore(User $user, Swimmer $swimmer): bool {
        return $user->isAdmin();
    }

    public function forceDelete(User $user, Swimmer $swimmer): bool {
        return $user->isAdmin();
    }
}
