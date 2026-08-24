@component('mail::message')
# Nuevo mensaje desde tu portfolio

**Nombre:** {{ $contactMessage->name }}
**Email:** {{ $contactMessage->email }}

**Mensaje:**

{{ $contactMessage->message }}

@component('mail::button', ['url' => 'mailto:' . $contactMessage->email])
Responder
@endcomponent

Podés ver todos los mensajes en tu panel admin.

Saludos,<br>
Tu portfolio
@endcomponent