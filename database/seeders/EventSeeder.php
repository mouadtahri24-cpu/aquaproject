<?php
namespace Database\Seeders;

use App\Models\Event;
use Illuminate\Database\Seeder;

class EventSeeder extends Seeder {
    public function run(): void {
        $events = [
            ['name' => '50m Crawl', 'distance' => 50, 'stroke' => 'Crawl'],
            ['name' => '100m Crawl', 'distance' => 100, 'stroke' => 'Crawl'],
            ['name' => '100m Dos', 'distance' => 100, 'stroke' => 'Dos'],
            ['name' => '100m Brasse', 'distance' => 100, 'stroke' => 'Brasse'],
            ['name' => '100m Papillon', 'distance' => 100, 'stroke' => 'Papillon'],
            ['name' => '200m 4 Nages', 'distance' => 200, 'stroke' => 'Crawl'],
            ['name' => '25m Crawl (Jeunes)', 'distance' => 25, 'stroke' => 'Crawl'],
        ];

        foreach ($events as $event) {
            Event::create($event);
        }
    }
}
