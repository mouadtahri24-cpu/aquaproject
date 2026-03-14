<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Ex: 50m Crawl, 100m Dos
            $table->integer('distance')->nullable(); // Distance en mètres
            $table->enum('stroke', ['Crawl', 'Dos', 'Brasse', 'Papillon'])->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('events');
    }
};
