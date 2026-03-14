<?php
namespace Database\Seeders;

use App\Models\Performance;
use App\Models\Swimmer;
use App\Models\Event;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class PerformanceSeeder extends Seeder {
    public function run(): void {
        $swimmers = Swimmer::all();
        $events = Event::all();
        $months = [
            Carbon::now()->format('Y-m'),
            Carbon::now()->subMonth()->format('Y-m'),
            Carbon::now()->subMonths(2)->format('Y-m'),
        ];

        foreach ($swimmers as $swimmer) {
            foreach ($events->random(3) as $event) {
                foreach ($months as $month) {
                    // Temps aléatoire entre 30 et 120 secondes
                    $time = rand(30, 120) + rand(0, 99) / 100;

                    Performance::create([
                        'swimmer_id' => $swimmer->id,
                        'event_id' => $event->id,
                        'session_id' => null,
                        'month' => $month,
                        'time_seconds' => $time,
                        'notes' => 'Bonne performance',
                        'is_personal_record' => false,
                    ]);
                }
            }
        }

        // Créer quelques records personnels
        $performances = Performance::orderBy('created_at', 'desc')->limit(6)->get();
        foreach ($performances as $perf) {
            $perf->update(['is_personal_record' => true]);
        }
    }
}
