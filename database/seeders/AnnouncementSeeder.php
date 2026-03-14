<?php
namespace Database\Seeders;

use App\Models\Announcement;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class AnnouncementSeeder extends Seeder {
    public function run(): void {
        $admin = User::where('role', 'admin')->first();
        $coach1 = User::where('email', 'coach.dupont@swimming-club.local')->first();

        $announcements = [
            [
                'title' => 'Bienvenue au club de natation !',
                'content' => 'Nous sommes heureux de vous accueillir. Consultez régulièrement cette plateforme pour les mises à jour.',
                'type' => 'info',
                'created_by' => $admin->id,
            ],
            [
                'title' => 'Fermeture exceptionnelle',
                'content' => 'Le club sera fermé le 25 décembre. Reprise des cours le 2 janvier.',
                'type' => 'urgent',
                'created_by' => $admin->id,
            ],
            [
                'title' => 'Compétition inter-clubs',
                'content' => 'Nous organisons une compétition inter-clubs le 15 avril. Les inscriptions sont ouvertes !',
                'type' => 'event',
                'created_by' => $coach1->id,
            ],
            [
                'title' => 'Résultats du concours de nage',
                'content' => 'Félicitations à tous les participants ! Les résultats sont disponibles sur le tableau d\'affichage.',
                'type' => 'announcement',
                'created_by' => $coach1->id,
            ],
            [
                'title' => 'Changement d\'horaire pour le groupe Benjamin',
                'content' => 'À partir de la semaine prochaine, le groupe Benjamin se réunira le lundi et jeudi à 17h.',
                'type' => 'info',
                'created_by' => $admin->id,
            ],
            [
                'title' => '🎯 Nouveau coach : Coach Martin',
                'content' => 'Nous accueillons notre nouveau coach Martin qui encadrera le groupe Cadet.',
                'type' => 'announcement',
                'created_by' => $admin->id,
            ],
        ];

        foreach ($announcements as $announcement) {
            Announcement::create(array_merge($announcement, [
                'is_published' => true,
                'published_at' => now()->subDays(rand(1, 30)),
                'expires_at' => now()->addMonths(3),
            ]));
        }
    }
}
