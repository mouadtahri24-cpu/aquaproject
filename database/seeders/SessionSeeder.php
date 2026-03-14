<?php
namespace Database\Seeders;

use App\Models\Session;
use App\Models\Group;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class SessionSeeder extends Seeder {
    public function run(): void {
        $coach1 = User::where('email', 'coach.dupont@swimming-club.local')->first();
        $coach2 = User::where('email', 'coach.martin@swimming-club.local')->first();

        $groupBenjamin = Group::where('age_category', 'benjamin')->first();
        $groupCadet = Group::where('age_category', 'cadet')->first();
        $groupJunior = Group::where('age_category', 'junior')->first();

        // Semaine 1
        Session::create([
            'group_id' => $groupBenjamin->id,
            'coach_id' => $coach1->id,
            'session_date' => Carbon::now()->startOfWeek()->addDay(0),
            'start_time' => '17:00',
            'end_time' => '18:00',
            'type' => 'Entrainement',
            'objective' => 'Travail du crawl - mouvements de jambes',
        ]);

        Session::create([
            'group_id' => $groupBenjamin->id,
            'coach_id' => $coach1->id,
            'session_date' => Carbon::now()->startOfWeek()->addDay(2),
            'start_time' => '17:00',
            'end_time' => '18:00',
            'type' => 'Entrainement',
            'objective' => 'Respiration et coordination',
        ]);

        Session::create([
            'group_id' => $groupCadet->id,
            'coach_id' => $coach2->id,
            'session_date' => Carbon::now()->startOfWeek()->addDay(1),
            'start_time' => '18:00',
            'end_time' => '19:00',
            'type' => 'Entrainement',
            'objective' => 'Crawl - amélioration vitesse',
        ]);

        Session::create([
            'group_id' => $groupCadet->id,
            'coach_id' => $coach2->id,
            'session_date' => Carbon::now()->startOfWeek()->addDay(3),
            'start_time' => '18:00',
            'end_time' => '19:00',
            'type' => 'Entrainement',
            'objective' => 'Dos crawlé - mouvements de bras',
        ]);

        Session::create([
            'group_id' => $groupJunior->id,
            'coach_id' => $coach1->id,
            'session_date' => Carbon::now()->startOfWeek()->addDay(0),
            'start_time' => '19:00',
            'end_time' => '20:30',
            'type' => 'Entrainement',
            'objective' => 'Entraînement complet - crawl et dos',
        ]);

        Session::create([
            'group_id' => $groupJunior->id,
            'coach_id' => $coach1->id,
            'session_date' => Carbon::now()->startOfWeek()->addDay(2),
            'start_time' => '19:00',
            'end_time' => '20:30',
            'type' => 'Entrainement',
            'objective' => 'Brasse et Papillon',
        ]);

        // Semaine 2 (futures sessions)
        Session::create([
            'group_id' => $groupBenjamin->id,
            'coach_id' => $coach1->id,
            'session_date' => Carbon::now()->addWeek()->startOfWeek()->addDay(0),
            'start_time' => '17:00',
            'end_time' => '18:00',
            'type' => 'Entrainement',
            'objective' => 'Révision mouvements crawl',
        ]);

        Session::create([
            'group_id' => $groupCadet->id,
            'coach_id' => $coach2->id,
            'session_date' => Carbon::now()->addWeek()->startOfWeek()->addDay(1),
            'start_time' => '18:00',
            'end_time' => '19:00',
            'type' => 'Entrainement',
            'objective' => 'Préparation compétition',
        ]);

        Session::create([
            'group_id' => $groupJunior->id,
            'coach_id' => $coach1->id,
            'session_date' => Carbon::now()->addWeek()->startOfWeek()->addDay(3),
            'start_time' => '19:00',
            'end_time' => '20:30',
            'type' => 'Competition',
            'objective' => 'Compétition interne - 100m crawl',
        ]);

        // Semaine 3
        Session::create([
            'group_id' => $groupBenjamin->id,
            'coach_id' => $coach1->id,
            'session_date' => Carbon::now()->addWeeks(2)->startOfWeek()->addDay(0),
            'start_time' => '17:00',
            'end_time' => '18:00',
            'type' => 'Entrainement',
            'objective' => 'Endurance - nage continue 500m',
        ]);

        Session::create([
            'group_id' => $groupCadet->id,
            'coach_id' => $coach2->id,
            'session_date' => Carbon::now()->addWeeks(2)->startOfWeek()->addDay(1),
            'start_time' => '18:00',
            'end_time' => '19:00',
            'type' => 'Entrainement',
            'objective' => 'Renforcement musculaire',
        ]);
    }
}
