<?php

namespace App\Http\Controllers;

use App\Expense;
use App\ExpenseCategory;
use App\Http\Requests\ExpenseRequest;
use App\Services\AuditLogService;
use Illuminate\Support\Facades\DB;

class ExpenseController extends Controller
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
        $expenses = Expense::with(['category', 'user'])
            ->orderByDesc('expense_date')
            ->paginate(50);

        return view('expenses.index', ['expenses' => $expenses]);
    }

    public function create()
    {
        $categories = ExpenseCategory::where('is_active', true)->orderBy('name')->get();
        return view('expenses.form', ['categories' => $categories]);
    }

    public function store(ExpenseRequest $request)
    {
        $number = 'EXP-' . date('YmdHis');

        $expense = Expense::create(array_merge(
            $request->validated(),
            ['number' => $number, 'user_id' => auth()->id()]
        ));

        $this->auditService->log('expense_create', 'Expense', $expense->id, null, "Created expense: {$expense->number}");

        return redirect()->route('expenses.index')->with('success', 'Expense created successfully.');
    }

    public function edit(Expense $expense)
    {
        $categories = ExpenseCategory::where('is_active', true)->orderBy('name')->get();
        return view('expenses.form', [
            'expense' => $expense,
            'categories' => $categories,
        ]);
    }

    public function update(ExpenseRequest $request, Expense $expense)
    {
        $old = $expense->toArray();
        $expense->update($request->validated());
        $this->auditService->log('expense_update', 'Expense', $expense->id, array_diff_assoc($expense->toArray(), $old));
        return redirect()->route('expenses.index')->with('success', 'Expense updated successfully.');
    }

    public function destroy(Expense $expense)
    {
        $this->auditService->log('expense_delete', 'Expense', $expense->id, null, "Deleted expense: {$expense->number}");
        $expense->delete();
        return redirect()->route('expenses.index')->with('success', 'Expense deleted successfully.');
    }
}
