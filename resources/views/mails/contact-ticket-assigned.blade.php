<x-mail::message>
# Hola, {{ $ticket->contact_name }}!

Estamos atendiendo tu ticket y te estaremos notificando cualquier actualización. <br>

Los detalles del ticket son los siguientes: <br>

**Número:** #{{ $ticket->number }} <br>
**Asunto:** {{ $ticket->subject }} <br>

<x-mail::button :url="route('tickets.show', $ticket)">
Ver ticket
</x-mail::button>

Saludos,<br>
{{ config('app.name') }}
</x-mail::message>
