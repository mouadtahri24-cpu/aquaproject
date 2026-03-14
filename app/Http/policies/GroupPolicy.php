<?php
namespace App\Policies;

use App\Models\User;
use App\Models\Group;

class GroupPolicy {
    public function viewAny(User $user): bool {
        return $user->isAdmin() || $user->isCoach();
    }

    public function view(User $user, Group $group): bool {
        // Admin peut voir tous les groupes
        if ($user->isAdmin()) {
            return true;
        }

        // Coach peut voir ses propres groupes
        if ($user->isCoach()) {
            return $group->coach_id === $user->id;
        }

        // Parent ne peut pas voir les groupes
        return false;
    }

    public function create(User $user): bool {
        return $user->isAdmin();
    }

    public function update(User $user, Group $group): bool {
        return $user->isAdmin();
    }

    public function delete(User $user, Group $group): bool {
        return $user->isAdmin();
    }
}
