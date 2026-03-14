<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attendance extends Model {
    protected $fillable = [
        'session_id',
        'swimmer_id',
        'status',
        'reason',
    ];

    // ===================== RELATIONS =====================

    public function session(): BelongsTo {
        return $this->belongsTo(Session::class);
    }

    public function swimmer(): BelongsTo {
        return $this->belongsTo(Swimmer::class);
    }

    // ===================== SCOPES =====================

    public function scopeBySession($query, $sessionId) {
        return $query->where('session_id', $sessionId);
    }

    public function scopeBySwimmer($query, $swimmerId) {
        return $query->where('swimmer_id', $swimmerId);
    }

    public function scopePresent($query) {
        return $query->whereIn('status', ['Present', 'Retard']);
    }

    public function scopeAbsent($query) {
        return $query->where('status', 'Absent');
    }

    public function scopeJustified($query) {
        return $query->where('status', 'Justifie');
    }

    // ===================== ACCESSORS =====================

    public function getIsAbsentAttribute() {
        return $this->status === 'Absent';
    }

    public function getIsPresentAttribute() {
        return in_array($this->status, ['Present', 'Retard']);
    }

    public function getStatusLabelAttribute() {
        return match($this->status) {
            'Present' => 'Présent',
            'Absent' => 'Absent',
            'Justifie' => 'Excusé',
            'Retard' => 'Retard',
            default => 'Inconnu'
        };
    }
}
