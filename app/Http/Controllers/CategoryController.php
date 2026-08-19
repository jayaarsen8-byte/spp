<?php

namespace App\Http\Controllers;

use App\Category;
use App\Http\Requests\CategoryRequest;
use App\Services\AuditLogService;

class CategoryController extends Controller
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
        $categories = Category::orderBy('name')->paginate(50);
        return view('categories.index', ['categories' => $categories]);
    }

    public function create()
    {
        return view('categories.form');
    }

    public function store(CategoryRequest $request)
    {
        $category = Category::create($request->validated());
        $this->auditService->log('category_create', 'Category', $category->id, null, "Created category: {$category->name}");
        return redirect()->route('categories.index')->with('success', 'Category created successfully.');
    }

    public function edit(Category $category)
    {
        return view('categories.form', ['category' => $category]);
    }

    public function update(CategoryRequest $request, Category $category)
    {
        $old = $category->toArray();
        $category->update($request->validated());
        $this->auditService->log('category_update', 'Category', $category->id, array_diff_assoc($category->toArray(), $old));
        return redirect()->route('categories.index')->with('success', 'Category updated successfully.');
    }

    public function destroy(Category $category)
    {
        $this->auditService->log('category_delete', 'Category', $category->id, null, "Deleted category: {$category->name}");
        $category->delete();
        return redirect()->route('categories.index')->with('success', 'Category deleted successfully.');
    }
}
