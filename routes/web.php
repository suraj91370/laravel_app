<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');



Route::group(['middleware' => ['auth', 'role:Admin,Manager,User']], function () {
    Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])
        ->name('dashboard');
});

// User management routes
Route::prefix('users')->group(function () {
    Route::get('/', [UserController::class, 'index'])->name('users.index')
        ->middleware('role:Admin,Manager,User');

    Route::get('/{user}/edit', [UserController::class, 'edit'])->name('users.edit')
        ->middleware('role:Admin,Manager');

    Route::put('/{user}', [UserController::class, 'update'])->name('users.update')
        ->middleware('role:Admin,Manager');

    Route::delete('/{user}', [UserController::class, 'destroy'])->name('users.destroy')
        ->middleware('role:Admin');
});

Route::get('/users/data', [UserController::class, 'getUsers'])
    ->name('users.data')
    ->middleware('role:Admin,Manager,User');

// Route::get('/check-users', function () {
//     $users = App\Models\User::all(['id', 'name', 'isactive']);
//     return response()->json($users);
// });
