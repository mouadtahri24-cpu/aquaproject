<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class Swimmer extends Model {
    protected $fillable = [
        'first_name',
        'last_name',
        'birth_date',
        'parent_id',
        'group_id',
        'status',
        'level',
        'is_minor',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'is_minor' => 'boolean',
    ];

    // ===================== RELATIONS =====================

    // Un nageur appartient à un parent
    public function parent(): BelongsTo {
        return $this->belongsTo(User::class, 'parent_id');
    }

    // Un nageur appartient à un groupe
    public function group(): BelongsTo {
        return $this->belongsTo(Group::class);
    }

    // Un nageur a plusieurs présences
    public function attendances(): HasMany {
        return $this->hasMany(Attendance::class);
    }

    // Un nageur a plusieurs performances
    public function performances(): HasMany {
        return $this->hasMany(Performance::class);
    }

    // Un nageur a plusieurs paiements
    public function payments(): HasMany {
        return $this->hasMany(Payment::class);
    }

    // ===================== ACCESSORS =====================

    public function getFullNameAttribute() {
        return "{$this->first_name} {$this->last_name}";
    }

    // Calculer l'âge du nageur
    public function getAgeAttribute() {
        return Carbon::parse($this->birth_date)->age;
    }

    // Vérifier si c'est un mineur
    public function getIsMinorAttribute() {
        return $this->age < 18;
    }

    // Obtenir la catégorie d'âge
    public function getAgeCategoryAttribute() {
        $age = $this->age;
        if ($age >= 6 && $age <= 9) return 'benjamin';
        if ($age >= 10 && $age <= 12) return 'cadet';
        if ($age >= 13 && $age <= 17) return 'junior';
        return 'hors-categorie';
    }

    // ===================== SCOPES =====================

    public function scopeActive($query) {
        return $query->where('status', 'active');
    }

    public function scopeByGroup($query, $groupId) {
        return $query->where('group_id', $groupId);
    }

    public function scopeByParent($query, $parentId) {
        return $query->where('parent_id', $parentId);
    }

    // ===================== METHODS =====================

    // Obtenir le statut de paiement du mois courant
    public function getCurrentMonthPaymentStatus() {
        $month = now()->format('Y-m');
        return $this->payments()
            ->where('month', $month)
            ->first()?->status ?? 'Pending';
    }

    // Obtenir les performances du dernier mois
    public function getLastMonthPerformances() {
        return $this->performances()
            ->whereYear('created_at', now()->year)
            ->whereMonth('created_at', now()->month)
            ->get();
    }

    // Obtenir le taux de présence
    public function getAttendanceRate() {
        $total = $this->attendances()->count();
        if ($total === 0) return 0;
        
        $present = $this->attendances()
            ->whereIn('status', ['Present', 'Retard'])
            ->count();
        
        return round(($present / $total) * 100, 2);
    }
}
