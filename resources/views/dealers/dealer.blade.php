@extends('layouts.app')

@section('content')
    <div class="content">

        <div class="dealer-wrap">

            <div class="row">

                <div class="col-xl-9">
                    {{--                                                                                {{dd($current_dealer)}}--}}
                    <h1 class="my-3 font-weight-bold">
                        {{ $current_dealer->title }}
                    </h1>
                    {{--                    {{dd($current_motos)}}--}}

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="m-0">Официальный дилер в {{ $current_dealer->city->where }}</h5>
                                </div>
                                <ul class="list-group list-group-flush">
                                    @foreach ($motos_brands as $brands)
                                        @foreach ($brands->brands as $brand)
                                            <li class="list-group-item moto-brand-item">
                                                <a class="link"
                                                   href="{{ route('catalog.brands', [$brands->alias, $brand->alias]) }}">{{ $brands->title }} {{ $brand->title_ru }}
                                                </a>
                                            </li>
                                        @endforeach
                                    @endforeach
                                    @if (Auth::check())
                                        <li class="list-group-item moto-brand-item">
                                            Добавить тип и бренд
                                            <dealer-add-moto-brand
                                                    dealer_id="{{ $current_dealer->id }}"
                                                    :new_motos="{{ $new_motos }}"
                                            ></dealer-add-moto-brand>
                                        </li>
                                    @endif
                                </ul>
                                <div class="card-body">
                                    @if ($current_dealer->address)
                                        <p class="dealer-address">{{$current_dealer->address}}</p>
                                    @endif
                                    @if ($current_dealer->phone)
                                        <p class="dealer-phone">{{$current_dealer->phone}}</p>
                                    @endif
                                    @if ($current_dealer->site)
                                        <p class="dealer-site mb-0"><a class="link" target="_blank" rel="nofollow"
                                                                       href="{{$current_dealer->site}}">{{$current_dealer->site}}</a>
                                        </p>
                                    @endif
                                </div>
                            </div>

                            <div class="mt-4">
                                <h4 class="mb-3"><b>Смотрите также</b></h4>

                                @foreach ($current_motos as $index => $moto)
                                    <div class="breadcrumb">
                                        <p class="py-2 px-3 mb-0">
                            <span class="d-inline-block">
                            {{--                            @if ($current_brand)--}}
                            <a href="{{ route('dealers.index', $moto->alias) }}"><b>Все дилеры {{ $moto->title_chego }}</b></a>
                            <i class="fal fa-angle-right mx-1"></i>
                            </span>
                                            {{--                            @endif--}}
                                            <span class="d-inline-block">
                            <a data-toggle="collapse" href="#brands{{$index}}" role="button" aria-expanded="false"
                               aria-controls="brands{{$index}}">
                                Выберите марку <i class="fal fa-angle-down mx-1"></i>
                            </a>
                            </span>
                                        </p>
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="collapse multi-collapse border-top" id="brands{{$index}}">
                                                    <div class="p-3 pb-0">
                                                        <div class="brands-list">
                                                            @foreach ($moto->brands as $brand)

                                                                <a class="d-block mb-3" style="line-height: 1;"
                                                                   href="{{ route('dealers.brand', [$moto->alias, $brand->alias]) }}">{{ $brand->title }}</a>

                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                @endforeach
                            </div>

                        </div>
                        <div class="col-md-6">
                            <div style="height: 350px;">
                                <dealer-map
                                        :dealer="{{ $current_dealer }}"
                                ></dealer-map>
                            </div>
                            @if (Auth::check())
                                <dealer-edit
                                        :dealer="{{ $current_dealer }}">
                                </dealer-edit>
                            @endif
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-12">
                            <ad-block
                                    div_id="{{ 'yandex_rtb_R-A-347397-13' }}"
                                    script="{{ Helpers::get_ad_block('R-A-347397-13', 'yandex_rtb_R-A-347397-13', null) }}"
                            >
                            </ad-block>
                        </div>
                    </div>

                </div>

                {{--    РСЯ --}}
                <div class="col-xl-3">
                    <div class="my-3 sticky_column">

                        @if($user_agent->isMobile())

                            <ad-block
                                    margin="{{ '15' }}"
                                    div_id="{{ 'adblock-11' }}"
                                    script="{{ Helpers::get_ad_block('R-A-347397-14', 'adblock-11', 2) }}"
                            >
                            </ad-block>

                        @else

                            <ad-block
                                    margin="{{ '15' }}"
                                    div_id="{{ 'adblock-11' }}"
                                    script="{{ Helpers::get_ad_block($ads_sidebar->block_id, 'adblock-11', 20) }}"
                            >
                            </ad-block>

                            <ad-block
                                    div_id="{{ 'adblock-12' }}"
                                    script="{{ Helpers::get_ad_block($ads_sidebar->block_id, 'adblock-12', 21) }}"
                            >
                            </ad-block>

                        @endif

                    </div>
                </div>

            </div>

        </div>

    </div>
@endsection
