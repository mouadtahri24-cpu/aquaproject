<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Performance extends Model {
    protected $fillable = [
        'swimmer_id',
        'event_id',
        'session_id',
        'month',
        'time_seconds',
        'notes',
        'is_personal_record',
    ];

    protected $casts = [
        'time_seconds' => 'decimal:2',
        'is_personal_record' => 'boolean',
    ];

    // ===================== RELATIONS =====================

    public function swimmer(): BelongsTo {
        return $this->belongsTo(Swimmer::class);
    }

    public function event(): BelongsTo {
        return $this->belongsTo(Event::class);
    }

    public function session(): BelongsTo {
        return $this->belongsTo(Session::class)->nullable();
    }

    // ===================== SCOPES =====================

    public function scopeBySwimmer($query, $swimmerId) {
        return $query->where('swimmer_id', $swimmerId);
    }

    public function scopeByEvent($query, $eventId) {
        return $query->where('event_id', $eventId);
    }

    public function scopeByMonth($query, $month) {
        return $query->where('month', $month);
    }

    public function scopePersonalRecords($query) {
        return $query->where('is_personal_record', true);
    }

    public function scopeOrderByTime($query) {
        return $query->orderBy('time_seconds', 'asc');
    }

    // ===================== ACCESSORS =====================

    public function getFormattedTimeAttribute() {
        $seconds = $this->time_seconds;
        $minutes = intval($seconds / 60);
        $secs = round($seconds % 60, 2);
        
        if ($minutes > 0) {
            return sprintf("%d'%05.2f\"", $minutes, $secs);
        }
        return sprintf("%.2f\"", $seconds);
    }

    public function getRecordLabelAttribute() {
        return $this->is_personal_record ? '🏆 Record Personnel' : '';
    }

    // ===================== METHODS =====================

    // Vérifier si c'est un nouveau record personnel
    public function checkPersonalRecord() {
        $previousBest = Performance::where('swimmer_id', $this->swimmer_id)
            ->where('event_id', $this->event_id)
            ->where('id', '!=', $this->id)
            ->orderBy('time_seconds', 'asc')
            ->first();

        if (!$previousBest || $this->time_seconds < $previousBest->time_seconds) {
            $this->is_personal_record = true;
            $this->save();
            return true;
        }

        return false;
    }

    // Obtenir la progression par rapport au record personnel
    public function getProgressionAttribute() {
        $best = Performance::where('swimmer_id', $this->swimmer_id)
            ->where('event_id', $this->event_id)
            ->orderBy('time_seconds', 'asc')
            ->first();

        if (!$best || $best->id === $this->id) return 0;

        $difference = $this->time_seconds - $best->time_seconds;
        return round($difference, 2);
    }
}
