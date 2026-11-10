<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use LdapRecord\Laravel\Import\Importer;
use LdapRecord\Models\ActiveDirectory\User as LdapUser;


class LdapUserController extends Controller
{
    /**
     * Muestra la vista principal del módulo de importación.
     */
    public function index()
    {
        $roles = Role::all();
        return view('configuration.controlUsers', compact('roles'));
    }

    /**
     * Busca usuarios en Active Directory en tiempo real.
     */
    public function search(Request $request)
    {
        $request->validate([
            'term' => 'required|string|min:3',
        ]);

        $term = $request->input('term');

        // Búsqueda en AD por sAMAccountName o Nombre
        $ldapUsers = LdapUser::whereContains('samaccountname', $term)
            ->orWhereContains('cn', $term)
            ->limit(15)
            ->get();

        $results = $ldapUsers->map(function ($user) {
            $username = $user->getFirstAttribute('samaccountname');
            
            // Verificar si el usuario ya fue creado en la BD de MySQL
            $importedUser = User::where('username', $username)->first();

            return [
                'guid'             => $user->getConvertedGuid(),
                'cn'               => $user->getFirstAttribute('cn'),
                'username'         => $username,
                'email'            => $user->getFirstAttribute('mail') ?? 'Sin correo registrado',
                'already_imported' => $importedUser ? true : false,
                'roles'            => $importedUser ? $importedUser->getRoleNames()->toArray() : [],
            ];
        });

        return response()->json($results);
    }

    /**
     * Importa el usuario seleccionado a MySQL y le asigna el rol indicado en el RBAC.
     */
    public function import(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'role'     => 'required|string|exists:roles,name',
        ]);

        // 1. Obtener objeto de Active Directory
        $ldapUser = LdapUser::where('samaccountname', $request->username)->firstOrFail();

        // 2. Ejecutar la importación nativa a la tabla `users`
        $importer = new Importer(
            new LdapUser,
            config('auth.providers.users.database')
        );

        $importedUser = $importer->run($ldapUser);

        // 3. Asignar el rol del RBAC mediante Spatie
        $importedUser->syncRoles([$request->role]);

        return response()->json([
            'status'  => 'success',
            'message' => "El usuario {$importedUser->name} fue importado con éxito y se le asignó el rol '{$request->role}'.",
        ]);
    }
}