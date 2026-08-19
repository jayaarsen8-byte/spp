<?php

namespace App\Http\Controllers;

use App\Receivable;
use App\ReceivablePayment;
use App\Http\Requests\ReceivablePaymentRequest;
use App\Services\AuditLogService;
use Illuminate\Support\Facades\DB;

class ReceivablePaymentController extends Controller
{
    protected $auditService;

    public function __construct(AuditLogService $auditService)
    {
        $this->auditService = $auditService;
        $this->middleware('auth');
        $this->middleware('role:owner,admin');
    }

    public function form(Receivable $receivable)
    {
        return view('receivables.payment-form', ['receivable' => $receivable]);
    }

    public function store(ReceivablePaymentRequest $request, Receivable $receivable)
    {
        if ($receivable->status === 'paid') {
            return back()->with('error', 'Receivable is already paid.');
        }

        return DB::transaction(function () use ($request, $receivable) {
            $validated = $request->validated();
            $amount = $validated['amount'];

            if ($amount > $receivable->remaining_amount) {
                return back()->with('error', 'Payment amount exceeds remaining balance.');
            }

            ReceivablePayment::create([
                'receivable_id' => $receivable->id,
                'user_id' => auth()->id(),
                'amount' => $amount,
                'method' => $validated['method'],
                'note' => $validated['note'] ?? null,
                'paid_at' => now(),
            ]);

            $receivable->update([
                'paid_amount' => $receivable->paid_amount + $amount,
                'remaining_amount' => $receivable->remaining_amount - $amount,
                'status' => ($receivable->remaining_amount - $amount) <= 0 ? 'paid' : 'partial',
            ]);

            $this->auditService->log('receivable_payment', 'Receivable', $receivable->id, null, "Recorded payment: Rp" . number_format($amount, 2));

            return redirect()->route('receivables.show', $receivable)->with('success', 'Payment recorded successfully.');
        });
    }
}
