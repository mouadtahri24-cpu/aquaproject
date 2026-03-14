<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('coaches', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary();
            $table->timestamps();
        });

        Schema::table('coaches', function (Blueprint $table) {
            $table->foreign('id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');
        });
    }

    public function down(): void {
        Schema::dropIfExists('coaches');
    }
};
