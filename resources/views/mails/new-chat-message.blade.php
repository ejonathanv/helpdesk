<x-mail::message>
# Nuevo mensaje en ticket #{{ $ticket->number }}

Se ha recibido un nuevo mensaje en el ticket #{{ $ticket->number }}.

<x-mail::button :url="route('tickets.show', $ticket->id)">
Ver ticket
</x-mail::button>

Saludos,<br>
{{ config('app.name') }}
</x-mail::message>
