<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Conversation extends Model {
    protected $fillable = [
        'participant_a_id',
        'participant_b_id',
    ];

    // ===================== RELATIONS =====================

    public function participantA(): BelongsTo {
        return $this->belongsTo(User::class, 'participant_a_id');
    }

    public function participantB(): BelongsTo {
        return $this->belongsTo(User::class, 'participant_b_id');
    }

    public function messages(): HasMany {
        return $this->hasMany(Message::class)->orderBy('created_at', 'asc');
    }

    // ===================== SCOPES =====================

    public function scopeWithUser($query, $userId) {
        return $query->where(function ($q) use ($userId) {
            $q->where('participant_a_id', $userId)
              ->orWhere('participant_b_id', $userId);
        });
    }

    // ===================== ACCESSORS =====================

    // Obtenir l'autre participant
    public function getOtherParticipant($userId) {
        return $this->participant_a_id === $userId 
            ? $this->participantB 
            : $this->participantA;
    }

    // Vérifier si c'est une conversation Parent-Parent (interdite)
    public function getIsParentToParentAttribute() {
        return $this->participantA->isParent() && $this->participantB->isParent();
    }

    // Obtenir le dernier message
    public function getLastMessageAttribute() {
        return $this->messages()->latest()->first();
    }

    // Obtenir le nombre de messages non lus
    public function getUnreadCountAttribute($userId) {
        return $this->messages()
            ->where('sender_id', '!=', $userId)
            ->whereNull('read_at')
            ->count();
    }

    // Obtenir la date formatée du dernier message
    public function getFormattedLastMessageDateAttribute() {
        $lastMessage = $this->last_message;
        if (!$lastMessage) return 'Aucun message';
        
        return $lastMessage->created_at->diffForHumans();
    }

    // ===================== METHODS =====================

    // Vérifier si un utilisateur est participant
    public function hasUser($userId) {
        return $this->participant_a_id === $userId || $this->participant_b_id === $userId;
    }

    // Marquer tous les messages comme lus
    public function markAsRead($userId) {
        $this->messages()
            ->where('sender_id', '!=', $userId)
            ->update(['read_at' => now()]);
    }

    // Obtenir un résumé de la conversation
    public function getSummary($userId, $limit = 5) {
        return $this->messages()
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get()
            ->reverse();
    }
}
