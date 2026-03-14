<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Admin extends Model {
    protected $fillable = [];
    
    // Clé primaire = id qui vient de User
    public $incrementing = false;
    protected $keyType = 'int';

    // ===================== RELATIONS =====================

    // Un Admin est un User
    public function user() {
        return $this->belongsTo(User::class, 'id', 'id');
    }
}
