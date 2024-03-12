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
        // CREACION DE TICKETS
        AsignacionDeContacto::class => [
            EnviarCorreoDeAsignacion::class,
        ],
        AsignacionDeIngeniero::class => [
            EnviarCorreoDeAsignacionAIngeniero::class,
            EnviarCorreoAContactoDeAsignacionAIngeniero::class,
        ],
        TicketModificado::class => [
            NotificarModificacionesAlContacto::class,
        ],
        // ARCHIVAR Y ELIMINAR TICKETS
        TicketArchivado::class => [
            EstadoDelTicketArchivado::class,
        ],
        DesarchivarTicket::class => [
            EstadoDelTicketDesarchivado::class,
        ],
        // CHAT DEL TICKET
        MensajeEnChatPorIngeniero::class => [
            NotificarAlContactoPorEmail::class,
        ],
        MensajeEnChatPorContacto::class => [
            NotificarAlIngenieroPorEmail::class,
        ],
        // EVENTOS
        NuevoEventoPublico::class => [
            NotificarDeNuevoEventoAlContacto::class,
        ],
        // USUARIOS
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
