<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('announcements', function (Blueprint $table) {
            $table->id();
            
            // Créateur = ADMIN UNIQUEMENT
            $table->unsignedBigInteger('created_by')->comment('Admin qui crée l\'annonce');
            
            // Contenu
            $table->string('title');
            $table->text('content');
            $table->string('type')->nullable()->comment('Type: info, urgent, event, etc');
            
            // Visibilité
            $table->boolean('is_published')->default(true)->comment('Affichée ou non');
            $table->timestamp('published_at')->nullable()->comment('Date de publication');
            $table->timestamp('expires_at')->nullable()->comment('Date d\'expiration');
            
            // Audit
            $table->timestamp('updated_by_at')->nullable();
            $table->timestamps();
        });

        Schema::table('announcements', function (Blueprint $table) {
            // created_by DOIT être un ADMIN
            $table->foreign('created_by')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');
        });
    }

    public function down(): void {
        Schema::dropIfExists('announcements');
    }
};
