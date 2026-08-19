<?php

namespace App\Http\Controllers;

use App\Receivable;
use Illuminate\Http\Request;

class ReceivableController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:owner,admin');
    }

    public function index()
    {
        $receivables = Receivable::with(['customer', 'sale'])
            ->orderBy('due_date')
            ->paginate(50);

        return view('receivables.index', ['receivables' => $receivables]);
    }

    public function show(Receivable $receivable)
    {
        $receivable->load(['customer', 'sale.items.product', 'payments.user']);
        return view('receivables.show', ['receivable' => $receivable]);
    }
}
