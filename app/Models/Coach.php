<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coach extends Model {
    protected $fillable = [];
    
    public $incrementing = false;
    protected $keyType = 'int';

    // ===================== RELATIONS =====================

    public function user() {
        return $this->belongsTo(User::class, 'id', 'id');
    }

    // Un coach encadre plusieurs groupes
    public function groups() {
        return $this->hasMany(Group::class, 'coach_id', 'id');
    }

    // Un coach anime plusieurs séances
    public function sessions() {
        return $this->hasMany(Session::class, 'coach_id', 'id');
    }
}
