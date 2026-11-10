<?php

namespace App\Http\Controllers\AdminLte;

use App\DataTables\ActionsDataTable;
use App\Http\Controllers\Controller;
use App\Models\AdminlteAction;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;


class ActionController extends Controller
{

    private function authorizeManage(): void
    {
        abort_unless(auth()->user()?->hasPermission('manage-roles'), 403);
    }
 

    /**
     * Display a listing of the resource.
     */
    public function index(ActionsDataTable $dataTable)
    {
        $this->authorizeManage();

        return $dataTable->render('adminlte.actions.index');
    }

    /**
     * Store a newly created resource in storage.
     */
     public function store(Request $request)
    {
        $this->authorizeManage();

        $validated = $request->validate([
                'module' => ['required', 'string', 'max:255'],
                'route' => ['required', 'string', 'max:255'],
            ]
        );


        $action = AdminlteAction::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Acción creada correctamente.',
            'data' => $action,
        ]);
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
    public function update(Request $request, AdminlteAction $action)
    {
        $this->authorizeManage();

        $validated = $request->validate([
                'module' => ['required', 'string', 'max:255'],
                'route' => ['required', 'string', 'max:255'],
            ]
        );

        $action->update([
            'module' => $validated['module'],
            'route' => $validated['route'] ?? null,
        ]);

       
        return response()->json([
            'success' => true,
            'message' => 'Acción editada correctamente.',
            'data' => $action,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AdminlteAction $action)
    {
        $this->authorizeManage();

        $action->delete();

        return response()->json([
            'success' => true,
            'message' => 'Acción eliminada correctamente.',
            'data' => $action,
        ]);
        
    }
}   

