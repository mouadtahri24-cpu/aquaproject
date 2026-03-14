<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePaymentRequest;
use App\Http\Requests\UpdatePaymentRequest;
use App\Http\Resources\PaymentResource;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller {
    public function index(Request $request) {
        $query = Payment::with('swimmer');

        if ($request->has('swimmer_id')) {
            $query->where('swimmer_id', $request->swimmer_id);
        }

        if ($request->has('month')) {
            $query->where('month', $request->month);
        }

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $payments = $query->orderBy('month', 'desc')->get();

        return response()->json([
            'data' => PaymentResource::collection($payments),
            'count' => $payments->count(),
        ]);
    }

    public function store(StorePaymentRequest $request) {
        $payment = Payment::create([
            'swimmer_id' => $request->swimmer_id,
            'month' => $request->month,
            'amount_expected' => $request->amount_expected,
            'amount_paid' => $request->amount_paid,
            'status' => $request->status,
            'paid_at' => $request->status === 'Paid' ? now() : null,
        ]);

        return response()->json([
            'message' => 'Paiement enregistré',
            'data' => new PaymentResource($payment->load('swimmer')),
        ], 201);
    }

    public function show(Payment $payment) {
        return response()->json(new PaymentResource($payment->load('swimmer')));
    }

    public function update(UpdatePaymentRequest $request, Payment $payment) {
        $data = $request->validated();

        if ($request->has('status') && $request->status === 'Paid' && !$payment->paid_at) {
            $data['paid_at'] = now();
        }

        $payment->update($data);

        return response()->json([
            'message' => 'Paiement mis à jour',
            'data' => new PaymentResource($payment->load('swimmer')),
        ]);
    }

    public function destroy(Payment $payment) {
        $payment->delete();

        return response()->json([
            'message' => 'Paiement supprimé',
        ]);
    }

    public function getBySwimmer($swimmerId) {
        $payments = Payment::where('swimmer_id', $swimmerId)->with('swimmer')->orderBy('month', 'desc')->get();
        return response()->json([
            'data' => PaymentResource::collection($payments),
            'count' => $payments->count(),
        ]);
    }

    public function getLate() {
        $payments = Payment::where('status', 'Late')->with('swimmer')->orderBy('month', 'asc')->get();
        return response()->json([
            'data' => PaymentResource::collection($payments),
            'count' => $payments->count(),
        ]);
    }

    public function recordPayment(Request $request, Payment $payment) {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0.01',
        ]);

        $payment->recordPayment($validated['amount']);

        return response()->json([
            'message' => 'Paiement enregistré',
            'data' => new PaymentResource($payment->load('swimmer')),
        ]);
    }
}
