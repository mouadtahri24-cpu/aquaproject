<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Message extends Model {
    protected $fillable = [
        'conversation_id',
        'sender_id',
        'content',
        'read_at',
    ];

    protected $casts = [
        'read_at' => 'datetime',
    ];

    // ===================== RELATIONS =====================

    public function conversation(): BelongsTo {
        return $this->belongsTo(Conversation::class);
    }

    public function sender(): BelongsTo {
        return $this->belongsTo(User::class, 'sender_id');
    }

    // ===================== SCOPES =====================

    public function scopeByConversation($query, $conversationId) {
        return $query->where('conversation_id', $conversationId);
    }

    public function scopeBySender($query, $senderId) {
        return $query->where('sender_id', $senderId);
    }

    public function scopeUnread($query) {
        return $query->whereNull('read_at');
    }

    public function scopeRead($query) {
        return $query->whereNotNull('read_at');
    }

    public function scopeRecent($query) {
        return $query->orderBy('created_at', 'desc');
    }

    // ===================== ACCESSORS =====================

    public function getIsReadAttribute() {
        return $this->read_at !== null;
    }

    public function getFormattedDateAttribute() {
        return $this->created_at->format('d/m/Y H:i');
    }

    public function getFormattedTimeAttribute() {
        return $this->created_at->format('H:i');
    }

    public function getIsToday Attribute() {
        return $this->created_at->isToday();
    }

    public function getReadStatusAttribute() {
        return $this->is_read ? '✓✓' : '✓';
    }

    // ===================== METHODS =====================

    // Marquer comme lu
    public function markAsRead() {
        $this->update(['read_at' => now()]);
    }

    // Obtenir un aperçu du message (troncé)
    public function getPreview($length = 50) {
        return strlen($this->content) > $length
            ? substr($this->content, 0, $length) . '...'
            : $this->content;
    }
}
