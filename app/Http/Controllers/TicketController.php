<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Agent;
use App\Models\Ticket;
use Illuminate\Http\Request;
use App\Models\TicketCategory;
use App\Models\TicketAttachment;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\StoreTicketRequest;
use App\Http\Requests\UpdateTicketRequest;
use App\Http\Controllers\AccountController;

class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tickets = Ticket::all();

        return Inertia::render('Tickets/Index', [
            'tickets' => $tickets
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $accounts = app(AccountController::class)->list();
        return Inertia::render('Tickets/Create', [
            'accounts' => $accounts,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTicketRequest $request)
    {
        $ticket = $this->update_ticket(new Ticket(), $request);

        return redirect()
            ->route('tickets.show', $ticket->id)
            ->with('success', 'El ticket ha sido creado exitosamente.');
    }

    public function update_ticket($ticket, $request){
        $ticket->user_id = auth()->user()->id;
        $ticket->subject = $request->subject;
        $ticket->content = $request->content;
        $ticket->account_id = $request->account;
        $ticket->account_name = $request->account_name;

        $ticket->save();

        if($request->hasFile('attachment')){
            // We need to move the file to the public folder on attachments folder
            $file = $request->file('attachment');
            $fileName = $ticket->id . '.' . hexdec(uniqid()) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('attachments'), $fileName);

            // We need to update the ticket with the attachment
            $attachment = new TicketAttachment();
            $attachment->ticket_id = $ticket->id;
            $attachment->file_name = $fileName;
            $attachment->file_path = 'attachments/' . $fileName;

            $attachment->save();
        }

        return $ticket;
    }

    /**
     * Display the specified resource.
     */
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

    public function chat(Ticket $ticket){
        $messages = $ticket->messages()->orderBy('created_at', 'ASC')->get();

        return response()->json([
            'messages' => $messages,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Ticket $ticket)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTicketRequest $request, Ticket $ticket)
    {
        $ticket->status_id = $request->status_id;
        $ticket->priority_id = $request->priority_id;
        $ticket->severity_id = $request->severity_id;

        $ticket->save();

        return redirect()
            ->back()
            ->with('ticketDetails', 'El ticket ha sido actualizado exitosamente.');
    }

    public function update_category(Request $request, Ticket $ticket){

        $category = null;
        if($request->category_one){
            $category = TicketCategory::find($request->category_one);
        }

        if($request->category_two){
            $category = TicketCategory::find($request->category_two);
        }

        if($request->category_three){
            $category = TicketCategory::find($request->category_three);
        }

        if($category){
            $ticket->category_id = $category->id;
            $ticket->save();
        }

        return redirect()
            ->back();
    }

    public function update_contact(Request $request, Ticket $ticket){
        $contact = app(AccountController::class)->contact($request->contact_id);

        $ticket->contact_id = $contact['id'];
        $ticket->contact_name = $contact['name'];
        $ticket->contact_email = $contact['email'];

        $ticket->save();

        $response = "Se asignó a " . $contact['name'] . " como contacto de la cuenta.";

        return redirect()
            ->back()
            ->with('ticketContact', $response);
    }

    public function update_agent(Request $request, Ticket $ticket){
        $agent = Agent::find($request->agent_id);
        $ticket->agent_id = $agent->id;
        $ticket->save();

        $response = "Se asignó a " . $agent->user->name . " como ingeniero responsable.";

        return redirect()
            ->back()
            ->with('ticketAgent', $response);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ticket $ticket)
    {
        //
    }
}
