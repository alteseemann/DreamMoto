@component('mail::message')

Здравствуйте, Вы получили данное сообщение, потому что зарегистрировались на DreamMoto.ru. Для подтверждения регистрации перейдите по ссылке:

@component('mail::button', ['url' => route('register.verify', ['token' => $user->verify_token])])
Подтвердить
@endcomponent

С уважением, <br>
{{ config('app.name') }}
@endcomponent
