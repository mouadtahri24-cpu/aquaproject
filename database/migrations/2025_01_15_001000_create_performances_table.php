<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('performances', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('swimmer_id');
            $table->unsignedBigInteger('event_id');
            $table->unsignedBigInteger('session_id')->nullable();
            $table->string('month'); // Format: YYYY-MM
            $table->decimal('time_seconds', 10, 2); // Temps en secondes
            $table->text('notes')->nullable();
            $table->boolean('is_personal_record')->default(false);
            $table->timestamps();
        });

        Schema::table('performances', function (Blueprint $table) {
            $table->foreign('swimmer_id')
                  ->references('id')
                  ->on('swimmers')
                  ->onDelete('cascade');
            
            $table->foreign('event_id')
                  ->references('id')
                  ->on('events')
                  ->onDelete('cascade');
            
            $table->foreign('session_id')
                  ->references('id')
                  ->on('sessions')
                  ->onDelete('set null');
        });
    }

    public function down(): void {
        Schema::dropIfExists('performances');
    }
};
