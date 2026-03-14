<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('groups', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Ex: "Groupe Benjamin", "Groupe Cadet", "Groupe Junior"
            $table->string('level'); // Ex: Débutant, Intermédiaire, Avancé
            $table->string('schedule_label')->nullable(); // Ex: Lundi 18h-19h
            $table->unsignedBigInteger('coach_id');
            
            // CATÉGORIES D'ÂGE (Mineurs uniquement)
            $table->enum('age_category', [
                'benjamin',      // 6-9 ans
                'cadet',         // 10-12 ans
                'junior'         // 13-17 ans
            ])->comment('Catégorie d\'âge - Pour mineurs seulement');
            
            $table->integer('min_age')->default(6)->comment('Âge minimum pour cette catégorie');
            $table->integer('max_age')->default(17)->comment('Âge maximum pour cette catégorie');
            
            $table->decimal('monthly_fee', 10, 2)->default(0); // Frais mensuels
            $table->boolean('is_active')->default(true); // Groupe actif ou non
            $table->timestamps();
        });

        Schema::table('groups', function (Blueprint $table) {
            $table->foreign('coach_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');
        });
    }

    public function down(): void {
        Schema::dropIfExists('groups');
    }
};
