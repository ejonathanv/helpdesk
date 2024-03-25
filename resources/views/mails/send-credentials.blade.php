<x-mail::message>
# Hola {{ $contact['name'] }},

A continuación, te proporcionamos tus credenciales de acceso para HelpDesk Dynamic Communications:

- **Correo electrónico:** {{ $contact['email'] }}
- **Contraseña:** {{ $contact['password'] }}

Para iniciar sesión, haz clic en el siguiente botón:

<x-mail::button :url="route('guest.login')">
Iniciar sesión
</x-mail::button>

Saludos,<br>
{{ config('app.name') }}
</x-mail::message>
