<?php
namespace App\Policies;

use App\Models\User;
use App\Models\Payment;

class PaymentPolicy {
    public function viewAny(User $user): bool {
        return $user->isAdmin();
    }

    public function view(User $user, Payment $payment): bool {
        // Admin peut voir tous les paiements
        if ($user->isAdmin()) {
            return true;
        }

        // Parent peut voir les paiements de ses enfants
        if ($user->isParent()) {
            return $payment->swimmer->parent_id === $user->id;
        }

        return false;
    }

    public function create(User $user): bool {
        return $user->isAdmin();
    }

    public function update(User $user, Payment $payment): bool {
        return $user->isAdmin();
    }

    public function delete(User $user, Payment $payment): bool {
        return $user->isAdmin();
    }

    public function recordPayment(User $user, Payment $payment): bool {
        return $user->isAdmin();
    }
}
