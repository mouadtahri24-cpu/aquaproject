<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder {
    public function run(): void {
        // Créer les données dans le bon ordre
        $this->call([
            UserSeeder::class,
            GroupSeeder::class,
            SwimmerSeeder::class,
            EventSeeder::class,
            SessionSeeder::class,
            AttendanceSeeder::class,
            PerformanceSeeder::class,
            PaymentSeeder::class,
            AnnouncementSeeder::class,
        ]);

        $this->command->info('✅ Base de données remplie avec succès !');
        $this->command->info('');
        $this->command->info('📊 Résumé :');
        $this->command->info('   - 1 Admin');
        $this->command->info('   - 2 Coachs');
        $this->command->info('   - 2 Parents');
        $this->command->info('   - 3 Groupes (Benjamin, Cadet, Junior)');
        $this->command->info('   - 6 Nageurs (mineurs)');
        $this->command->info('   - 7 Épreuves');
        $this->command->info('   - 12 Séances');
        $this->command->info('   - Présences, Performances, Paiements');
        $this->command->info('   - Annonces');
    }
}
