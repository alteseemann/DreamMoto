<div class="my-4 sticky_column">

    {{--    РСЯ --}}
    @if (isset($ads_sidebar))
        @if($user_agent->isMobile())

            @if (!$current_brand && !isset($current_class))
                <ad-block
                        margin="{{ '15' }}"
                        div_id="{{ 'adblock-11' }}"
                        script="{{ Helpers::get_ad_block('R-A-347397-26', 'adblock-11', 2) }}"
                >
                </ad-block>
            @endif

        @else

            <ad-block
                    margin="{{ '15' }}"
                    div_id="{{ 'adblock-11' }}"
                    script="{{ Helpers::get_ad_block($ads_sidebar->block_id, 'adblock-11', null) }}"
            >
            </ad-block>

        @endif
    @endif

    @if (isset($current_brand) && !isset($current_class))
        <div class="card">
            <div class="card-body moto-description">
                @if ($current_brand->motos[0]->pivot->catalog_description)
                    {!! $current_brand->motos[0]->pivot->catalog_description !!}
                @endif
                <h5 class="card-title @if ($current_brand->motos[0]->pivot->catalog_description) mt-3 @endif">
                    <strong>
                        <a class="text-link"
                               href="{{ Helpers::set_route_city(route('dealers.brand', [$current_moto->alias, $current_brand->alias], false)) }}">Где
                            купить {{ $current_moto->title_single }} {{ $current_brand->title_ru }}
                            @if ($current_city)
                                в {{ $current_city->where }}
                            @endif
                        </a>
                    </strong>
                </h5>
                <p>
                    Здесь представлена большая часть
                    новых {{ $current_moto->title_chego }} {{ $current_brand->title_ru }}, которые можно купить у
                    <a class="text-link"
                       href="{{ Helpers::set_route_city(route('dealers.brand', [$current_moto->alias, $current_brand->alias], false))  }}">официальных
                        дилеров {{ $current_brand->title }}
                        @if ($current_city)
                            в {{ $current_city->where }}
                        @endif
                    </a>
                    на
                    сегодняшний день.
                </p>
            </div>
        </div>
    @endif

    @if (!$current_brand && !$current_product && !isset($current_class))
        <div class="card">
            <div class="card-body moto-description">
                <h5 class="card-title"><strong><a class="text-link"
                                                  href="{{ Helpers::set_route_city(route('dealers.index', $current_moto->alias, false)) }}">Где
                            купить {{ $current_moto->title_single }}</a></strong></h5>
                <p>
                    Выбрать и купить новый <strong>{{ $current_moto->title_single }}</strong> Вам поможет наш
                    электронный каталог. Каждая
                    модель имеет
                    актуальную цену официального дилера, описание, технические характеристики, фотографии, видео.
                </p>
                <p>
                    Все {{ mb_strtolower($current_moto->title) }} сгруппированы по производителям и классам.
                    Дополнительно на нашем сайте есть подборки
                    новых моделей различных классов.
                </p>
                <p>
                    Для <strong>покупки {{ $current_moto->title_chego }}</strong> обратитесь к
                    <a class="text-link"
                       href="{{ Helpers::set_route_city(route('dealers.index', $current_moto->alias, false)) }}">официальным
                        дилерам {{ $current_moto->title_chego }}</a>, ссылки и информацию о которых Вы найдете на нашем
                    сайте.
                </p>
            </div>
        </div>
    @endif

    @if (isset($current_class) && !$current_brand)
        <div class="card">
            <div class="card-body moto-description">
                {!! $current_class->description !!}
                <h5 class="card-title @if ($current_class->description) mt-3 @endif">
                    <strong><a class="text-link"
                               href="{{ Helpers::set_route_city(route('dealers.index', $current_moto->alias, false)) }}">Где
                            купить {{ $current_class->title_single }} {{ $current_moto->title_single }}</a></strong>
                </h5>
                <p>
                    Здесь представлена большая часть
                    новых {{ $current_class->title_kakih }} {{ $current_moto->title_chego }}, которые можно купить у
                    <a class="text-link"
                       href="{{ Helpers::set_route_city(route('dealers.index', $current_moto->alias, false))  }}">официальных
                        дилеров</a>
                    на
                    сегодняшний день.
                </p>
            </div>
        </div>
    @endif

    {{--    @if($user_agent->isDesktop())--}}

    {{--        @if (isset($all_classes))--}}
    {{--            @if ($all_classes->count() > 0)--}}
    {{--                <ul class="p-0 m-0">--}}
    {{--                    @foreach ($all_classes as $class)--}}

    {{--                        <li class="d-inline-block mb-1 w-100">--}}
    {{--                            <a class="btn btn-outline-primary d-block"--}}
    {{--                               href="{{ route('catalog.brands', [$current_moto->alias, $class->alias]) }}"--}}
    {{--                               role="button">{{ $class->seo_title }}</a>--}}
    {{--                        </li>--}}

    {{--                    @endforeach--}}
    {{--                </ul>--}}
    {{--            @endif--}}
    {{--        @endif--}}

    {{--    @endif--}}

</div>
