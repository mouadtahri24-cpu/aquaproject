<?php
namespace Database\Seeders;

use App\Models\Group;
use App\Models\User;
use Illuminate\Database\Seeder;

class GroupSeeder extends Seeder {
    public function run(): void {
        $coach1 = User::where('email', 'coach.dupont@swimming-club.local')->first();
        $coach2 = User::where('email', 'coach.martin@swimming-club.local')->first();

        // Groupe Benjamin (6-9 ans)
        Group::create([
            'name' => 'Groupe Benjamin',
            'level' => 'Débutant',
            'schedule_label' => 'Lundi & Mercredi 17h-18h',
            'coach_id' => $coach1->id,
            'age_category' => 'benjamin',
            'min_age' => 6,
            'max_age' => 9,
            'monthly_fee' => 45.00,
            'is_active' => true,
        ]);

        // Groupe Cadet (10-12 ans)
        Group::create([
            'name' => 'Groupe Cadet',
            'level' => 'Intermédiaire',
            'schedule_label' => 'Mardi & Jeudi 18h-19h',
            'coach_id' => $coach2->id,
            'age_category' => 'cadet',
            'min_age' => 10,
            'max_age' => 12,
            'monthly_fee' => 50.00,
            'is_active' => true,
        ]);

        // Groupe Junior (13-17 ans)
        Group::create([
            'name' => 'Groupe Junior',
            'level' => 'Avancé',
            'schedule_label' => 'Lundi, Mercredi & Samedi 19h-20h30',
            'coach_id' => $coach1->id,
            'age_category' => 'junior',
            'min_age' => 13,
            'max_age' => 17,
            'monthly_fee' => 60.00,
            'is_active' => true,
        ]);
    }
}
