<x-mail::message>
# Hola {{ $ticket->contact_name }},

Se ha registrado un nuevo evento en tu ticket #{{ $ticket->number }}.

<x-mail::button :url="route('tickets.show', $ticket)">
Ver Ticket
</x-mail::button>

Saludos,<br>
{{ config('app.name') }}
</x-mail::message>
