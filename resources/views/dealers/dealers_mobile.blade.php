@extends('layouts.app')

@section('content')

    <div class="row">

        <div class="col-md-12">

            <h1 class="font-weight-bold my-2 my-sm-3">
                Купить {{ $current_moto->title_single }}
                @if ($current_brand)
                    {{ $current_brand->title }}
                @endif
                @if ($current_city)
                    в {{ $current_city->where }}
                @endif
            </h1>

            <div class="mb-3">Список официальных дилеров и салонов {{ $current_moto->title_chego }}
                <b>
                    @if ($current_brand)
                        {{ $current_brand->title }}
                    @endif
                    @if ($current_city)
                        в {{ $current_city->where }}
                    @endif
                </b> - адреса, телефоны, вебсайты и другая полезная информация в нашем электронном
                <a class="link"
                   href="{{ route('catalog.index', $current_moto->alias) }}">каталоге {{ $current_moto->title_chego }}</a>
            </div>

            @if ($brands)

                <div class="input-group input-group-filter mb-3">
                    <select class="custom-select" id="inputGroupSelect04" onchange="top.location=this.value">

                        @if (!$current_brand)
                            <option value="" selected disabled>Марка</option>
                        @endif

                        @foreach ($brands as $brand)
                            @if ($current_brand)
                                @if ($current_brand->title == $brand->title)
                                    <option value="{{ Helpers::set_route_city(route('dealers.brand', [$current_moto->alias, $brand->alias], false)) }}"
                                            selected>
                                        {{ $brand->title }}
                                    </option>
                                @else
                                    <option value="{{ Helpers::set_route_city(route('dealers.brand', [$current_moto->alias, $brand->alias], false)) }}">
                                        {{ $brand->title }}
                                    </option>
                                @endif
                            @else
                                <option value="{{ Helpers::set_route_city(route('dealers.brand', [$current_moto->alias, $brand->alias], false)) }}">
                                    {{ $brand->title }}
                                </option>
                            @endif
                        @endforeach

                    </select>
                    <div class="input-group-append">
                        <button class="btn btn-outline-primary"
                                type="button"
                                onclick="top.location = '{{ Helpers::set_route_city(route('dealers.index', [$current_moto->alias], false)) }}'">
                            Все марки
                        </button>
                    </div>
                </div>
                <p>
                    @if (!$current_brand)
                        <a class="link" href="{{ route('catalog.index', $current_moto->alias) }}">Смотреть все <span class="text-lowercase"><strong>{{ $current_moto->title }}</strong></span></a>
                    @else
                        <a class="link" href="{{ route('catalog.brands', [$current_moto->alias, $current_brand->alias]) }}">
                            Смотреть все <strong><span class="text-lowercase">{{ $current_moto->title }}</span> {{ $current_brand->title }}</strong>
                        </a>
                    @endif

                </p>
            @endif

        </div>

    </div>

    {{--                            {{dd($dealers)}}--}}

    <div class="row">

        @if ($dealers->dealers->count() > 0)

            <div class="dealers-wrapper w-100">
                @foreach ($dealers->dealers as $index => $dealer)
                    {{--                            {{dd($placemarks)}}--}}
                    <div class="dealer p-3 border-top position-relative">
                        <h5><a class="stretched-link"
                               href="/dealers/{{ $dealer->alias }}"><b>{{ $dealer->title }}</b>
                            </a>
                        </h5>
                        <b>{{ $dealer->city->title }}</b>
                        <p class="my-2">{{ $dealer->address }}</p>
                        {{ $dealer->phone }}
                    </div>

                    {{--    РСЯ --}}
                    @if ($index == 2)
                        <div class="border-top position-relative">
                            <ad-block
                                    div_id="{{ 'yandex_rtb_R-A-347397-18' }}"
                                    script="{{ Helpers::get_ad_block('R-A-347397-18', 'yandex_rtb_R-A-347397-18', 21) }}"
                            >
                            </ad-block>
                        </div>
                    @endif
                @endforeach
                    {{--    РСЯ --}}
                @if ($index < 1 || $dealers->dealers->count() > 9)
                    <div class="border-top position-relative">
                        <ad-block
                                div_id="{{ 'yandex_rtb_R-A-347397-171' }}"
                                script="{{ Helpers::get_ad_block('R-A-347397-17', 'yandex_rtb_R-A-347397-171', 5) }}"
                        >
                        </ad-block>
{{--                        <ad-block--}}
{{--                                div_id="{{ 'yandex_rtb_R-A-347397-181' }}"--}}
{{--                                script="{{ Helpers::get_ad_block('R-A-347397-18', 'yandex_rtb_R-A-347397-181', 31) }}"--}}
{{--                        >--}}
{{--                        </ad-block>--}}
                    </div>
                @endif
            </div>

        @else

            <div class="px-3 py-4 border-top position-relative text-center">
                <p>По данным условиям поиска дилеры не найдены</p>
                <p>Попробуйте изменить критерии или <a class="link" href="{{ route('dealers.index', [$current_moto->alias], false) }}">смотрите всех
                        дилеров {{ $current_moto->title_chego }}</a></p>
            </div>
            {{--    РСЯ --}}
            <div class="border-top border-bottom position-relative">
                <ad-block
                        div_id="{{ 'yandex_rtb_R-A-347397-18' }}"
                        script="{{ Helpers::get_ad_block('R-A-347397-18', 'yandex_rtb_R-A-347397-18', 1) }}"
                >
                </ad-block>
            </div>

        @endif

    </div>

@endsection
