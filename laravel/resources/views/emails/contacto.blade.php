@component('mail::message')
# Muchas gracias por comunicarte con nosotros estimado/a {{ $name }}

Un representante de ventas se contactara con usted.


Atentamente,<br>
{{ config('app.name') }}
@endcomponent
