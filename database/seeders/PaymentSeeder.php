<?php
namespace Database\Seeders;

use App\Models\Payment;
use App\Models\Swimmer;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class PaymentSeeder extends Seeder {
    public function run(): void {
        $swimmers = Swimmer::all();
        $statuses = ['Paid', 'Partial', 'Pending', 'Late'];
        $months = [
            Carbon::now()->format('Y-m'),
            Carbon::now()->subMonth()->format('Y-m'),
            Carbon::now()->subMonths(2)->format('Y-m'),
        ];

        foreach ($swimmers as $swimmer) {
            $monthlyFee = $swimmer->group->monthly_fee;

            foreach ($months as $month) {
                $status = $statuses[array_rand($statuses)];
                $amountPaid = match($status) {
                    'Paid' => $monthlyFee,
                    'Partial' => $monthlyFee * 0.5,
                    'Pending' => 0,
                    'Late' => $monthlyFee * 0.75,
                };

                Payment::create([
                    'swimmer_id' => $swimmer->id,
                    'month' => $month,
                    'amount_expected' => $monthlyFee,
                    'amount_paid' => $amountPaid,
                    'status' => $status,
                    'paid_at' => $status === 'Paid' ? now()->subDays(rand(1, 30)) : null,
                ]);
            }
        }
    }
}
