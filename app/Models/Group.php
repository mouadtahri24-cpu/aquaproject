<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Group extends Model {
    protected $fillable = [
        'name',
        'level',
        'schedule_label',
        'coach_id',
        'age_category',
        'min_age',
        'max_age',
        'monthly_fee',
        'is_active',
    ];

    protected $casts = [
        'monthly_fee' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    // ===================== RELATIONS =====================

    // Un groupe est encadré par un coach
    public function coach(): BelongsTo {
        return $this->belongsTo(User::class, 'coach_id');
    }

    // Un groupe contient plusieurs nageurs (mineurs)
    public function swimmers(): HasMany {
        return $this->hasMany(Swimmer::class);
    }

    // Un groupe organise plusieurs séances
    public function sessions(): HasMany {
        return $this->hasMany(Session::class);
    }

    // ===================== SCOPES =====================

    public function scopeActive($query) {
        return $query->where('is_active', true);
    }

    public function scopeByCategory($query, $category) {
        return $query->where('age_category', $category);
    }

    public function scopeBenjamin($query) {
        return $query->where('age_category', 'benjamin');
    }

    public function scopeCadet($query) {
        return $query->where('age_category', 'cadet');
    }

    public function scopeJunior($query) {
        return $query->where('age_category', 'junior');
    }

    // ===================== ACCESSORS =====================

    public function getAgeRangeAttribute() {
        return "{$this->min_age}-{$this->max_age} ans";
    }

    public function getCategoryLabelAttribute() {
        return match($this->age_category) {
            'benjamin' => 'Benjamin (6-9 ans)',
            'cadet' => 'Cadet (10-12 ans)',
            'junior' => 'Junior (13-17 ans)',
            default => 'Catégorie inconnue'
        };
    }

    // Vérifier si un nageur peut rejoindre ce groupe
    public function canAcceptSwimmer(Swimmer $swimmer) {
        $age = $swimmer->getAgeAttribute();
        return $age >= $this->min_age && $age <= $this->max_age;
    }
}
