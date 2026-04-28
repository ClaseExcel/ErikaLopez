<?php

use App\Http\Controllers\admin\EmpleadoController;
use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Admin\TutorialController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/login');
Route::get('/home', function () {
    if (Auth::user()->role_id != 7 && Auth::user()->role_id != 8) {
        return redirect()->route('admin.informesgerenciales.index');
    } else {
        return redirect()->route('admin.informesgerenciales.index');
    }
});

Auth::routes(['register' => false]);

Route::group(['as' => 'admin.', 'middleware' => ['auth']], function () {
    Route::post('filtro-cliente', [HomeController::class, 'filtroCliente'])->name('filtro.cliente');
});

Route::group(['prefix' => 'admin', 'as' => 'admin.', 'namespace' => 'Admin', 'middleware' => ['auth']], function () {
    
    Route::get('/', 'HomeController@index')->name('home');
    
    // $permissionsApi = session('permissionsApi');
    // if (!empty($permissionsApi['estado']) && $permissionsApi['estado'] == '1') {
    // }
    Route::get('events', 'HomeController@events')->name('events'); 
    // Permissions
    Route::delete('permissions/destroy', 'PermissionsController@massDestroy')->name('permissions.massDestroy');
    Route::resource('permissions', 'PermissionsController');

    // Roles
    Route::delete('roles/destroy', 'RolesController@massDestroy')->name('roles.massDestroy');
    Route::resource('roles', 'RolesController');

    // Users    
    Route::delete('users/destroy', 'UsersController@massDestroy')->name('users.massDestroy');
    Route::resource('users', 'UsersController');

    // Empleados
    // Route::delete('empleados/destroy', 'EmpleadosController@massDestroy')->name('empleados.massDestroy');
    Route::resource('empleados', EmpleadoController::class)->names('empleados')->except('destroy');
    Route::get('empleado/estado/{id}', [EmpleadoController::class, 'statusUser'])->name('update.status');

    Route::get('tutoriales', [TutorialController::class, 'index'])->name('tutoriales.index');
});
Route::group(['prefix' => 'profile', 'as' => 'profile.', 'namespace' => 'Auth', 'middleware' => ['auth']], function () {
    // Change password
    if (file_exists(app_path('Http/Controllers/Auth/ChangePasswordController.php'))) {
        Route::get('password', 'ChangePasswordController@edit')->name('password.edit');
        Route::post('password', 'ChangePasswordController@update')->name('password.update');
        Route::post('profile', 'ChangePasswordController@updateProfile')->name('password.updateProfile');
        Route::post('profile/destroy', 'ChangePasswordController@destroy')->name('password.destroyProfile');
    }
});
