<?php

namespace App\Http\Controllers;

use App\Sale;
use App\StockMovement;
use App\Services\AuditLogService;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{
    protected $auditService;

    public function __construct(AuditLogService $auditService)
    {
        $this->auditService = $auditService;
        $this->middleware('auth');
        $this->middleware('role:owner,admin');
    }

    public function index()
    {
        $sales = Sale::with(['customer', 'user', 'items'])
            ->orderByDesc('sold_at')
            ->paginate(50);

        return view('sales.index', ['sales' => $sales]);
    }

    public function show(Sale $sale)
    {
        $sale->load(['customer', 'user', 'items.product', 'payments', 'receivable']);
        return view('sales.show', ['sale' => $sale]);
    }

    public function cancel(Sale $sale)
    {
        if ($sale->status === 'cancelled') {
            return back()->with('error', 'Sale is already cancelled.');
        }

        return DB::transaction(function () use ($sale) {
            $sale->update(['status' => 'cancelled']);

            foreach ($sale->items as $item) {
                StockMovement::create([
                    'product_id' => $item->product_id,
                    'user_id' => auth()->id(),
                    'type' => 'return',
                    'quantity' => $item->quantity,
                    'reference' => $sale->invoice_number,
                    'note' => 'Sale cancellation',
                ]);

                $item->product->stock()->update([
                    'quantity' => DB::raw('quantity + ' . $item->quantity)
                ]);
            }

            $this->auditService->log('sale_cancel', 'Sale', $sale->id, null, "Cancelled sale: {$sale->invoice_number}");

            return redirect()->route('sales.show', $sale)->with('success', 'Sale cancelled successfully.');
        });
    }

    public function print(Sale $sale)
    {
        $sale->load(['customer', 'user', 'items.product', 'payments']);
        return view('sales.print', ['sale' => $sale]);
    }
}
