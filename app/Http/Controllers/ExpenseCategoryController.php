<?php

namespace App\Http\Controllers;

use App\ExpenseCategory;
use App\Http\Requests\CategoryRequest;
use App\Services\AuditLogService;

class ExpenseCategoryController extends Controller
{
    protected $auditService;

    public function __construct(AuditLogService $auditService)
    {
        $this->auditService = $auditService;
        $this->middleware('auth');
        $this->middleware('role:owner');
    }

    public function index()
    {
        $categories = ExpenseCategory::orderBy('name')->paginate(50);
        return view('expense-categories.index', ['categories' => $categories]);
    }

    public function create()
    {
        return view('expense-categories.form');
    }

    public function store(CategoryRequest $request)
    {
        $category = ExpenseCategory::create($request->validated());
        $this->auditService->log('expense_category_create', 'ExpenseCategory', $category->id);
        return redirect()->route('expense-categories.index')->with('success', 'Category created successfully.');
    }

    public function edit(ExpenseCategory $expenseCategory)
    {
        return view('expense-categories.form', ['category' => $expenseCategory]);
    }

    public function update(CategoryRequest $request, ExpenseCategory $expenseCategory)
    {
        $expenseCategory->update($request->validated());
        $this->auditService->log('expense_category_update', 'ExpenseCategory', $expenseCategory->id);
        return redirect()->route('expense-categories.index')->with('success', 'Category updated successfully.');
    }

    public function destroy(ExpenseCategory $expenseCategory)
    {
        $this->auditService->log('expense_category_delete', 'ExpenseCategory', $expenseCategory->id);
        $expenseCategory->delete();
        return redirect()->route('expense-categories.index')->with('success', 'Category deleted successfully.');
    }
}
