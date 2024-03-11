<?php
namespace App\Providers;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        // ==============================
        // CREACION DE TICKETS
        // ==============================
        // Proceso al asignar a un contacto a un ticket
        AsignacionDeContacto::class => [
            EnviarCorreoDeAsignacion::class,
        ],
        // Proceso al asignar a un ingeniero a un ticket
        AsignacionDeIngeniero::class => [
            EnviarCorreoDeAsignacionAIngeniero::class,
        ],
        // Proceso al modificar un ticket
        TicketModificado::class => [
            NotificarModificacionesAlContacto::class,
        ],
        // ==============================
        // ARCHIVAR Y ELIMINAR TICKETS
        // ==============================
        // Proceso al archivar un ticket
        TicketArchivado::class => [
            EstadoDelTicketArchivado::class,
        ],
        // ==============================
        // CHAT DEL TICKET
        // ==============================
        // Que pasa si se manda un mensaje por chat en un ticket
        MensajeEnChatPorIngeniero::class => [
            NotificarAlContactoPorEmail::class,
        ],
        MensajeEnChatPorContacto::class => [
            NotificarAlIngenieroPorEmail::class,
        ],
        // ==============================
        // EVENTOS
        // ==============================
        // Que sucede al crear un evento en un ticket
        NuevoEventoPublico::class => [
            NotificarDeNuevoEventoAlContacto::class,
        ],
        // ==============================
        // USUARIOS
        // ==============================
        // Que sucede al crear un ingeniero
        UsuarioCreado::class => [
            EnviarDatosDeAccesoAlNuevoUsuario::class,
        ],

    ];
    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        //
    }
    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
