<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notification extends Model {
    protected $fillable = [
        'user_id',
        'coach_id',
        'type',
        'title',
        'body',
        'is_read',
    ];

    protected $casts = [
        'is_read' => 'boolean',
    ];

    // ===================== RELATIONS =====================

    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }

    public function coach(): BelongsTo {
        return $this->belongsTo(User::class, 'coach_id')->nullable();
    }

    // ===================== SCOPES =====================

    public function scopeByUser($query, $userId) {
        return $query->where('user_id', $userId);
    }

    public function scopeUnread($query) {
        return $query->where('is_read', false);
    }

    public function scopeRead($query) {
        return $query->where('is_read', true);
    }

    public function scopeByType($query, $type) {
        return $query->where('type', $type);
    }

    public function scopeRecent($query) {
        return $query->orderBy('created_at', 'desc');
    }

    public function scopeMessage($query) {
        return $query->where('type', 'message');
    }

    public function scopeAnnouncement($query) {
        return $query->where('type', 'announcement');
    }

    public function scopePaymentAlert($query) {
        return $query->where('type', 'payment_alert');
    }

    // ===================== ACCESSORS =====================

    public function getTypeIconAttribute() {
        return match($this->type) {
            'message' => '💬',
            'announcement' => '📣',
            'payment_alert' => '💰',
            default => '🔔'
        };
    }

    public function getTypeColorAttribute() {
        return match($this->type) {
            'message' => 'blue',
            'announcement' => 'green',
            'payment_alert' => 'orange',
            default => 'gray'
        };
    }

    public function getStatusLabelAttribute() {
        return $this->is_read ? '✓ Lu' : '● Non lu';
    }

    public function getFormattedDateAttribute() {
        return $this->created_at->diffForHumans();
    }

    public function getFullMessageAttribute() {
        return "{$this->type_icon} {$this->title}: {$this->body}";
    }

    // ===================== METHODS =====================

    // Marquer comme lu
    public function markAsRead() {
        $this->update(['is_read' => true]);
    }

    // Marquer comme non lu
    public function markAsUnread() {
        $this->update(['is_read' => false]);
    }

    // Obtenir un aperçu du message
    public function getPreview($length = 100) {
        return strlen($this->body) > $length
            ? substr($this->body, 0, $length) . '...'
            : $this->body;
    }
}
