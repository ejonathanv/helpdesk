<x-mail::message>
# Hola {{ $ticket->contact_name }},

El estado del ticket #{{ $ticket->number }} ha sido modificado a "{{ $ticket->status }}".

<x-mail::button :url="route('tickets.show', $ticket)">
    Ver ticket
</x-mail::button>

Saludos,<br>
{{ config('app.name') }}
</x-mail::message>
