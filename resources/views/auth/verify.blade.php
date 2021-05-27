@extends('layouts.app')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Подтверждение e-mail адреса') }}</div>

                <div class="card-body">
                    @if (session('resent'))
                        <div class="alert alert-success" role="alert">
                            {{ __('Новая ссылка отправлена Вам на почту') }}
                        </div>
                    @endif

                    {{ __('Перед тем как продолжить, перейдите по ссылке, отправленной Вам на почту, для подтверждения пароля.') }}
                    {{ __('Если Вы не получали письмо') }}, <a href="{{ route('verification.resend') }}">{{ __('нажмите сюда, чтобы отправить его повторно') }}</a>.
                </div>
            </div>
        </div>
    </div>
@endsection
