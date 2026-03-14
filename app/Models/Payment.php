<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model {
    protected $fillable = [
        'swimmer_id',
        'month',
        'amount_expected',
        'amount_paid',
        'status',
        'paid_at',
    ];

    protected $casts = [
        'amount_expected' => 'decimal:2',
        'amount_paid' => 'decimal:2',
        'paid_at' => 'datetime',
    ];

    // ===================== RELATIONS =====================

    public function swimmer(): BelongsTo {
        return $this->belongsTo(Swimmer::class);
    }

    // Obtenir le parent via swimmer
    public function parent() {
        return $this->swimmer->parent();
    }

    // ===================== SCOPES =====================

    public function scopeBySwimmer($query, $swimmerId) {
        return $query->where('swimmer_id', $swimmerId);
    }

    public function scopeByMonth($query, $month) {
        return $query->where('month', $month);
    }

    public function scopePaid($query) {
        return $query->where('status', 'Paid');
    }

    public function scopePending($query) {
        return $query->where('status', 'Pending');
    }

    public function scopePartial($query) {
        return $query->where('status', 'Partial');
    }

    public function scopeLate($query) {
        return $query->where('status', 'Late');
    }

    // ===================== ACCESSORS =====================

    public function getAmountDueAttribute() {
        return $this->amount_expected - $this->amount_paid;
    }

    public function getPercentagePaidAttribute() {
        if ($this->amount_expected == 0) return 0;
        return round(($this->amount_paid / $this->amount_expected) * 100, 2);
    }

    public function getStatusLabelAttribute() {
        return match($this->status) {
            'Paid' => '✅ Payé',
            'Partial' => '⚠️ Partiellement payé',
            'Pending' => '⏳ En attente',
            'Late' => '❌ En retard',
            default => 'Inconnu'
        };
    }

    public function getStatusColorAttribute() {
        return match($this->status) {
            'Paid' => 'green',
            'Partial' => 'orange',
            'Pending' => 'blue',
            'Late' => 'red',
            default => 'gray'
        };
    }

    // ===================== METHODS =====================

    // Enregistrer un paiement
    public function recordPayment($amount) {
        $this->amount_paid += $amount;

        if ($this->amount_paid >= $this->amount_expected) {
            $this->status = 'Paid';
            $this->paid_at = now();
        } elseif ($this->amount_paid > 0) {
            $this->status = 'Partial';
        }

        $this->save();
    }

    // Marquer comme en retard
    public function markAsLate() {
        if ($this->status !== 'Paid') {
            $this->status = 'Late';
            $this->save();
        }
    }

    // Vérifier si le paiement est en retard
    public function isOverdue() {
        if ($this->status === 'Paid') return false;
        
        $dueDate = now()
            ->setYear(substr($this->month, 0, 4))
            ->setMonth(substr($this->month, 5, 2))
            ->endOfMonth();
        
        return now() > $dueDate;
    }
}
