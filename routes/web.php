<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\IndexController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SesiController;
use App\Http\Middleware\TokenAuthenticate;
use App\Http\Controllers\ContenController;
use App\Http\Controllers\KandidatController;
use App\Http\Controllers\SuperadmController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\SentimenController;
use App\Http\Controllers\SentimenGrafikController;
use App\Http\Controllers\RegisterController;
use App\Models\event;
use App\Models\Kandidat;
use App\Models\token;

// Definisi rute untuk otentikasi
Route::get('/login', [SesiController::class, 'index'])->name('login');
Route::post('/login', [SesiController::class, 'login']);


// Grup rute yang memerlukan otentikasi
Route::middleware(['auth'])->group(function () {

    Route::get('/index', [IndexController::class, 'index']);
    Route::get('/index/admin', [IndexController::class, 'admin'])->middleware('userAkases:admin')->name('admin');
    Route::get('/index/admin', [IndexController::class, 'superadm'])->middleware('userAkases:pengelola')->name('superadm');
    Route::get('/index/admin', [IndexController::class, 'admin'])
        ->middleware('userAkases:admin,pengelola')
        ->name('admin');

    // Rute user
    Route::get('/index/user', [IndexController::class, 'user'])->middleware('userAkases:user')->name('user');
    Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('register', [RegisterController::class, 'register']);


    Route::get('/logout', [SesiController::class, 'logout'])->name('logout');
    Route::get('/create', [IndexController::class, 'create'])->name('adminall.tambah');
    Route::post('/store', [IndexController::class, 'store'])->name('adminall.store');

    // Route untuk menampilkan form edit profil
    Route::get('/edit-profile/{id}', [IndexController::class, 'editProfile'])->name('admin.editProfile');

    // Route untuk menghandle pengiriman form update profil
    Route::post('/admin/update-profile/{id}', [IndexController::class, 'updateProfile'])->name('admin.updateProfile');

    Route::delete('/admin/deleteProfile/{id}', [IndexController::class, 'deleteProfile'])->name('admin.deleteProfile');

    // Reviews routes
    Route::get('/reviews/create', [ReviewController::class, 'create'])->name('reviews.create');
    Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');
    Route::get('/reviews', [ReviewController::class, 'index'])->name('reviews.index');
    Route::post('/reviews/import', [ReviewController::class, 'import'])->name('reviews.import');
    Route::get('/sentimen', [SentimenController::class, 'index'])->name('sentimen.index');
    Route::delete('/reviews/{id}', [ReviewController::class, 'destroy'])->name('reviews.destroy');
    Route::get('/grafik', [SentimenGrafikController::class, 'index'])->name('grafik');

    // Added route for admin.index
    Route::get('/admin', [App\Http\Controllers\IndexController::class, 'admin'])->name('admin.index');
    Route::get('/sentimen/top-words', [SentimenController::class, 'topWords'])->name('sentimen.top-words');

    // SUPER ADM ROUTES

});

// Rute fallback untuk tampilan landing
Route::fallback(function () {
    return view('login');
});
