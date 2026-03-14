<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('sessions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('group_id');
            $table->unsignedBigInteger('coach_id');
            $table->date('session_date');
            $table->time('start_time');
            $table->time('end_time');
            $table->enum('type', ['Entrainement', 'Competition'])->default('Entrainement');
            $table->text('objective')->nullable();
            $table->timestamps();
        });

        Schema::table('sessions', function (Blueprint $table) {
            $table->foreign('group_id')
                  ->references('id')
                  ->on('groups')
                  ->onDelete('cascade');
            
            $table->foreign('coach_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');
        });
    }

    public function down(): void {
        Schema::dropIfExists('sessions');
    }
};
