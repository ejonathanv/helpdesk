<?php
namespace App\Http\Controllers;
use App\Models\Ticket;
use App\Models\ChatMessage;
use App\Providers\MensajeEnChatPorContacto;
use App\Notifications\NewContactChatMessage;
use App\Providers\MensajeEnChatPorIngeniero;
use App\Http\Requests\StoreChatMessageRequest;
use App\Http\Requests\UpdateChatMessageRequest;
class ChatMessageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreChatMessageRequest $request, Ticket $ticket)
    {
        $from = $request->type == 'guest' ? session('contact_name') : auth()->user()->name;
        $message = new ChatMessage();
        $message->ticket_id = $ticket->id;
        $message->from = $from;
        $message->message = $request->message;
        $message->save();
        $route = null;


        if($request->type == 'guest'){
            event(new MensajeEnChatPorContacto($ticket, $message));
            $route = route('guest.ticket', $ticket->id);
        }else{
            event(new MensajeEnChatPorIngeniero($ticket, $message));
            $route = route('tickets.show', $ticket->id);
        }
        
        return redirect()
            ->to($route)
            ->with('success', 'El mensaje ha sido enviado exitosamente.');
    }
    /**
     * Display the specified resource.
     */
    public function show(ChatMessage $chatMessage)
    {
        //
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ChatMessage $chatMessage)
    {
        //
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateChatMessageRequest $request, ChatMessage $chatMessage)
    {
        //
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ChatMessage $chatMessage)
    {
        //
    }
}
