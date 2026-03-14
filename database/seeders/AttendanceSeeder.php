<?php
namespace Database\Seeders;

use App\Models\Attendance;
use App\Models\Session;
use App\Models\Swimmer;
use Illuminate\Database\Seeder;

class AttendanceSeeder extends Seeder {
    public function run(): void {
        // Récupérer les séances passées seulement
        $sessions = Session::where('session_date', '<', now()->toDateString())->get();
        $statuses = ['Present', 'Absent', 'Justifie', 'Retard'];

        foreach ($sessions as $session) {
            $swimmers = $session->group->swimmers;

            foreach ($swimmers as $swimmer) {
                // Créer une présence aléatoire
                $status = $statuses[array_rand($statuses)];
                $reason = $status === 'Absent' ? 'Malade' : ($status === 'Justifie' ? 'Raison personnelle' : null);

                Attendance::create([
                    'session_id' => $session->id,
                    'swimmer_id' => $swimmer->id,
                    'status' => $status,
                    'reason' => $reason,
                ]);
            }
        }
    }
}
