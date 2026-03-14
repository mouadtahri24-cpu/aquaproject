<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('conversations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('participant_a_id');
            $table->unsignedBigInteger('participant_b_id');
            $table->comment('RESTRICTION: Parent-Parent conversations interdites');
            $table->timestamps();
            $table->unique(['participant_a_id', 'participant_b_id']);
        });

        Schema::table('conversations', function (Blueprint $table) {
            $table->foreign('participant_a_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');
            
            $table->foreign('participant_b_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');
        });
    }

    public function down(): void {
        Schema::dropIfExists('conversations');
    }
};
