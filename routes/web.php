<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PhotoController;
use App\Http\Controllers\ReportController;

Route::get('/', function () {
    return view('home');
})->name('home');



Route::get('/login', function () {
    return view('auth.login');
})->name('login');
Route::get('/signup', function () {
    return view('auth.signup');
})->name('signup');
Route::get('/video', function () {
    return view('user.index');
})->name('video');
Route::get('/logout', [UserController::class, 'logout'])
->name('logout');


Route::post('/signup', [UserController::class, 'register'])
->name('signup.register');
Route::post('/login', [UserController::class, 'loginAuth'])
->name('login.auth');




Route::middleware('isAdmin')->prefix('/admin')->name('admin.')->group(function() {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    Route::prefix('/users')->name('users.')->group(function() {
        Route::get('/datatables', [UserController::class, 'datatables'])->name('datatables');
        Route::get('/', [UserController::class, 'index'])
        ->name('index');
        Route::get('/create', [UserController::class, 'create'])
        ->name('create');
        Route::post('/store', [UserController::class, 'store'])
        ->name('store');
        Route::get('/edit/{id}', [UserController::class, 'edit'])
        ->name('edit');
        Route::put('/update/{id}', [UserController::class, 'update'])
        ->name('update');
        Route::delete('/delete/{id}', [UserController::class, 'destroy'])
        ->name('delete');
        Route::get('/export', [UserController::class, 'exportExcel'])
        ->name('export');
        Route::get('/trash', [UserController::class, 'trash'])->name('trash');
        Route::patch('/restore/{id}', [UserController::class, 'restore'])->name('restore');
        Route::delete('/delete-permanent/{id}', [UserController::class, 'deletePermanent'])->name('delete_permanent');
    });

});

Route::middleware('isStaff')->prefix('/staff')->name('staff.')->group(function() {

    Route::get('/staff', function () {
        return view('staff.index');
    })->name('index');
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::patch('/reports/{report}/review', [ReportController::class, 'review'])->name('reports.review');
    Route::delete('/reports/{photo}/delete', [ReportController::class, 'deletePost'])->name('reports.deletePost');

});


Route::middleware('isUser')->prefix('/user')->name('user.')->group(function() {
    Route::get('/photos', [PhotoController::class, 'index'])->name('index');
    Route::get('/photos/create', [PhotoController::class, 'create'])->name('create');
    Route::post('/photos/store', [PhotoController::class, 'store'])->name('store');
    Route::post('/photos/{photo}/like', [PhotoController::class, 'like'])->name('like');
    Route::post('/photos/{photo}/comment', [PhotoController::class, 'commentStore'])->name('comment.store');
    Route::post('/photos/{photo}/report', [PhotoController::class, 'reportStore'])->name('report.store');
    Route::get('/profile/edit', [UserController::class, 'editProfile'])->name('profile.edit');
    Route::put('/profile/update', [UserController::class, 'updateProfile'])->name('profile.update');
});
