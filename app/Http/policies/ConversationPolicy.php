<?php
namespace App\Policies;

use App\Models\User;
use App\Models\Conversation;

class ConversationPolicy {
    public function viewAny(User $user): bool {
        return true; // Tous les utilisateurs connectés
    }

    public function view(User $user, Conversation $conversation): bool {
        // L'utilisateur doit être l'un des participants
        return $conversation->participant_a_id === $user->id || 
               $conversation->participant_b_id === $user->id;
    }

    public function create(User $user): bool {
        // Tous peuvent créer une conversation
        return true;
    }

    public function update(User $user, Conversation $conversation): bool {
        // Les conversations ne se modifient pas
        return false;
    }

    public function delete(User $user, Conversation $conversation): bool {
        // Seul un participant peut supprimer
        return $conversation->participant_a_id === $user->id || 
               $conversation->participant_b_id === $user->id;
    }

    public function checkParentRestriction(User $userA, User $userB): bool {
        // Les parents NE PEUVENT PAS converser entre eux
        if ($userA->isParent() && $userB->isParent()) {
            return false;
        }
        return true;
    }
}
