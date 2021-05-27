

{{--<div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="LoginModalLongTitle"--}}
{{--     aria-hidden="true">--}}
{{--    <div class="modal-dialog">--}}
{{--        <div class="modal-content">--}}
{{--            <div class="modal-header">--}}
{{--                <h5 class="modal-title" id="LoginModalLongTitle">Вход</h5>--}}
{{--                <button type="button" class="close" data-dismiss="modal" aria-label="Close">--}}
{{--                    <span aria-hidden="true">&times;</span>--}}
{{--                </button>--}}
{{--            </div>--}}

{{--            <div class="modal-body">--}}

{{--                {!! Form::open( ['route' => 'login', 'method' => 'POST','class' => 'form-horizontal', 'role' => 'form']) !!}--}}

{{--                <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">--}}
{{--                    <label for="email" class="control-label">E-Mail</label>--}}
{{--                    <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}"--}}
{{--                           required autofocus>--}}

{{--                    @if ($errors->has('email'))--}}
{{--                        <span class="help-block">--}}
{{--                                        <strong>{{ $errors->first('email') }}</strong>--}}
{{--                                    </span>--}}
{{--                    @endif--}}

{{--                </div>--}}

{{--                <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">--}}
{{--                    <label for="password" class="control-label">Пароль</label>--}}

{{--                    <input id="password" type="password" class="form-control" name="password" required>--}}

{{--                    @if ($errors->has('password'))--}}
{{--                        <span class="help-block">--}}
{{--                                        <strong>{{ $errors->first('password') }}</strong>--}}
{{--                                    </span>--}}
{{--                    @endif--}}

{{--                </div>--}}

{{--                <div class="custom-control custom-checkbox mr-sm-2">--}}
{{--                    <input type="checkbox" class="custom-control-input" id="remember"--}}
{{--                           name="remember" {{ old('remember') ? 'checked' : '' }}>--}}
{{--                    <label class="custom-control-label" for="remember">Запомнить меня</label>--}}
{{--                </div>--}}

{{--                <a class="nav-link nav-link-top"--}}
{{--                   onclick="$('#loginModal').modal('hide');"--}}
{{--                   href="javascript:void(0);"--}}
{{--                   data-toggle="modal"--}}
{{--                   data-target="#registerModal">{{ __('Регистрация') }}--}}
{{--                </a>--}}

{{--            </div>--}}

{{--            <div class="modal-footer">--}}
{{--                <button type="submit" class="btn btn-primary">--}}
{{--                    Войти--}}
{{--                </button>--}}
{{--            </div>--}}

{{--            --}}{{--<a class="btn btn-link" href="{{ route('password.request') }}">--}}
{{--            --}}{{--Forgot Your Password?--}}
{{--            --}}{{--</a>--}}

{{--            {!! Form::close() !!}--}}

{{--        </div>--}}
{{--    </div>--}}
{{--</div>--}}
