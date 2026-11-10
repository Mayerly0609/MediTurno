<?php

namespace App\Http\Controllers;

use App\DataTables\ProjectActivitiesDataTable;
use App\DataTables\ProjectsDataTable;
use App\Http\Controllers\Controller;
use App\Models\Projects\Project;
use App\Models\Projects\ProjectActivity;
use App\Models\Projects\ProjectMember;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
     public function index(ProjectsDataTable $dataTable)
    {
        $totalProjects = Project::count();

        $activeProjects = Project::where(
            'status',
            'active'
        )->count();

        $planningProjects = Project::where(
            'status',
            'planning'
        )->count();

        $completedProjects = Project::where(
            'status',
            'completed'
        )->count();

        
        $users = User::query()
            ->orderBy('name')
            ->get();

        return $dataTable->render(
            'projects.index',
            compact(
                'totalProjects',
                'activeProjects',
                'planningProjects',
                'completedProjects',
                'users',
            )
        );
    }


    /**
     * Show the form for creating a new resource.
     */
    

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $validated = $request->validate([
            'name' => ['required','string','max:255',],
            'description' => ['nullable','string',],
            'status' => ['required','in:planning,active,paused,completed,cancelled',],
            'leader_id' => ['required','integer','exists:users,id',],
            'start_date' => ['nullable','date',],
            'end_date' => ['nullable','date','after_or_equal:start_date',],
        ]);


        DB::transaction(function () use ($validated,&$project) {

            $project = Project::create([

                'name' => $validated['name'],

                'description' =>
                    $validated['description'] ?? null,

                'status' =>
                    $validated['status'],

                'leader_id' =>
                    $validated['leader_id'],

                'created_by' =>
                    auth()->id(),

                'start_date' =>
                    $validated['start_date'] ?? null,

                'end_date' =>
                    $validated['end_date'] ?? null,

            ]);


            $phases = [

                [
                    'order' => 1,
                    'name' => 'Inicio',
                    'status' => 'pending',
                ],

                [
                    'order' => 2,
                    'name' => 'Planificación',
                    'status' => 'pending',
                ],

                [
                    'order' => 3,
                    'name' => 'Ejecución',
                    'status' => 'pending',
                ],

                [
                    'order' => 4,
                    'name' => 'Seguimiento',
                    'status' => 'pending',
                ],

                [
                    'order' => 5,
                    'name' => 'Entrega',
                    'status' => 'pending',
                ],

                [
                    'order' => 6,
                    'name' => 'Cierre',
                    'status' => 'pending',
                ],

            ];
            $project->phases()->createMany($phases);

        });


        return redirect()->route('projects.show', $project)->with('success','Proyecto creado correctamente.');
    }

        /**
         * Display the specified resource.
         */
    public function show(Project $project)
    {
        $project->load([
            'leader',
            'creator',
            'phases',
            'activities.responsible',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Usuarios
        |--------------------------------------------------------------------------
        */

        $projectMembers = User::query()
            ->orderBy('name')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Actividades
        |--------------------------------------------------------------------------
        */

        $activities = $project->activities()
            ->with([
                'responsible',
                'project_phase',
            ])
            ->orderBy('due_date')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Estadísticas
        |--------------------------------------------------------------------------
        */

        $totalActivities = $activities->count();

        $completedActivities = $activities
            ->where('status', 'completed')
            ->count();

        $inProgressActivities = $activities
            ->where('status', 'in_progress')
            ->count();


        /*
        |--------------------------------------------------------------------------
        | Progreso general
        |--------------------------------------------------------------------------
        |    Cada actividad tiene el mismo peso.
        | Las fases sin actividades no afectan el porcentaje.
        |
        */

        $progress  = $totalActivities > 0
            ? round($activities->avg('progress') ?? 0)
            : 0;

        // Se conserva el nombre por si lo necesitas en otras partes de la vista.

        /*
        |--------------------------------------------------------------------------
        | Actividades vencidas
        |--------------------------------------------------------------------------
        */

        $overdueActivities = $activities
            ->filter(function ($activity) {
                return $activity->due_date
                    && $activity->due_date->isPast()
                    && $activity->status !== 'completed';

            });

        /*
        |--------------------------------------------------------------------------
        | Fases
        |--------------------------------------------------------------------------
        */

        $phases = $project->phases()
        ->withCount('activities')
        ->withAvg('activities', 'progress')
        ->withCount([
            'activities as completed_activities_count' =>
                fn ($query) => $query->where(
                    'status',
                    'completed'
                ),
        ])
        ->orderBy('order')
        ->get();



        $dataTable = new ProjectActivitiesDataTable(
            $project->id
        );


        return $dataTable->render(
            'projects.show',
            compact(
                'project',
                'projectMembers',
                'activities',
                'phases',
                'totalActivities',
                'completedActivities',
                'inProgressActivities',
                'progress',
                'overdueActivities'
            )
        );
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
