{{--@component('mail::message')--}}
    Сообщение с сайта DreamMoto<br/><br/>
    Имя: {{ $name }}<br/>
    Email: {{ $email }}<br/>
    Сообщение:<br/>
    {{ $text }}
{{--@endcomponent--}}