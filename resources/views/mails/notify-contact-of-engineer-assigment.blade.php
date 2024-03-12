<x-mail::message>
# Hola {{ $ticket->contact_name }},

Tu ticket #{{ $ticket->number }} ha sido asignado al ingeniero {{ $ticket->agent->name }}. Puedes ver el ticket en el siguiente enlace:

<x-mail::button :url="route('guest.ticket', $ticket)">
    Ver ticket
</x-mail::button>

Saludos,<br>
{{ config('app.name') }}
</x-mail::message>
