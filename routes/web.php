<?php

use App\DataTables\ActionsDataTable;
use App\DataTables\UsersDataTable;
use App\Http\Controllers\Admin\LdapUserController;
use App\Http\Controllers\AdminLte\UserController;
use App\Http\Controllers\Auth\TwoFactorEmailController;
use App\Http\Controllers\FamiliaController;
use App\Http\Controllers\ProjectActivityController;
use App\Http\Controllers\ProjectController;
use App\Models\Familia;
use App\Services\UnoeeSoapService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Actions\EnableTwoFactorAuthentication;
use Laravel\Fortify\TwoFactorAuthenticationProvider;

Route::redirect('/', '/login')->name('login');

Route::get('/', function (UsersDataTable $dataTable) {

    //$dataTable->render('pruebas.usuarios');
    return $dataTable->render('welcome');

    // return view('welcome', [
    //     'dataTable' => $dataTable,
    // ]);
});

// Route::get('/getRoles', function (Request $request):JsonResponse {

//     $search = $request->get('search', '');
//     $roles = \App\Models\Role::where('name', 'like', "%$search%")
//     ->select('id','name as text')
//     ->get()
//     ->map(function ($role) {
//         return [
//             'id' => (string) $role->id,
//             'text' => (string) $role->text,
//         ];
//     });
//     return response()->json([
//         'results' => $roles
//     ]);
// })->name('getRoles');


// Route::get('/getFamilias', [FamiliaController::class, 'getTreeData']);

// Route::get('/test-unoee', function (UnoeeSoapService $unoee) {

//     $respuesta = $unoee->generarXml(
//         'ti',
//         'Busscar2025$*'
//     );

//     dd($respuesta);
// });

// Route::get('/test-email', function () {
//     Mail::raw(
//         'Este es un correo de prueba enviado desde Laravel utilizando Microsoft 365.',
//         function ($message) {
//             $message
//                 ->to('mayerly.arango@busscar.com.co')
//                 ->subject('Prueba de correo Laravel');
//         }
//     );

//     return 'Correo enviado correctamente.';
// });

Route::post('/two-factor-login/email/send', [TwoFactorEmailController::class,'sendCode',])->name('two-factor.email.send');

Route::post('/two-factor-login/email/verify', [TwoFactorEmailController::class,'verifyCode',])->name('two-factor-login.email.verify');

