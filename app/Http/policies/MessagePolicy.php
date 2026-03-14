<?php
namespace App\Policies;

use App\Models\User;
use App\Models\Message;

class MessagePolicy {
    public function viewAny(User $user): bool {
        return true;
    }

    public function view(User $user, Message $message): bool {
        // L'utilisateur doit être participant à la conversation
        return $message->conversation->hasUser($user->id);
    }

    public function create(User $user): bool {
        return true;
    }

    public function update(User $user, Message $message): bool {
        // Les messages ne se modifient pas
        return false;
    }

    public function delete(User $user, Message $message): bool {
        // Seul le sender peut supprimer son message
        return $message->sender_id === $user->id;
    }

    public function markAsRead(User $user, Message $message): bool {
        // Seul le destinataire peut marquer comme lu
        return $message->conversation->hasUser($user->id) && 
               $message->sender_id !== $user->id;
    }
}
