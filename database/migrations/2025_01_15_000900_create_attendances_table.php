<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('session_id');
            $table->unsignedBigInteger('swimmer_id');
            $table->enum('status', ['Present', 'Absent', 'Justifie', 'Retard'])->default('Absent');
            $table->string('reason')->nullable();
            $table->timestamps();
            $table->unique(['session_id', 'swimmer_id']);
        });

        Schema::table('attendances', function (Blueprint $table) {
            $table->foreign('session_id')
                  ->references('id')
                  ->on('sessions')
                  ->onDelete('cascade');
            
            $table->foreign('swimmer_id')
                  ->references('id')
                  ->on('swimmers')
                  ->onDelete('cascade');
        });
    }

    public function down(): void {
        Schema::dropIfExists('attendances');
    }
};
