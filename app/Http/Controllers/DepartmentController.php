<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Department;
use App\Http\Requests\StoreDepartmentRequest;
use App\Http\Requests\UpdateDepartmentRequest;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $departments = Department::orderBy('name', 'asc')->get();

        return Inertia::render('Departments/Index', [
            'departments' => $departments
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Departments/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDepartmentRequest $request)
    {
        $department = new Department();
        $department->name = $request->name;
        $department->description = $request->description;

        $department->save();

        return redirect()
            ->route('departments.show', $department->id)
            ->with('departmentCreated', 'El departamento ha sido creado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Department $department)
    {
        return Inertia::render('Departments/Show', [
            'department' => $department
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Department $department)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDepartmentRequest $request, Department $department)
    {
        $department->name = $request->name;
        $department->description = $request->description;
        $department->save();

        return redirect()
            ->back()
            ->with('departmentUpdated', 'El departamento ha sido actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Department $department)
    {
        $tickets = $department->tickets;
        $agents = $department->agents;

        foreach ($tickets as $ticket) {
            $ticket->department_id = "";
        }

        foreach ($agents as $agent) {
            $generalDepartment = Department::where('name', 'General')->first();
            $agent->department_id = $generalDepartment->id;
            $agent->save();
        }

        $department->delete();

        return redirect()
            ->route('departments.index')
            ->with('departmentDeleted', 'El departamento ha sido eliminado exitosamente.');
    }
}
