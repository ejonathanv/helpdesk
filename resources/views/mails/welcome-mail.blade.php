<x-mail::message>
# Hola, {{ $name }}!

Se creó tu cuenta en el sistema HelpDesk de Dynamic Communications. <br>

Tus credenciales de acceso son las siguientes: <br>

**Correo electrónico:** {{ $email }} <br>
**Contraseña:** {{ $password }} <br>

<x-mail::button :url="route('login')">
Ingresar
</x-mail::button>

Saludos,<br>
{{ config('app.name') }}
</x-mail::message>
