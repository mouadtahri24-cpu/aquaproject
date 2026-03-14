<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Session extends Model {
    protected $fillable = [
        'group_id',
        'coach_id',
        'session_date',
        'start_time',
        'end_time',
        'type',
        'objective',
    ];

    protected $casts = [
        'session_date' => 'date',
    ];

    // ===================== RELATIONS =====================

    public function group(): BelongsTo {
        return $this->belongsTo(Group::class);
    }

    public function coach(): BelongsTo {
        return $this->belongsTo(User::class, 'coach_id');
    }

    public function attendances(): HasMany {
        return $this->hasMany(Attendance::class);
    }

    public function performances(): HasMany {
        return $this->hasMany(Performance::class);
    }

    // ===================== SCOPES =====================

    public function scopeByGroup($query, $groupId) {
        return $query->where('group_id', $groupId);
    }

    public function scopeByCoach($query, $coachId) {
        return $query->where('coach_id', $coachId);
    }

    public function scopeUpcoming($query) {
        return $query->where('session_date', '>=', now()->toDateString())
                    ->orderBy('session_date');
    }

    public function scopePast($query) {
        return $query->where('session_date', '<', now()->toDateString())
                    ->orderBy('session_date', 'desc');
    }

    public function scopeTraining($query) {
        return $query->where('type', 'Entrainement');
    }

    public function scopeCompetition($query) {
        return $query->where('type', 'Competition');
    }

    // ===================== ACCESSORS =====================

    public function getFormattedDateAttribute() {
        return $this->session_date->format('d/m/Y');
    }

    public function getFormattedTimeAttribute() {
        return "{$this->start_time} - {$this->end_time}";
    }

    // ===================== METHODS =====================

    // Obtenir le nombre de présents
    public function getPresentCount() {
        return $this->attendances()
            ->whereIn('status', ['Present', 'Retard'])
            ->count();
    }

    // Obtenir le nombre d'absents
    public function getAbsentCount() {
        return $this->attendances()
            ->where('status', 'Absent')
            ->count();
    }

    // Obtenir le taux de présence
    public function getAttendanceRate() {
        $total = $this->attendances()->count();
        if ($total === 0) return 0;
        
        return round(($this->getPresentCount() / $total) * 100, 2);
    }
}
