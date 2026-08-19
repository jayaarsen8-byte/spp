<?php

namespace App\Http\Controllers;

use App\Unit;
use App\Http\Requests\UnitRequest;
use App\Services\AuditLogService;

class UnitController extends Controller
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
        $units = Unit::orderBy('name')->paginate(50);
        return view('units.index', ['units' => $units]);
    }

    public function create()
    {
        return view('units.form');
    }

    public function store(UnitRequest $request)
    {
        $unit = Unit::create($request->validated());
        $this->auditService->log('unit_create', 'Unit', $unit->id, null, "Created unit: {$unit->name}");
        return redirect()->route('units.index')->with('success', 'Unit created successfully.');
    }

    public function edit(Unit $unit)
    {
        return view('units.form', ['unit' => $unit]);
    }

    public function update(UnitRequest $request, Unit $unit)
    {
        $old = $unit->toArray();
        $unit->update($request->validated());
        $this->auditService->log('unit_update', 'Unit', $unit->id, array_diff_assoc($unit->toArray(), $old));
        return redirect()->route('units.index')->with('success', 'Unit updated successfully.');
    }

    public function destroy(Unit $unit)
    {
        $this->auditService->log('unit_delete', 'Unit', $unit->id, null, "Deleted unit: {$unit->name}");
        $unit->delete();
        return redirect()->route('units.index')->with('success', 'Unit deleted successfully.');
    }
}
