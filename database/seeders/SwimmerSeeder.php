<?php
namespace Database\Seeders;

use App\Models\Swimmer;
use App\Models\User;
use App\Models\Group;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class SwimmerSeeder extends Seeder {
    public function run(): void {
        $parent1 = User::where('email', 'parent.durand@swimming-club.local')->first();
        $parent2 = User::where('email', 'parent.blanc@swimming-club.local')->first();

        $groupBenjamin = Group::where('age_category', 'benjamin')->first();
        $groupCadet = Group::where('age_category', 'cadet')->first();
        $groupJunior = Group::where('age_category', 'junior')->first();

        // Benjamin - Enfant 1 du Parent 1
        Swimmer::create([
            'first_name' => 'Léa',
            'last_name' => 'Durand',
            'birth_date' => Carbon::now()->subYears(8),
            'parent_id' => $parent1->id,
            'group_id' => $groupBenjamin->id,
            'level' => 'Débutant',
            'status' => 'active',
            'is_minor' => true,
        ]);

        // Benjamin - Enfant 2 du Parent 1
        Swimmer::create([
            'first_name' => 'Tom',
            'last_name' => 'Durand',
            'birth_date' => Carbon::now()->subYears(7),
            'parent_id' => $parent1->id,
            'group_id' => $groupBenjamin->id,
            'level' => 'Intermédiaire',
            'status' => 'active',
            'is_minor' => true,
        ]);

        // Cadet - Enfant du Parent 2
        Swimmer::create([
            'first_name' => 'Marie',
            'last_name' => 'Blanc',
            'birth_date' => Carbon::now()->subYears(11),
            'parent_id' => $parent2->id,
            'group_id' => $groupCadet->id,
            'level' => 'Intermédiaire',
            'status' => 'active',
            'is_minor' => true,
        ]);

        // Cadet - Enfant 2 du Parent 2
        Swimmer::create([
            'first_name' => 'Lucas',
            'last_name' => 'Blanc',
            'birth_date' => Carbon::now()->subYears(10),
            'parent_id' => $parent2->id,
            'group_id' => $groupCadet->id,
            'level' => 'Débutant',
            'status' => 'active',
            'is_minor' => true,
        ]);

        // Junior - Enfant 3 du Parent 1
        Swimmer::create([
            'first_name' => 'Sophie',
            'last_name' => 'Durand',
            'birth_date' => Carbon::now()->subYears(15),
            'parent_id' => $parent1->id,
            'group_id' => $groupJunior->id,
            'level' => 'Avancé',
            'status' => 'active',
            'is_minor' => true,
        ]);

        // Junior - Enfant 3 du Parent 2
        Swimmer::create([
            'first_name' => 'Nicolas',
            'last_name' => 'Blanc',
            'birth_date' => Carbon::now()->subYears(14),
            'parent_id' => $parent2->id,
            'group_id' => $groupJunior->id,
            'level' => 'Intermédiaire',
            'status' => 'active',
            'is_minor' => true,
        ]);
    }
}
