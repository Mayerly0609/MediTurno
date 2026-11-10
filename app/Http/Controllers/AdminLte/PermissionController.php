<?php

namespace App\Http\Controllers\AdminLte;

use App\Http\Controllers\Controller;
use App\Models\AdminlteAction;
use App\Models\AdminltePermission;
use App\Models\Permission;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class PermissionController extends Controller
{

    private function authorizeManage(): void
    {
        abort_unless(auth()->user()?->hasPermission('manage-roles'), 403);
    }
 

    /**
     * Display a listing of the resource.
     */
    public function index() : View {
        $this->authorizeManage();

        $actions = AdminlteAction::query()
                        ->orderBy('module')
                        ->orderBy('route')
                        ->get();

        $permissions_created = AdminltePermission::query()
                        ->withCount('actions')
                        ->orderBy('id','DESC')
                        ->get();

        return view('adminlte.permissions.index', compact('permissions_created','actions'));

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorizeManage();
        
        $validated = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'label' => ['nullable','string', 'max:255'],
            ]
        );
        
        $permission = AdminltePermission::create($validated);

        return redirect()->route('adminlte.permissions.index')
            ->with('status', 'Permiso Creado');

        //
    }

    public function getActions(AdminltePermission $permission): JsonResponse {

        $idActions = $permission->actions()->pluck('adminlte_action_permission.action_id');

        return response()->json([
            'ids' => $idActions
        ]);

    }

    public function asignActions(Request $request, AdminltePermission $permission): RedirectResponse {
        $this->authorizeManage();


        $validated = $request->validate([
                'actions' => ['nullable', 'array'],
                'actions.*' => [
                'integer',
                'exists:adminlte_actions,id',
            ],
        ]);


        $permission->actions()->sync($validated['actions'] ?? []);


        return redirect()->route('adminlte.permissions.index')
            ->with('status', 'Permiso actualizado con las acciones seleccionadas');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
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
    public function update(Request $request, Permission $permission): RedirectResponse
    {
        $this->authorizeManage();

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:adminlte_roles,name,'.$permission->id],
            'label' => ['nullable', 'string', 'max:255'],
        ]);

        $permission->update([
            'name' => $data['name'],
            'label' => $data['label'] ?? null,
        ]);

        return redirect()->route('adminlte.permissions.index')
            ->with('status', __('adminlte.role_updated'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
