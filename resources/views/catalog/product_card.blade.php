@if (isset($ads))
    <div class="row mb-3">
        <div class="col-md-12">

            {!! $ads->script !!}

        </div>
    </div>
@endif

@if (!$products->count())

    <p class="mt-5 text-center">
        Извините, в нашем каталоге {{ $current_moto->title_chego }}
        @if ($current_brand)
            {{ $current_brand->title }}
        @endif
        @if ($current_product)
            {{ $current_product->title }}
        @endif
        информация о моделях не представлена
    </p>
    <p class="mb-3 text-center">
        <a class="link" href="{{ route('catalog.index', [$current_moto->alias], false) }}">
            Смотреть каталог всех {{ $current_moto->title_chego }}
        </a>
    </p>

@else

    <div class="row">

        @foreach ($products as $key => $product)
            <div class="col-md-4 mb-4">
                <div class="card catalog-item h-100">
                    @if ($product->images->count() > 0)
                        <compare-add :product="{{ $product }}"></compare-add>
                        <img
                                @if($user_agent->isMobile())
                                data-src="{{ Helpers::get_image(Storage::url($product->images[0]->path), 600, 400) }}"
                                src="{{ Helpers::get_image(Storage::url($product->images[0]->path), 60, 40) }}"
                                class="card-img-top border-bottom b-lazy"
                                @else
                                src="{{ Helpers::get_image(Storage::url($product->images[0]->path), 600, 400) }}"
                                class="card-img-top border-bottom"
                                @endif
                                alt="{{ isset($current_class) ? $current_class->seo_title.' '.mb_strtolower($current_moto->title) : mb_strtolower($current_moto->title) }} {{ $product->brand->title }} {{ $product->title }}">
                    @endif
                    <div class="card-body p-3">
                        <p class="card-title mb-0">
                            <a class="stretched-link"
                               href="{{ route('catalog.product', [$current_moto->alias, $product->brand->alias, $product->alias]) }}">
                                <b>
                                    @if($product->brand->alias=='brp' && $current_moto->alias=='boat-motors')
                                        Evinrude
                                    @else
                                        {{ $product->brand->title }}
                                    @endif
                                    {{ $product->title }}
                                </b>
                            </a>
                        </p>

                        @if (Auth::check())
                            <class-edit
                                    :classes="{{ $all_classes }}"
                                    :product_id="{{ $product->id }}"
                                    :class_id="{{ json_encode ($product->class_id) }}"></class-edit>
                        @else
                            @if ($product->moto_class)
                                <p class="mb-0"
                                   style="opacity: .6; font-size: .8rem;">{{ $product->moto_class->title }}</p>
                            @endif
                        @endif

                        @foreach ($product->parameter_names as $parameter)

                            <p class="mb-0" style="font-size: .8rem;">{{ $parameter->short_title }}:
                                <b>{{ $parameter->pivot->value }}</b> {{ $parameter->unit }}</p>

                        @endforeach
                    </div>
                    <div class="card-footer text-muted text-right position-relative"
                         style="border-top: none; overflow:hidden;">
                        @if (Auth::check())
                            <price-edit
                                    :product_id="{{ $product->id }}"
                                    :price_catalog="{{ json_encode ($product->price_catalog) }}"></price-edit>
                        @endif
                        @if ($product->price_catalog)
                            <div class="float-xl-right price-catalog">
                                    <span
                                            style="font-size: 1rem;"><span
                                                class="mr-3 p-0">от</span><b>{{ number_format($product->price_catalog, 0, ',', ' ') }}</b>  ₽</span>
                            </div>
                        @else
                            <div class="float-xl-right price-catalog-none">
                                <span>Цена не определена</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            {{--    РСЯ --}}
            @if($user_agent->isMobile())
                @if ($products->count() > 11 && $key == 6)
                    <div class="col-md-4 mb-4">
                        <div class="card catalog-item h-100">
                            <ad-block
                                    div_id="{{ 'adblock-111' }}"
                                    script="{{ Helpers::get_ad_block('R-A-347397-17', 'adblock-111', 22) }}"
                            >
                            </ad-block>
                        </div>
                    </div>
                @endif
            @endif
        @endforeach

        {{--    РСЯ --}}
        @if (isset($ads_sidebar))

            @if($user_agent->isMobile())
                <div class="col-12 mb-4">
                    <div class="card catalog-item h-100">

                        <ad-block
                                div_id="{{ 'adblock-11' }}"
                                script="{{ Helpers::get_ad_block('R-A-347397-17', 'adblock-11', 3) }}"
                        >
                        </ad-block>

                    </div>
                </div>
            @else

                @if ($products->count()%3 == 0)
                    <div class="col-md-4 mb-4">
                        <div class="card catalog-item h-100">

                            <ad-block
                                    div_id="{{ 'adblock-1' }}"
                                    script="{{ Helpers::get_ad_block('R-A-347397-12', 'adblock-1', 1) }}"
                            >
                            </ad-block>

                        </div>
                    </div>

                    <div class="col-md-4 mb-4">
                        <div class="card catalog-item h-100">

                            <ad-block
                                    div_id="{{ 'adblock-2' }}"
                                    script="{{ Helpers::get_ad_block('R-A-347397-12', 'adblock-2', 2) }}"
                            >
                            </ad-block>

                        </div>
                    </div>

                    <div class="col-md-4 mb-4">
                        <div class="card catalog-item h-100">

                            <ad-block
                                    div_id="{{ 'adblock-3' }}"
                                    script="{{ Helpers::get_ad_block('R-A-347397-12', 'adblock-3', 3) }}"
                            >
                            </ad-block>

                        </div>
                    </div>
                @endif

                @if (($products->count()+2)%3 == 0)
                    <div class="col-md-4 mb-4">
                        <div class="card catalog-item h-100">

                            <ad-block
                                    div_id="{{ 'adblock-1' }}"
                                    script="{{ Helpers::get_ad_block('R-A-347397-12', 'adblock-1', 1) }}"
                            >
                            </ad-block>

                        </div>
                    </div>

                    <div class="col-md-4 mb-4">
                        <div class="card catalog-item h-100">

                            <ad-block
                                    div_id="{{ 'adblock-2' }}"
                                    script="{{ Helpers::get_ad_block('R-A-347397-12', 'adblock-2', 2) }}"
                            >
                            </ad-block>

                        </div>
                    </div>
                @endif

                @if (($products->count()+1)%3 == 0)
                    <div class="col-md-4 mb-4">
                        <div class="card catalog-item h-100">

                            <ad-block
                                    div_id="{{ 'adblock-1' }}"
                                    script="{{ Helpers::get_ad_block('R-A-347397-12', 'adblock-1', 1) }}"
                            >
                            </ad-block>

                        </div>
                    </div>
                @endif

            @endif

        @endif

    </div>

    {{--    @if($user_agent->isDesktop())--}}
    {{--        <div class="row mb-3">--}}
    {{--            <div class="col-12">--}}
    {{--                <ad-block--}}
    {{--                        div_id="{{ 'yandex_rtb_R-A-347397-13' }}"--}}
    {{--                        script="{{ Helpers::get_ad_block('R-A-347397-13', 'yandex_rtb_R-A-347397-13', null) }}"--}}
    {{--                >--}}
    {{--                </ad-block>--}}
    {{--            </div>--}}
    {{--        </div>--}}
    {{--    @endif--}}

    @if(!Auth::check() || !$current_brand)
        @if ($products->lastPage()>1)
            <div class="row">
                <div class="col-12 mt-3">
                    {{ $products->appends($parameters)->links() }}
                </div>
            </div>
        @endif
    @endif

@endif
