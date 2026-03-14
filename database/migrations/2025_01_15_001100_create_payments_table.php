<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('swimmer_id');
            $table->string('month'); // Format: YYYY-MM
            $table->decimal('amount_expected', 10, 2);
            $table->decimal('amount_paid', 10, 2)->default(0);
            $table->enum('status', ['Paid', 'Partial', 'Pending', 'Late'])->default('Pending');
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
            $table->unique(['swimmer_id', 'month']); // Un paiement par nageur par mois
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->foreign('swimmer_id')
                  ->references('id')
                  ->on('swimmers')
                  ->onDelete('cascade');
        });
    }

    public function down(): void {
        Schema::dropIfExists('payments');
    }
};
