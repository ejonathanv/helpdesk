<x-mail::message>
# Hola {{ $ticket->contact_name }},

Gracias por contactarnos, hemos recibido tu solicitud y estaremos respondiendo a la brevedad posible.

<x-mail::button :url="''">
Ver ticket
</x-mail::button>

Saludos,<br>
{{ config('app.name') }}
</x-mail::message>
