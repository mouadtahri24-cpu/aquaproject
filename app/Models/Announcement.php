<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Announcement extends Model {
    protected $fillable = [
        'created_by',
        'title',
        'content',
        'type',
        'is_published',
        'published_at',
        'expires_at',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'published_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    // ===================== RELATIONS =====================

    public function creator(): BelongsTo {
        return $this->belongsTo(User::class, 'created_by');
    }

    // ===================== SCOPES =====================

    public function scopePublished($query) {
        return $query->where('is_published', true)
                    ->where(function ($q) {
                        $q->whereNull('expires_at')
                          ->orWhere('expires_at', '>=', now());
                    });
    }

    public function scopeByType($query, $type) {
        return $query->where('type', $type);
    }

    public function scopeRecent($query) {
        return $query->orderBy('published_at', 'desc');
    }

    public function scopeAdmin($query) {
        return $query->whereHas('creator', function ($q) {
            $q->where('role', 'admin');
        });
    }

    public function scopeCoach($query) {
        return $query->whereHas('creator', function ($q) {
            $q->where('role', 'coach');
        });
    }

    // ===================== ACCESSORS =====================

    public function getTypeIconAttribute() {
        return match($this->type) {
            'info' => 'ℹ️',
            'urgent' => '🚨',
            'event' => '📅',
            'announcement' => '📣',
            default => '📌'
        };
    }

    public function getTypeColorAttribute() {
        return match($this->type) {
            'info' => 'blue',
            'urgent' => 'red',
            'event' => 'green',
            'announcement' => 'purple',
            default => 'gray'
        };
    }

    public function getIsExpiredAttribute() {
        return $this->expires_at && now() > $this->expires_at;
    }

    public function getIsActiveAttribute() {
        return $this->is_published && !$this->is_expired;
    }

    public function getFormattedDateAttribute() {
        return $this->published_at?->format('d/m/Y H:i') ?? 'Non publié';
    }
}
