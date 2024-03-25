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
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\GuestLoginController;
use App\Http\Controllers\ChatMessageController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\GuestDashboardController;
use App\Http\Controllers\TicketCategoryController;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;
Route::redirect('/', '/dashboard');
// Necesitamos crear un grupo de rutas con el prefix dashboard
Route::middleware([
        'auth', 
        'verified', 
        'user-account-suspended'
    ])->prefix('dashboard')->group(function () {
    // Ruta para notificaciones
    Route::group(['prefix' => 'notifications',], function () {
        Route::get('/', [
            NotificationController::class, 'index'
        ])->name('notifications.index');
        Route::get('/{notification}', [
            NotificationController::class, 'read'
        ])->name('notifications.read');
        Route::post('delete-all-read', [
            NotificationController::class, 'deleteAllRead'
        ])->name('notifications.delete-all-read');
    });

    // La primera ruta debe ser dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    // Listado de cuentas
    Route::get('/accounts-list', [DashboardController::class, 'accounts'])->name('accounts-list');
    Route::resource('tickets', TicketController::class);
    // Tickets archivados
    Route::get('tickets-archived', [TicketController::class, 'archived'])->name('tickets.archived');
    Route::post('restore-ticket/{ticket}', [TicketController::class, 'restore'])->name('tickets.restore');
    // Detalles generales
    Route::get('get-subcategories/{parent}', [TicketCategoryController::class, 'subcategories'])->name('get-subcategories');
    Route::put('tickets/{ticket}/update-category', [TicketController::class, 'update_category'])->name('tickets.update-category');
    Route::put('tickets/{ticket}/update-contact', [TicketController::class, 'update_contact'])->name('tickets.update-contact');
    Route::post('send-credentials/{contact}', [TicketController::class, 'send_credentials_to_contact'])->name('send-credentials-to-contact');
    Route::put('tickets/{ticket}/update-agent', [TicketController::class, 'update_agent'])->name('tickets.update-agent');
    Route::get('tickets/{ticket}/chat', [TicketController::class, 'chat'])->name('tickets.chat');
    Route::resource('tickets.messages', ChatMessageController::class);
    // Eventos del ticket
    Route::resource('tickets.events', EventController::class);
    // Historias del ticket
    Route::resource('tickets.histories', HistoryController::class);
    // Edicion de ingenieros
    Route::resource('agents', AgentController::class)->middleware('admin-module');
    Route::post('agents/{agent}/personal-data', [AgentController::class, 'personal_data'])->name('agents.personal-data')->middleware('admin-module');
    Route::post('agents/{agent}/update-permissions', [AgentController::class, 'update_permissions'])->name('agents.update-permissions')->middleware('admin-module');
    Route::post('agents/{agent}/update-security', [AgentController::class, 'update_security'])->name('agents.update-security')->middleware('admin-module');
    Route::post('agents/{agent}/suspend', [AgentController::class, 'suspend'])->name('agents.suspend')->middleware('admin-module');
    // Rutas de configuracion
    Route::group(['prefix' => 'config', 'middleware' => 'admin-module'], function () {
        // Edicion de departamentos
        Route::resource('departments', DepartmentController::class);
    });
});
Route::group(['prefix' => 'guest', 'middleware' => 'prevent-guest-login'], function () {
    Route::get('login', [GuestLoginController::class, 'showLoginForm'])->name('guest.login');
    Route::post('login', [GuestLoginController::class, 'login'])->name('guest.login-post');
    Route::group(['middleware' => 'guest-auth'], function () {
        Route::post('logout', [GuestLoginController::class, 'logout'])->name('guest.logout');
        Route::get('dashboard', [GuestDashboardController::class, 'index'])->name('guest.dashboard');
        Route::get('tickets', [GuestDashboardController::class, 'tickets'])->name('guest.tickets');
        Route::get('tickets/{ticket}', [GuestDashboardController::class, 'ticket'])->name('guest.ticket');
        Route::get('new-ticket', [GuestDashboardController::class, 'newTicket'])->name('guest.new-ticket');
        Route::post('new-ticket', [GuestDashboardController::class, 'storeNewTicket'])->name('guest.new-ticket-post');
        Route::delete('tickets/{ticket}', [GuestDashboardController::class, 'cancel'])->name('guest.tickets.cancel');
        // El guest tiene que poder enviar un mensaje de chat a un ticket
        Route::post('tickets/{ticket}/messages', [ChatMessageController::class, 'store'])->name('guest.tickets.messages.store');
        // Debemos obtener todo el chat de un ticket
        Route::get('tickets/{ticket}/messages', [GuestDashboardController::class, 'chat'])->name('guest.tickets.messages.index');
        Route::get('tickets/{ticket}/histories', [GuestDashboardController::class, 'histories'])->name('guest.tickets.histories');
        Route::get('tickets/{ticket}/events', [GuestDashboardController::class, 'events'])->name('guest.tickets.events');
    });
});
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
require __DIR__ . '/auth.php';
