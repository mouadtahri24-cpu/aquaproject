<?php
namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder {
    public function run(): void {
        // Admin
        User::create([
            'name' => 'Admin Club',
            'email' => 'admin@swimming-club.local',
            'password' => bcrypt('password123'),
            'telephone' => '+33612345678',
            'role' => 'admin',
            'is_active' => true,
        ]);

        // Coachs
        User::create([
            'name' => 'Coach Dupont',
            'email' => 'coach.dupont@swimming-club.local',
            'password' => bcrypt('password123'),
            'telephone' => '+33612345679',
            'role' => 'coach',
            'is_active' => true,
        ]);

        User::create([
            'name' => 'Coach Martin',
            'email' => 'coach.martin@swimming-club.local',
            'password' => bcrypt('password123'),
            'telephone' => '+33612345680',
            'role' => 'coach',
            'is_active' => true,
        ]);

        // Parents
        User::create([
            'name' => 'Parent Durand',
            'email' => 'parent.durand@swimming-club.local',
            'password' => bcrypt('password123'),
            'telephone' => '+33612345681',
            'role' => 'parent',
            'is_active' => true,
        ]);

        User::create([
            'name' => 'Parent Blanc',
            'email' => 'parent.blanc@swimming-club.local',
            'password' => bcrypt('password123'),
            'telephone' => '+33612345682',
            'role' => 'parent',
            'is_active' => true,
        ]);

        // Parent inactif (pour tester)
        User::create([
            'name' => 'Parent Inactif',
            'email' => 'parent.inactif@swimming-club.local',
            'password' => bcrypt('password123'),
            'telephone' => '+33612345683',
            'role' => 'parent',
            'is_active' => false,
        ]);
    }
}
