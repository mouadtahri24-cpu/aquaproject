<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ParentUser extends Model {
    protected $table = 'parents';
    protected $fillable = [];
    
    public $incrementing = false;
    protected $keyType = 'int';

    // ===================== RELATIONS =====================

    public function user() {
        return $this->belongsTo(User::class, 'id', 'id');
    }

    // Un parent a plusieurs enfants (nageurs)
    public function swimmers() {
        return $this->hasMany(Swimmer::class, 'parent_id', 'id');
    }
}
