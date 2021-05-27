@extends('layouts.app')

@section('content')
</div>
<div class="container-fluid p-0" style="margin-bottom: -3rem;">
    <div class="row no-gutters" style="height: calc(100vh - 84px);">
        <div class="col-md-4 h-100 mh-100 px-3 pb-3" style="overflow-y: auto;">

            <div class="row">

                <div class="col-md-12">

                    <h1 class="font-weight-bold my-2 my-sm-3">
                        Купить {{ $current_moto->title_single }}
                        @if ($current_brand)
                            {{-- для снегоходов и квадроциклов BRP добавляем модель--}}
                            @if ($current_brand->alias == 'brp' && $current_moto->alias == 'atv')
                                {{ $current_brand->title }} Can-Am
                            @elseif ($current_brand->alias == 'brp' && $current_moto->alias == 'snowmobiles')
                                {{ $current_brand->title }} Ski-Doo, Lynxs
                            @elseif ($current_brand->alias == 'brp' && $current_moto->alias == 'boat-motors')
                                {{ $current_brand->title }} Evinrude
                            @else
                                {{ $current_brand->title }}
                            @endif
                        @endif
                        @if ($current_city)
                            в {{ $current_city->where }}
                        @endif
                    </h1>
                    @if (Auth::check())
                        <a style="margin-top: -15px; margin-bottom: 10px; display:block;"
                           href="{{ route('dealer.add') }}">
                            Добавить дилера
                        </a>
                    @endif

                    <div class="mb-3">Список официальных дилеров и салонов {{ $current_moto->title_chego }}
                        <b>
                            @if ($current_brand)
                                {{-- для снегоходов и квадроциклов BRP добавляем модель--}}
                                @if ($current_brand->alias == 'brp' && $current_moto->alias == 'atv')
                                    {{ $current_brand->title }} Can-Am
                                @elseif ($current_brand->alias == 'brp' && $current_moto->alias == 'snowmobiles')
                                    {{ $current_brand->title }} Ski-Doo, Lynxs
                                @elseif ($current_brand->alias == 'brp' && $current_moto->alias == 'boat-motors')
                                    {{ $current_brand->title }} Evinrude
                                @else
                                    {{ $current_brand->title }}
                                @endif
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
                                    <option value="" selected disabled>Выберите марку</option>
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
                                    все марки
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

                    {{--<example-component image-src=""></example-component>--}}

                    {{--                {{dd($current_brand)}}--}}


                </div>

            </div>

            {{--                            {{dd($dealers)}}--}}

            <div class="row">

                @if ($dealers->dealers)

                    <dealers-list
                            :current_moto="{{ $current_moto }}"
                            :dealers="{{ $dealers->dealers->toJson() }}">
                    </dealers-list>

                    {{--                    <div class="dealers-wrapper w-100">--}}
                    {{--                        @foreach ($dealers->dealers as $dealer)--}}
                    {{--                            --}}{{--                            {{dd($placemarks)}}--}}
                    {{--                            <div class="dealer p-3 border-top position-relative">--}}
                    {{--                                <h5><a class="stretched-link"--}}
                    {{--                                       href="/dealers/{{ $dealer->alias }}"><b>{{ $dealer->title }}</b>--}}
                    {{--                                    </a>--}}
                    {{--                                </h5>--}}
                    {{--                                {{ $dealer->city->title }}<br>--}}
                    {{--                                {{ $dealer->address }}<br>--}}
                    {{--                                {{ $dealer->phone }}--}}
                    {{--                            </div>--}}
                    {{--                        @endforeach--}}
                    {{--                    </div>--}}

                @endif

            </div>

        </div>
        <div class="col-md-8" style="height: 100%;">
            {{--                                    {{ dd($dealers) }}--}}

            <dealers-map
                    :dealers="{{ $dealers->dealers->toJson() }}"
                    :placemarks="{{ $placemarks->toJson() }}">
            </dealers-map>

            {{--            <test-map--}}
            {{--                    :dealers="{{ $dealers->dealers }}"--}}
            {{--                    :placemarks="{{ $placemarks }}">--}}
            {{--            </test-map>--}}

        </div>
    </div>
</div>

<div class="container">
@endsection
