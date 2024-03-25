<?php
namespace App\Http\Controllers;
use Inertia\Inertia;
use App\Models\Agent;
use App\Models\Ticket;
use App\Models\Department;
use Illuminate\Http\Request;
use App\Models\TicketCategory;
use App\Models\TicketAttachment;
use App\Providers\TicketArchivado;
use App\Providers\TicketModificado;
use App\Providers\DesarchivarTicket;
use App\Providers\AsignacionDeContacto;
use App\Providers\AsignacionDeIngeniero;
use App\Http\Requests\StoreTicketRequest;
use App\Http\Requests\UpdateTicketRequest;
use App\Http\Controllers\AccountController;
use App\Providers\EnviarCredencialesAlContacto;
class TicketController extends Controller
{
    public function __construct()
    {
        $this->middleware('agent-ticket-owner')->only('show');
        $this->middleware('ticket-cancelled')->only('show');
    }
    public function index(Request $request)
    {
        $user = auth()->user();
        $permission = $user->permission->name;
        $filterdata = $request->filterdata;
        $withoutAttended = $request->withoutAttended;
        $search = $request->search;
        $departments = Department::orderBy('name', 'ASC')->get();
        $agents = Agent::with('user')->get()->sortBy('user.name')->values()->all();
        $categories = TicketCategory::get();
        $statuses = [
            ['id' => 1, 'name' => 'Abierto'],
            ['id' => 2, 'name' => 'Resuelto'],
            ['id' => 3, 'name' => 'En proceso'],
            ['id' => 4, 'name' => 'En espera del cliente'],
            ['id' => 5, 'name' => 'Monitoreo'],
            ['id' => 6, 'name' => 'Cerrado'],
            ['id' => 7, 'name' => 'Cancelado'],
        ];
        $severities = [
            ['id' => 1, 'name' => 'Baja'],
            ['id' => 2, 'name' => 'Media'],
            ['id' => 3, 'name' => 'Alta'],
            ['id' => 4, 'name' => 'Urgente'],
        ];
        $priorities = [
            ['id' => 1, 'name' => 'Baja'],
            ['id' => 2, 'name' => 'Media'],
            ['id' => 3, 'name' => 'Alta'],
            ['id' => 4, 'name' => 'Urgente'],
        ];
        if($search){
            $tickets = Ticket::where('id', 'like', '%' . $search . '%')
                ->orWhere('subject', 'like', '%' . $search . '%')
                ->orWhere('account_name', 'like', '%' . $search . '%')
                ->orderBy('id', 'DESC')
                ->userPermission($permission)
                ->paginate(10);
        }elseif($filterdata){
            $tickets = Ticket::when($request->department, function ($query, $department_id) {
                return $query->where('department_id', $department_id);
            })->when($request->agent, function ($query, $agent_id) {
                return $query->where('agent_id', $agent_id);
            })->when($request->status, function ($query, $status_id) {
                return $query->where('status_id', $status_id);
            })->when($request->severity, function ($query, $severity_id) {
                return $query->where('severity_id', $severity_id);
            })->when($request->priority, function ($query, $priority_id) {
                return $query->where('priority_id', $priority_id);
            })->when($request->account, function ($query, $account_name) {
                return $query->where('account_name', $account_name);
            })
            ->orderBy('id', 'DESC')
            ->userPermission($permission)
            ->paginate(10)
            ->withQueryString();
        }elseif($withoutAttended){
            $tickets = Ticket::where('category_id', '')
                ->orWhere('severity_id', '')
                ->orWhere('priority_id', '')
                ->userPermission($permission)
                ->paginate(10);
        }else{
            $tickets = Ticket::orderBy('id', 'DESC')
                ->userPermission($permission)
                ->paginate(10);
        }
        return Inertia::render('Tickets/Index', [
            'tickets' => $tickets,
            'departments' => $departments,
            'agents' => $agents,
            'statuses' => $statuses,
            'severities' => $severities,
            'priorities' => $priorities,
            'categories' => $categories,
        ]);
    }
    public function create()
    {
        $accounts = app(AccountController::class)->list();
        return Inertia::render('Tickets/Create', [
            'accounts' => $accounts,
        ]);
    }
    public function store(StoreTicketRequest $request)
    {
        $ticket = $this->update_ticket(new Ticket(), $request);
        $ticket->histories()->create([
            'user_id' => auth()->user()->id,
            'ticket_id' => $ticket->id,
            'description' => 'Se creó el ticket #' . $ticket->number . '.'
        ]);
        return redirect()
            ->route('tickets.show', $ticket->id)
            ->with('success', 'El ticket ha sido creado exitosamente.');
    }
    public function update_ticket($ticket, $request, $type = null)
    {
        $user = auth()->user();
        $ticket->user_id = $type == 'guest' ? null : $user->id;
        $ticket->subject = $request->subject;
        $ticket->content = $request->content;
        $ticket->account_id = $request->account;
        $ticket->account_name = $request->account_name;
        if($type == null){
            if($user->permission->name == 'Ingeniero'){
                $agent = Agent::where('user_id', $user->id)->first();
                $ticket->agent_id = $agent->id;
                $ticket->department_id = $agent->department_id;
                event(new AsignacionDeIngeniero($agent, $ticket));
            }
        }
        $ticket->save();
        if ($request->hasFile('attachment')) {
            // We need to move the file to the public folder on attachments folder
            $file = $request->file('attachment');
            $fileName = $ticket->id . '.' . hexdec(uniqid()) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('attachments'), $fileName);
            // We need to update the ticket with the attachment
            $attachment = new TicketAttachment();
            $attachment->ticket_id = $ticket->id;
            $attachment->name = $file->getClientOriginalName();
            $attachment->file_name = $fileName;
            $attachment->file_path = 'attachments/' . $fileName;
            $attachment->save();
        }
        return $ticket;
    }
    public function show(Ticket $ticket)
    {
        $account = app(AccountController::class)->account($ticket->account_id);
        $contacts = app(AccountController::class)->contacts($ticket->account_id);
        $agents = Agent::with('user')->get()->sortBy('user.name')->values()->all();
        $categories = TicketCategory::where('parent_id', null)->get()->sortBy('name')->values()->all();
        return Inertia::render('Tickets/Show', [
            'ticket' => $ticket,
            'agents' => $agents,
            'account' => $account,
            'contacts' => $contacts,
            'categories' => $categories,
        ]);
    }
    public function chat(Ticket $ticket)
    {
        $messages = $ticket->messages()->orderBy('created_at', 'ASC')->get();
        return response()->json([
            'messages' => $messages,
        ]);
    }
    public function edit(Ticket $ticket)
    {
        //
    }
    public function update(UpdateTicketRequest $request, Ticket $ticket)
    {
        $status = $ticket->status_id;
        $priority = $ticket->priority_id;
        $severity = $ticket->severity_id;
        $ticket->status_id = $request->status_id;
        $ticket->priority_id = $request->priority_id;
        $ticket->severity_id = $request->severity_id;
        if ($status != $request->status_id) {
            $ticket->histories()->create([
                'user_id' => auth()->user()->id,
                'ticket_id' => $ticket->id,
                'description' => 'Se cambió el estado del ticket a ' . $ticket->status . '.'
            ]);
            event(new TicketModificado($ticket));
        }
        if ($priority != $request->priority_id) {
            $ticket->histories()->create([
                'user_id' => auth()->user()->id,
                'ticket_id' => $ticket->id,
                'description' => 'Se cambió la prioridad del ticket a ' . $ticket->priority . '.'
            ]);
        }
        if ($severity != $request->severity_id) {
            $ticket->histories()->create([
                'user_id' => auth()->user()->id,
                'ticket_id' => $ticket->id,
                'description' => 'Se cambió la severidad del ticket a ' . $ticket->severity . '.'
            ]);
        }
        $ticket->save();

        if($ticket->status_id === 7){
            return redirect()
                ->route('tickets.index')
                ->with('ticketCancelled', 'El ticket ha sido cancelado.');
        }

        return redirect()
            ->back()
            ->with('ticketDetails', 'El ticket ha sido actualizado exitosamente.');
    }
    public function update_category(Request $request, Ticket $ticket)
    {
        $request->validate([
            'category_one' => 'required|integer|exists:ticket_categories,id',
            'category_two' => 'nullable|integer|exists:ticket_categories,id',
            'category_three' => 'nullable|integer|exists:ticket_categories,id',
        ], [
            'category_one.required' => 'La categoría es requerida',
            'category_one.integer' => 'La categoría debe ser un número entero',
            'category_one.exists' => 'La categoría seleccionada no es válida',
            'category_two.integer' => 'La categoría debe ser un número entero',
            'category_two.exists' => 'La categoría seleccionada no es válida',
            'category_three.integer' => 'La categoría debe ser un número entero',
            'category_three.exists' => 'La categoría seleccionada no es válida',
        ]);
        $category = null;
        if ($request->category_one) {
            $category = TicketCategory::find($request->category_one);
        }
        if ($request->category_two) {
            $category = TicketCategory::find($request->category_two);
        }
        if ($request->category_three) {
            $category = TicketCategory::find($request->category_three);
        }
        if ($category) {
            $ticket->category_id = $category->id;
            $ticket->save();
        }
        $ticket->histories()->create([
            'user_id' => auth()->user()->id,
            'ticket_id' => $ticket->id,
            'description' => 'Se cambió la categoría del ticket a ' . $ticket->category->full_name . '.'
        ]);
        return redirect()
            ->back();
    }
    public function update_contact(Request $request, Ticket $ticket)
    {
        $request->validate([
            'contact_id' => 'required|string',
        ], [
            'contact_id.required' => 'El contacto es requerido'
        ]);
        $contact = app(AccountController::class)->contact($request->contact_id);
        $ticket->contact_id = $contact['id'];
        $ticket->contact_name = $contact['name'];
        $ticket->contact_email = $contact['email'];
        $ticket->save();
        event(new AsignacionDeContacto($contact, $ticket));
        $response = "Se asignó a " . $contact['name'] . " como contacto de la cuenta.";
        return redirect()->back()->with('ticketContact', $response);
    }
    public function send_credentials_to_contact($contact){
        $contact = app(AccountController::class)->get_password($contact);
        event(new EnviarCredencialesAlContacto($contact));
        return redirect()
            ->back()
            ->with('credentialsSended', 'Las credenciales han sido enviadas exitosamente.');
    }
    public function update_agent(Request $request, Ticket $ticket)
    {
        $request->validate([
            'agent_id' => 'required|integer|exists:agents,id',
        ], [
            'agent_id.required' => 'El ingeniero es requerido',
            'agent_id.integer' => 'El ingeniero debe ser un número entero',
            'agent_id.exists' => 'El ingeniero seleccionado no es válido',
        ]);
        $agent = Agent::find($request->agent_id);
        $ticket->agent_id = $agent->id;
        $ticket->department_id = $agent->department_id;
        $ticket->save();
        event(new AsignacionDeIngeniero($agent, $ticket));
        $response = "Se asignó a " . $agent->user->name . " como ingeniero responsable.";
        return redirect()
            ->back()
            ->with('ticketAgent', $response);
    }
    public function destroy(Ticket $ticket)
    {
        $archive = request()->archive;
        if ($archive) {
            event(new TicketArchivado($ticket));
            return redirect()
                ->route('tickets.index')
                ->with('ticketArchived', 'El ticket ha sido archivado exitosamente.');
        }else{
            $ticket->events()->delete();
            $ticket->histories()->delete();
            $ticket->messages()->delete();
            $ticket->forceDelete();
            return redirect()
                ->route('tickets.index')
                ->with('ticketArchived', 'El ticket ha sido eliminado exitosamente.');
        }
    }
    public function archived(Request $request){
        $filterdata = $request->filterdata;
        $search = $request->search;
        $departments = Department::orderBy('name', 'ASC')->get();
        $agents = Agent::with('user')->get()->sortBy('user.name')->values()->all();
        $categories = TicketCategory::get();
        $statuses = [
            ['id' => 1, 'name' => 'Abierto'],
            ['id' => 2, 'name' => 'Resuelto'],
            ['id' => 3, 'name' => 'En proceso'],
            ['id' => 4, 'name' => 'En espera del cliente'],
            ['id' => 5, 'name' => 'Monitoreo'],
            ['id' => 6, 'name' => 'Cerrado'],
            ['id' => 7, 'name' => 'Cancelado'],
        ];
        $severities = [
            ['id' => 1, 'name' => 'Baja'],
            ['id' => 2, 'name' => 'Media'],
            ['id' => 3, 'name' => 'Alta'],
            ['id' => 4, 'name' => 'Urgente'],
        ];
        $priorities = [
            ['id' => 1, 'name' => 'Baja'],
            ['id' => 2, 'name' => 'Media'],
            ['id' => 3, 'name' => 'Alta'],
            ['id' => 4, 'name' => 'Urgente'],
        ];
        if($search){
            $tickets = Ticket::onlyTrashed()
                ->where('id', 'like', '%' . $search . '%')
                ->orWhere('subject', 'like', '%' . $search . '%')
                ->orWhere('account_name', 'like', '%' . $search . '%')
                ->paginate(10);
        }elseif($filterdata){
            $tickets = Ticket::onlyTrashed()
            ->when($request->department, function ($query, $department_id) {
                return $query->where('department_id', $department_id);
            })->when($request->agent, function ($query, $agent_id) {
                return $query->where('agent_id', $agent_id);
            })->when($request->status, function ($query, $status_id) {
                return $query->where('status_id', $status_id);
            })->when($request->severity, function ($query, $severity_id) {
                return $query->where('severity_id', $severity_id);
            })->when($request->priority, function ($query, $priority_id) {
                return $query->where('priority_id', $priority_id);
            })->paginate(10)->withQueryString();
        }else{
            $tickets = Ticket::onlyTrashed()->paginate(10);
        }
        return Inertia::render('Tickets/Archived', [
            'tickets' => $tickets,
            'departments' => $departments,
            'agents' => $agents,
            'statuses' => $statuses,
            'severities' => $severities,
            'priorities' => $priorities,
            'categories' => $categories,
        ]);
    }
    public function restore($ticket)
    {
        event(new DesarchivarTicket($ticket));
        return redirect()
            ->route('tickets.show', $ticket)
            ->with('ticketRestored', 'El ticket ha sido restaurado exitosamente.');
    }
}
