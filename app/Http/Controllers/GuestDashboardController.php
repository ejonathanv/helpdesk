<?php
namespace App\Http\Controllers;
use Inertia\Inertia;
use App\Models\Ticket;
use App\Models\Permission;
use Illuminate\Http\Request;
use App\Models\UserPermission;
use App\Mail\GuestTicketCreated;
use Illuminate\Support\Facades\Mail;
use App\Notifications\NewGuestTicket;
use App\Http\Requests\CancelTicketRequest;
use App\Providers\TicketCanceladoPorContacto;
use App\Http\Requests\StoreGuestTicketRequest;

class GuestDashboardController extends Controller
{

    public function __construct()
    {
        $this->middleware('guest-ticket')->only('ticket', 'chat', 'histories', 'events');
        $this->middleware('ticket-cancelled')->only('ticket');
    }

    public function index() {
        return redirect()->route('guest.tickets');
    }
    public function accounts(){
        $accounts = session()->get('accounts');
        // Necesitamos convertir el stdClass accounts en un array
        $accounts = json_decode(json_encode($accounts), true);
        // El arrya de accounts esta asi ['id' => 'nombre'] pero deberia estar asi ['id' => 'id', 'name' => 'nombre']
        // Entonces vamos a recorrer el array y vamos a crear un nuevo array con el formato que necesitamos
        $accountsFormat = [];
        foreach($accounts as $key => $value){
            $accountsFormat[] = [
                'id' => $key,
                'name' => $value,
            ];
        }
        return $accountsFormat;
    }
    public function tickets(Request $request){
        $search = $request->search;
        // Vamos a obtener el ID del contacto que esta en session
        $contact_id = session('contact_id');
        // Si estamos realizando una busqueda, vamos a buscar los tickets que esten relacionados con el contacto y que coincidan con la busqueda,
        if($search){
            $tickets = Ticket::where('contact_id', $contact_id)
                ->where('id', 'like', '%' . $search . '%')
                ->orWhere('subject', 'like', '%' . $search . '%')
                ->paginate(10);
            return Inertia::render('Guest/Tickets', [
                'tickets' => $tickets,
            ]);
        }
        // Si no, vamos a buscar todos los tickets que esten relacionados con el contacto
        $tickets = Ticket::where('contact_id', $contact_id)->paginate(10);
        // Y vamos a retornar la vista con los tickets
        return Inertia::render('Guest/Tickets', [
            'tickets' => $tickets,
        ]);
    }
    public function ticket(Ticket $ticket){
        $agent = $ticket->agent;
        return Inertia::render('Guest/Ticket', [
            'ticket' => $ticket,
            'attachments' => $ticket->attachments,
            'agent' => $agent,
        ]);
    }
    public function newTicket(){
        $accounts = $this->accounts();
        return Inertia::render('Guest/NewTicket', [
            'accounts' => $accounts,
        ]);
    }
    public function storeNewTicket(StoreGuestTicketRequest $request){
        $ticket = app(TicketController::class)->update_ticket(new Ticket(), $request, 'guest');
        // Vamos a actualizar el contact_id, contact_name y contact_email del ticket con los datos del contacto que esta en session
        $ticket->contact_id = session('contact_id');
        $ticket->contact_name = session('contact_name');
        $ticket->contact_email = session('contact_email');
        $ticket->save();
        // Despues vamos a crear una historia para el ticket con la informacion de que se creo el ticket
        $ticket->histories()->create([
            'user_id' => null,
            'contact_name' => session('contact_name'),
            'ticket_id' => $ticket->id,
            'description' => session('contact_name') . ' creó el ticket.'
        ]);
        // Vamos a enviar una notificacion a los administradores para notificarles que se creó un nuevo ticket
        $permission = Permission::where('name', 'Administrador')->first();
        $admins = UserPermission::where('permission_id', $permission->id)->get();
        foreach($admins as $admin){
            $admin = $admin->user;
            $admin->notify(new NewGuestTicket($ticket));
        }
        // Vamos a enviar un email al guest para notificarle que se creó un ticket
        Mail::to(session('contact_email'))->send(new GuestTicketCreated($ticket));
        // Redireccionamos al guest a la vista del ticket
        return redirect()->route('guest.ticket', $ticket->id)
            ->with('ticketDetails', 'Se creó el ticket correctamente.');
    }
    public function cancel(CancelTicketRequest $request, Ticket $ticket){
        $reason = $request->reason;

        event(new TicketCanceladoPorContacto($ticket, $reason));

        return redirect()->route('guest.tickets')
            ->with('ticketDetails', 'Se canceló el ticket correctamente.');
    }
    public function chat(Ticket $ticket){
        $messages = $ticket->messages()->orderBy('created_at', 'ASC')->get();
        return response()->json([
            'messages' => $messages,
        ]);
    }
    public function histories(Ticket $ticket){
        $histories = $ticket->histories()
            ->orderBy('created_at', 'asc')
            ->with('user')
            ->get();

        return response()->json($histories);
    }
    public function events(Ticket $ticket){
        $events = $ticket->events()
            ->where('publicAs', 'public')
            ->orderBy('created_at', 'asc')
            ->with('attachments')
            ->get();
        return response()->json($events);
    }
}
