<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TicketCategoryController;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

// Necesitamos crear un grupo de rutas con el prefix dashboard
Route::middleware(['auth', 'verified'])->prefix('dashboard')->group(function () {
    // La primer ruta debe ser dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('tickets', TicketController::class);
    Route::get('get-subcategories/{parent}', [TicketCategoryController::class, 'subcategories'])->name('get-subcategories');
    Route::put('tickets/{ticket}/update-category', [TicketController::class, 'update_category'])->name('tickets.update-category');
    Route::put('tickets/{ticket}/update-contact', [TicketController::class, 'update_contact'])->name('tickets.update-contact');
    Route::put('tickets/{ticket}/update-agent', [TicketController::class, 'update_agent'])->name('tickets.update-agent');
});


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
