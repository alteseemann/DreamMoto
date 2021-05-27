<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{--    Фавикон--}}
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="manifest" href="/site.webmanifest">
    <link rel="mask-icon" href="/safari-pinned-tab.svg" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#ffffff">

    @yield('additional_plugins')

    <link rel="icon" type="image/x-icon" href="/favicon.ico">

    {!! SEO::generate(true) !!}
    {{--    {!! SEOMeta::generate() !!}--}}

    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    @if (request()->getHost() == 'dreammoto.ru')
    <!-- Yandex.Metrika counter -->
        <script type="text/javascript">
            (function (m, e, t, r, i, k, a) {
                m[i]   = m[i] || function () {
                    (m[i].a = m[i].a || []).push(arguments)
                };
                m[i].l = 1 * new Date();
                k = e.createElement(t), a = e.getElementsByTagName(t)[0], k.async = 1, k.src = r, a.parentNode.insertBefore(k, a)
            })
            (window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym");

            ym(51529445, "init", {
                clickmap           : true,
                trackLinks         : true,
                accurateTrackBounce: true,
                webvisor           : true
            });
        </script>
        <noscript>
            <div><img src="https://mc.yandex.ru/watch/51529445" style="position:absolute; left:-9999px;" alt=""/></div>
        </noscript>
        <!-- /Yandex.Metrika counter -->
        <!-- Global site tag (gtag.js) - Google Analytics -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=UA-131766651-1"></script>
        <script>
            window.dataLayer = window.dataLayer || [];

            function gtag() {
                dataLayer.push(arguments);
            }

            gtag('js', new Date());

            gtag('config', 'UA-131766651-1');
        </script>

        <meta name="google-site-verification" content="XjzQOu1_nnLZtFS_Hwz-WZChKKfXXHxUc-_HZ-9iGKc"/>
        <meta name="mailru-domain" content="2xwASwRjBzV7NQTZ"/>
        <meta name="yandex-verification" content="d2e5aab43ab2b2cc"/>
    @endif

    {{--    Google ADS--}}
{{--    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>--}}

</head>
<body>
<div id="app">

    <div id="header">
        <nav class="navbar navbar-expand-lg navbar-dark bg-primary navbar-top py-0 px-0">
            <div class="container">
                <button class="navbar-toggler" type="button" data-toggle="collapse"
                        data-target="#navbarSupportedContent"
                        aria-controls="navbarSupportedContent" aria-expanded="false"
                        aria-label="{{ __('Toggle navigation') }}">
                    <i class="fal fa-bars"></i>
                    {{--                    <span class="navbar-toggler-icon"></span>--}}
                </button>

                <a class="navbar-brand mr-5" href="{{ Helpers::set_route_city(route('welcome')) }}">
                    {{--                <a class="navbar-brand mr-5" href="{{ route('welcome') }}">--}}
                    <img src="/images/logo.svg" alt="{{ config('app.name', 'Laravel') }}"
                         style="height: 17px; margin-top: -5px;">
                </a>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto list-top-menu">

                        @foreach ($motos as $moto)
                            {{--                        <li class="nav-item {{ Request::is(route('moto.index', $moto->alias) . '*') ? 'active' : '' }}">--}}
                            <li class="nav-item {{ Request::is('*' . $moto->alias . '*') ? 'active' : '' }}">
                                {{--                            {{ Helpers::set_menu_active(['/'.$moto->alias]) }}--}}
                                <a class="nav-link nav-link-top font-weight-bold position-relative"
                                   href="{{ route('catalog.index', $moto->alias) }}">{{ $moto->title }}</a>
                            </li>
                        @endforeach

                            <li class="nav-item">
                                <a class="nav-link nav-link-top font-weight-bold position-relative"
                                   target="_blank"
                                   href="https://joymotors.ru">Автомобили</a>
                            </li>

                    </ul>
                {{--{{dd(Request::input('moto_alias'))}}--}}
                <!-- Right Side Of Navbar -->

                </div>

                <ul class="navbar-nav ml-auto login-link">
                    @guest
                        <li class="nav-item">
                            <a class="nav-link nav-link-top nav-link-top-normal user-login-link"
                               href="{{ route(('login')) }}">Войти</a>
                        </li>

{{--                        <login-form></login-form>--}}

                        {{--                               href="{{ route('login') }}">{{ __('Войти') }}</a>--}}

                    @else

                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link nav-link-top nav-link-top-normal user-login-link"
                               href="javascript:void(0);"
                               role="button"
                               data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                {{ mb_strimwidth( Auth::user()->name, 0, 10, "...") }} <span class="caret"></span>
                            </a>

                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="{{route('home.personal')}}">
                                    Личный кабинет
                                </a>
                                <a class="dropdown-item" href="{{ route('home.sales') }}">
                                    Мои объявления
                                </a>
                                <a class="dropdown-item" href="{{ route('home.settings') }}">
                                    Настройки
                                </a>
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                   onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                    {{ __('Выйти') }}
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                      style="display: none;">
                                    @csrf
                                </form>
                            </div>
                        </li>
                    @endguest

                </ul>

            </div>
        </nav>

        {{--    РСЯ --}}
        @if(!Auth::check())
            @if($user_agent->isMobile())
                @if (Route::currentRouteName() != 'catalog.product')
                    <ad-block
                            div_id="{{ 'yandex_rtb_R-A-347397-17' }}"
                            script="{{ Helpers::get_ad_block('R-A-347397-17', 'yandex_rtb_R-A-347397-17', null) }}"
                    >
                    </ad-block>
                @endif
            @endif

            @if($user_agent->isDesktop())
                <ad-block
                        div_id="{{ 'yandex_rtb_R-A-347397-30' }}"
                        script="{{ Helpers::get_ad_block('R-A-347397-30', 'yandex_rtb_R-A-347397-30', null) }}"
                >
                </ad-block>
            @endif
        @endif

        <div class="border-top-blue border-bottom">
            <div class="container nav-top">
                <div class="row justify-content-between sub-menu">

                    <div class="col-auto">
                        @if ($current_moto)

                            <ul class="nav justify-content-left">
                                <li class="nav-item nav-item-dop position-relative mr-2 mr-sm-4">
                                    <a class="nav-link nav-link-dop {{ Request::is('*/catalog*') && !Request::is('*/compare*') ? 'active' : '' }}"
                                       href="{{ route('catalog.index', $current_moto->alias) }}">Каталог</a>
                                </li>
                                <li class="nav-item nav-item-dop position-relative mr-2 mr-sm-4">
                                    @if (isset($current_brand) && $current_brand)
                                        <a class="nav-link nav-link-dop {{ Request::is('*/dealers*') ? 'active' : '' }}"
                                           href="{{ Helpers::set_route_city(route('dealers.brand', [$current_moto->alias, $current_brand->alias], false)) }}">Дилеры</a>
                                    @else
                                        <a class="nav-link nav-link-dop {{ Request::is('*/dealers*') ? 'active' : '' }}"
                                           href="{{ Helpers::set_route_city(route('dealers.index', $current_moto->alias, false)) }}">Дилеры</a>
                                    @endif
                                </li>
                                <li class="nav-item nav-item-dop position-relative mr-0 mr-sm-4">
                                    <a class="nav-link nav-link-dop {{ (Request::is('*/dealers*') || Request::is('*/catalog*')) ? '' : 'active' }}"
                                       {{--                                       href="{{ route('sale.index', $current_moto->alias, false) }}">Объявления</a>--}}
                                       href="{{ Helpers::set_route_city(route('parameter1', $current_moto->alias, false)) }}">Объявления</a>
                                </li>
                            </ul>

                        @else

                            <span style="padding: 0.6rem 0; display:block;">
                                Техника для активных людей
                            </span>

                        @endif
                    </div>

                    <div class="col-auto my-auto">
                        <select-city :cities="{{ $cities }}"></select-city>

                    </div>

                </div>
            </div>
        </div>
    </div>

    <div class="container">

        @yield('content')
        @yield('message')

    </div>

    {{--@include('components.modals.register')--}}
{{--    @include('components.modals.login')--}}
    @include('layouts.footer')
</div>

<!-- Scripts -->
<script src="{{ asset('js/app.js') }}" defer></script>
{{--</script>--}}
<script>
    var csrf_token = document.querySelector('meta[name="csrf-token"]').getAttribute('content')
</script>
@yield('script')
</body>
</html>
