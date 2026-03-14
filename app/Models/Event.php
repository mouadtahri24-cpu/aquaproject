<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Event extends Model {
    protected $fillable = [
        'name',
        'distance',
        'stroke',
    ];

    // ===================== RELATIONS =====================

    public function performances(): HasMany {
        return $this->hasMany(Performance::class);
    }

    // ===================== SCOPES =====================

    public function scopeByStroke($query, $stroke) {
        return $query->where('stroke', $stroke);
    }

    public function scopeByDistance($query, $distance) {
        return $query->where('distance', $distance);
    }

    // ===================== ACCESSORS =====================

    public function getDisplayNameAttribute() {
        return "{$this->distance}m {$this->stroke}";
    }

    public function getStrokeLabelAttribute() {
        return match($this->stroke) {
            'Crawl' => 'Crawl (Nage libre)',
            'Dos' => 'Dos crawlé',
            'Brasse' => 'Brasse',
            'Papillon' => 'Papillon',
            default => $this->stroke
        };
    }
}
