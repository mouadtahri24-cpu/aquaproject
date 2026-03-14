<?php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable {
    use HasApiTokens, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'telephone',
        'role',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'password' => 'hashed',
        'email_verified_at' => 'datetime',
    ];

    // ===================== RELATIONS =====================

    // Un User peut être Admin (héritage)
    public function admin() {
        return $this->hasOne(Admin::class, 'id', 'id');
    }

    // Un User peut être Coach (héritage)
    public function coach() {
        return $this->hasOne(Coach::class, 'id', 'id');
    }

    // Un User peut être Parent (héritage)
    public function parent() {
        return $this->hasOne(ParentUser::class, 'id', 'id');
    }

    // Coach : Un coach encadre plusieurs groupes
    public function groups() {
        return $this->hasMany(Group::class, 'coach_id');
    }

    // Coach : Un coach anime plusieurs séances
    public function sessions() {
        return $this->hasMany(Session::class, 'coach_id');
    }

    // Parent : Un parent a plusieurs enfants (nageurs)
    public function swimmers() {
        return $this->hasMany(Swimmer::class, 'parent_id');
    }

    // Admin : Crée des annonces
    public function announcements() {
        return $this->hasMany(Announcement::class, 'created_by');
    }

    // Messagerie : Participant A
    public function conversationsAsA() {
        return $this->hasMany(Conversation::class, 'participant_a_id');
    }

    // Messagerie : Participant B
    public function conversationsAsB() {
        return $this->hasMany(Conversation::class, 'participant_b_id');
    }

    // Messages envoyés
    public function messages() {
        return $this->hasMany(Message::class, 'sender_id');
    }

    // Notifications reçues
    public function notifications() {
        return $this->hasMany(Notification::class, 'user_id');
    }

    // ===================== SCOPES =====================

    public function scopeAdmins($query) {
        return $query->where('role', 'admin');
    }

    public function scopeCoachs($query) {
        return $query->where('role', 'coach');
    }

    public function scopeParents($query) {
        return $query->where('role', 'parent');
    }

    public function scopeActive($query) {
        return $query->where('is_active', true);
    }

    // ===================== ACCESSORS =====================

    public function getFullNameAttribute() {
        return $this->name;
    }

    public function isAdmin() {
        return $this->role === 'admin';
    }

    public function isCoach() {
        return $this->role === 'coach';
    }

    public function isParent() {
        return $this->role === 'parent';
    }
}
