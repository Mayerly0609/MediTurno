<?php

namespace App\Http\Controllers;

use App\Models\Projects\Project;
use App\Models\Projects\ProjectActivity;
use Illuminate\Http\Request;

class ProjectActivityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Project $project)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:1000'],
            'responsible_id' => [
                'required',
                'integer',
                'exists:users,id',
            ],
            'phase_id' => [
                'required',
                'integer',
                \Illuminate\Validation\Rule::exists('project_phases', 'id')
                    ->where('project_id', $project->id),
            ],

            'priority' => [
                'required',
                'in:high,medium,low',
            ],

            'due_date' => [
                'required',
                'date',
            ],

            'estimated_hours' => [
                'nullable',
                'numeric',
                'min:0.5',
            ],
        ]);

        ProjectActivity::create([
            'project_id' => $project->id,
            'phase_id' => $validated['phase_id'],
            'title' => $validated['title'],
            'description' => $validated['description'],
            'responsible_id' => $validated['responsible_id'],
            'priority' => $validated['priority'],
            'due_date' => $validated['due_date'],
            'estimated_hours' => $validated['estimated_hours'] ?? null,
            'status' => 'pending',
            'progress' => 0,
            'created_by' => auth()->id(),
        ]);

        return redirect()
            ->route('projects.show', $project)
            ->with('success', 'La actividad se creó correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