// AdminLTE authentication routes
Route::middleware('guest')->group(function () {
    // Route::get('login', [\App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('login');
    // Route::post('login', [\App\Http\Controllers\Auth\LoginController::class, 'login']);

    Route::get('register', [\App\Http\Controllers\Auth\RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('register', [\App\Http\Controllers\Auth\RegisterController::class, 'register']);

    Route::get('forgot-password', [\App\Http\Controllers\Auth\ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('forgot-password', [\App\Http\Controllers\Auth\ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');

    Route::get('reset-password/{token}', [\App\Http\Controllers\Auth\ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('reset-password', [\App\Http\Controllers\Auth\ResetPasswordController::class, 'reset'])->name('password.update');

    Route::post('/two-factor/email/send',[TwoFactorEmailController::class, 'sendCode'])->name('two-factor.email.send');

    Route::post('/two-factor/email/verify',[TwoFactorEmailController::class, 'verifyCode'])->name('two-factor.email.verify');

});

Route::middleware('auth')->group(function () {
    // Email verification — protect app routes with the `verified` middleware once
    // your User model implements MustVerifyEmail (adminlte:make-auth wires it in).
    Route::get('email/verify', [\App\Http\Controllers\Auth\EmailVerificationController::class, 'notice'])->name('verification.notice');
    Route::get('email/verify/{id}/{hash}', [\App\Http\Controllers\Auth\EmailVerificationController::class, 'verify'])
        ->middleware(['signed', 'throttle:6,1'])->name('verification.verify');
    Route::post('email/verification-notification', [\App\Http\Controllers\Auth\EmailVerificationController::class, 'send'])
        ->middleware('throttle:6,1')->name('verification.send');

    // Password confirmation — guard sensitive actions with the `password.confirm` middleware.
    Route::get('confirm-password', [\App\Http\Controllers\Auth\ConfirmablePasswordController::class, 'show'])->name('password.confirm');
    Route::post('confirm-password', [\App\Http\Controllers\Auth\ConfirmablePasswordController::class, 'store']);

    Route::post('logout', [\App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');
    
    Route::get('/control-users', [LdapUserController::class, 'index'])->name('controlUsers.index');
    Route::get('/control-users/search', [LdapUserController::class, 'search'])->name('controlUsers.search');
    Route::post('/control-users/import', [LdapUserController::class, 'import'])->name('controlUsers.import');


    Route::get('/home', function () {

        $user = Auth::user();
        if (empty($user->two_factor_secret)) {
            app(EnableTwoFactorAuthentication::class)($user);
        }

        return view('home', ['mostrarModal2FA' => empty($user->two_factor_confirmed_at),]);
    });

    Route::post('/two-factor/email/send', [TwoFactorEmailController::class,'sendCode',])->name('two-factor.email.send');
    Route::post('/two-factor/email/verify', [TwoFactorEmailController::class,'verifyCode',])->name('two-factor.email.verify');

});

// AdminLTE scaffold routes
Route::middleware(['web', 'auth'])->prefix('admin')->name('adminlte.')->group(function () {
    // [adminlte:roles]
    Route::get('roles', [\App\Http\Controllers\AdminLte\RoleController::class, 'index'])->name('roles.index');
    Route::get('roles/create', [\App\Http\Controllers\AdminLte\RoleController::class, 'create'])->name('roles.create');
    Route::post('roles', [\App\Http\Controllers\AdminLte\RoleController::class, 'store'])->name('roles.store');
    Route::get('roles/{role}/edit', [\App\Http\Controllers\AdminLte\RoleController::class, 'edit'])->name('roles.edit');
    Route::put('roles/{role}', [\App\Http\Controllers\AdminLte\RoleController::class, 'update'])->name('roles.update');
    Route::delete('roles/{role}', [\App\Http\Controllers\AdminLte\RoleController::class, 'destroy'])->name('roles.destroy');

    Route::get('roles/getPermissions/{role}', [\App\Http\Controllers\AdminLte\RoleController::class, 'getPermissions'])->name('roles.getPermissions');
    Route::post('roles/asignPermissions/{role}', [\App\Http\Controllers\AdminLte\RoleController::class, 'asignPermissions'])->name('roles.asignPermissions');

    // [adminlte:users]
    Route::get('users', [\App\Http\Controllers\AdminLte\UserController::class, 'index'])->name('users.index');
    Route::get('users/create', [\App\Http\Controllers\AdminLte\UserController::class, 'create'])->name('users.create');
    Route::post('users', [\App\Http\Controllers\AdminLte\UserController::class, 'store'])->name('users.store');
    Route::get('users/{user}/edit', [\App\Http\Controllers\AdminLte\UserController::class, 'edit'])->name('users.edit');
    Route::put('users/{user}', [\App\Http\Controllers\AdminLte\UserController::class, 'update'])->name('users.update');
    Route::delete('users/{user}', [\App\Http\Controllers\AdminLte\UserController::class, 'destroy'])->name('users.destroy');

    // [adminlte:actions]
    Route::get('actions', [\App\Http\Controllers\AdminLte\ActionController::class, 'index'])->name('actions.index');
    Route::post('actions', [\App\Http\Controllers\AdminLte\ActionController::class, 'store'])->name('actions.store');
    Route::put('actions/{action}', [\App\Http\Controllers\AdminLte\ActionController::class, 'update'])->name('actions.update');
    Route::delete('actions/{action}', [\App\Http\Controllers\AdminLte\ActionController::class, 'destroy'])->name('actions.destroy');

    // [adminlte:permissions]
    Route::get('permissions', [\App\Http\Controllers\AdminLte\PermissionController::class, 'index'])->name('permissions.index');
    Route::post('permissions', [\App\Http\Controllers\AdminLte\PermissionController::class, 'store'])->name('permissions.store');
    Route::put('permissions/{permission}', [\App\Http\Controllers\AdminLte\PermissionController::class, 'update'])->name('permissions.update');

    Route::get('permissions/getActions/{permission}', [\App\Http\Controllers\AdminLte\PermissionController::class, 'getActions'])->name('permissions.getActions');
    Route::post('permissions/asignActions/{permission}',[\App\Http\Controllers\AdminLte\PermissionController::class, 'asignActions'])->name('permissions.asignActions');

});

Route::middleware(['web', 'auth'])->prefix('projects')->name('projects.')->group(function () {
    Route::get('/', [ProjectController::class,'index'])->name('index');
    Route::get('/data',[ProjectController::class, 'index'])->name('data');
    Route::get('/create', [ProjectController::class,'create'])->name('create');
    Route::post('/', [ProjectController::class,'store'])->name('store');
    Route::get('/{project}', [ProjectController::class,'show'])->name('show');
    Route::get('/{project}/edit', [ProjectController::class,'edit'])->name('edit');
    Route::put('/{project}', [ProjectController::class,'update'])->name('update');
    Route::delete('/{project}', [ProjectController::class,'destroy'])->name('destroy');
});

Route::middleware(['web', 'auth'])->prefix('activities')->name('activities.')->group(function () {

    Route::get('/create',[ProjectActivityController::class, 'create'])->name('create');
    Route::post('/{project}',[ProjectActivityController::class, 'store'])->name('store');
    Route::get('/{project}',[ProjectActivityController::class, 'show'])->name('show');
    Route::post('/edit/{project}',[ProjectActivityController::class, 'edit'])->name('edit');
});