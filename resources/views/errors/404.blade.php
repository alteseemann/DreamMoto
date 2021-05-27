@extends('layouts.app')

@php
    Artesaos\SEOTools\Facades\SEOMeta::setTitle('Страница не найдена')
@endphp

<style>
    html, body {
        background-color : #fff;
        color            : #636b6f;
        font-family      : 'Nunito', sans-serif;
        font-weight      : 100;
        height           : 100vh;
        margin           : 0;
    }

    .full-height {
        height : 50vh;
    }

    .flex-center {
        align-items     : center;
        display         : flex;
        justify-content : center;
    }

    .position-ref {
        position : relative;
    }

    .code {
        border-right : 2px solid;
        font-size    : 26px;
        padding      : 0 15px 0 15px;
        text-align   : center;
    }

    .message {
        font-size  : 18px;
        text-align : center;
    }
</style>

@section('message')
    <div class="flex-center position-ref full-height">
        <div class="code">
            404
        </div>

        <div class="message" style="padding: 10px;">
            Страница не найдена
        </div>
    </div>
@endsection

