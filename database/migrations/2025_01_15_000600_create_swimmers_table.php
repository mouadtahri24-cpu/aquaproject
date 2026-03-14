<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('swimmers', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->date('birth_date'); // Pour calculer l'âge
            $table->unsignedBigInteger('parent_id'); // Père/Mère obligatoire
            $table->unsignedBigInteger('group_id'); // Groupe obligatoire (mineur)
            
            // Infos du nageur
            $table->enum('status', ['active', 'inactive', 'suspended'])->default('active');
            $table->string('level')->nullable(); // Niveau personnel : Débutant, Intermédiaire, Avancé
            
            // Validation : Vérifier que le nageur est mineur (< 18 ans)
            $table->boolean('is_minor')->default(true)->comment('Doit être TRUE pour pouvoir être dans un groupe');
            
            $table->timestamps();
        });

        Schema::table('swimmers', function (Blueprint $table) {
            // FK vers parent
            $table->foreign('parent_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');
            
            // FK vers groupe
            $table->foreign('group_id')
                  ->references('id')
                  ->on('groups')
                  ->onDelete('cascade');
        });
    }

    public function down(): void {
        Schema::dropIfExists('swimmers');
    }
};
