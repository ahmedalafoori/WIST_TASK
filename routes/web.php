<?php

use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserAddController;
use App\Http\Controllers\UserProfileController;

Route::get('/', function () {
    return redirect()->route('tasks.index');
});

Auth::routes();

Route::middleware(['auth'])->group(function () {
    Route::get('/tasks', [TaskController::class, 'index'])->name('tasks.index');
    Route::get('/tasks/create', [TaskController::class, 'create'])->name('tasks.create');
    Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store');
    Route::get('/tasks/{task}/edit', [TaskController::class, 'edit'])->name('tasks.edit');
    Route::put('/tasks/{task}', [TaskController::class, 'update'])->name('tasks.update');
    Route::delete('/tasks/{task}', [TaskController::class, 'destroy'])->name('tasks.destroy');
    Route::patch('/tasks/{task}/done', [TaskController::class, 'markAsDone'])->name('tasks.markAsDone');


Route::get('/profile/edit', [UserProfileController::class, 'edit'])->name('profile.edit');
Route::post('/profile/update', [UserProfileController::class, 'update'])->name('profile.update');
Route::post('/password/update', [UserProfileController::class, 'updatePassword'])->name('password.update');

Route::get('/users/create', [UserAddController::class, 'create'])->name('users.create')->middleware('role:super_admin')->middleware('role:super_admin');
Route::resource('users', UserAddController::class)->middleware('role:super_admin');
Route::get('users/{id}/permissions', [UserAddController::class, 'managePermissions'])->name('users.permissions')->middleware('role:super_admin');
Route::post('users/{id}/permissions', [UserAddController::class, 'updatePermissions'])->name('users.permissions.update')->middleware('role:super_admin');

});
