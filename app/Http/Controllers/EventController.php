<?php
namespace App\Http\Controllers;
use App\Models\Event;
use App\Models\Ticket;
use App\Mail\NewPublicEvent;
use App\Models\EventAttachment;
use Illuminate\Support\Facades\Mail;
use App\Providers\NuevoEventoPublico;
use App\Http\Requests\StoreEventRequest;
use App\Http\Requests\UpdateEventRequest;

class EventController extends Controller
{
    public function index(Ticket $ticket)
    {
        $events = $ticket->events()
            ->orderBy('created_at', 'asc')
            ->with('attachments')
            ->get();
        return response()->json($events);
    }
    public function create()
    {
        //
    }
    public function store(StoreEventRequest $request, Ticket $ticket)
    {
        $event = $this->set_event_data($request, $ticket, new Event());
        $history = [
            'user_id' => auth()->user()->id,
            'ticket_id' => $ticket->id,
            'description' => 'Se ha creado un nuevo evento',
        ];
        $ticket->histories()->create($history);

        if($event->publicAs == 'public'){
            event(new NuevoEventoPublico($ticket, $event));
        }

        return redirect()->back()->with('newEventCreated', 'Evento creado correctamente');
    }
    public function set_event_data($request, $ticket, $event)
    {
        $totalTime = ($request->days * 24 * 60) + ($request->hours * 60) + $request->minutes;
        $event->user_id = auth()->user()->id;
        $event->ticket_id = $ticket->id;
        $event->comments = $request->comments;
        $event->days = $request->days ? $request->days : 0;
        $event->hours = $request->hours ? $request->hours : 0;
        $event->minutes = $request->minutes ? $request->minutes : 0;
        $event->total_time = $totalTime;
        $event->type = $request->type;
        $event->publicAs = $request->publicAs;
        $event->save();
        if($request->file('files')){
            foreach($request->file('files') as $file){
                $fileName = $file->getClientOriginalName();
                $file->move(public_path('files'), $fileName);
                $eventFile = new EventAttachment();
                $eventFile->event_id = $event->id;
                $eventFile->ticket_id = $ticket->id;
                $eventFile->file_name = $fileName;
                $eventFile->file_ext = $file->getClientOriginalExtension();
                $eventFile->original_file_name = $file->getClientOriginalName();
                $eventFile->file_path = asset('files/' . $fileName);
                $eventFile->save();
            }
        }
        return $event;
    }
    public function show(Event $event)
    {
        //
    }
    public function edit(Event $event)
    {
        //
    }
    public function update(UpdateEventRequest $request, Event $event)
    {
        //
    }
    public function destroy(Ticket $ticket, Event $event)
    {
        $event->delete();
        return redirect()->back()->with('eventDeleted', 'Evento eliminado correctamente');
    }
}
