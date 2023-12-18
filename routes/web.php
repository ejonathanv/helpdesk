<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use App\Http\Controllers\AgentController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ChatMessageController;
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
    // Detalles generales
    Route::get('get-subcategories/{parent}', [TicketCategoryController::class, 'subcategories'])->name('get-subcategories');
    Route::put('tickets/{ticket}/update-category', [TicketController::class, 'update_category'])->name('tickets.update-category');
    Route::put('tickets/{ticket}/update-contact', [TicketController::class, 'update_contact'])->name('tickets.update-contact');
    Route::put('tickets/{ticket}/update-agent', [TicketController::class, 'update_agent'])->name('tickets.update-agent');
    Route::get('tickets/{ticket}/chat', [TicketController::class, 'chat'])->name('tickets.chat');
    Route::resource('tickets.messages', ChatMessageController::class);

    // Eventos del ticket
    Route::resource('tickets.events', EventController::class);

    // Historias del ticket
    Route::resource('tickets.histories', HistoryController::class);

    // Edicion de ingenieros
    Route::resource('agents', AgentController::class);
    Route::post('agents/{agent}/personal-data', [AgentController::class, 'personal_data'])->name('agents.personal-data');
    Route::post('agents/{agent}/update-permissions', [AgentController::class, 'update_permissions'])->name('agents.update-permissions');
    Route::post('agents/{agent}/update-security', [AgentController::class, 'update_security'])->name('agents.update-security');

});


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
