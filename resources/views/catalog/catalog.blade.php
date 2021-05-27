@extends('layouts.app')

@section('content')

    <div class="content">

        <div class="catalog-wrap">

            <div class="row">

                <div class="col-xl-9">

                    <h1 class="font-weight-bold my-2 my-sm-3">
                        {{ $current_moto->title }}
                        @if ($current_brand)
                            {{--                            для снегоходов, лодочных моторов и квадроциклов BRP добавляем модель--}}
                            @if ($current_brand->alias == 'brp' && $current_moto->alias == 'atv')
                                {{ $current_brand->title }} Can-Am
                            @elseif ($current_brand->alias == 'brp' && $current_moto->alias == 'snowmobiles')
                                {{ $current_brand->title }} Ski-Doo, Lynxs
                            @elseif ($current_brand->alias == 'brp' && $current_moto->alias == 'boat-motors')
                                Evinrude
                            @else
                                {{ $current_brand->title }}
                            @endif
                        @endif
                    </h1>
                    @if (Auth::check())
                        <a style="margin-top: -15px; margin-bottom: 10px; display:block;" href="{{ route('catalog.product.add', array(
                            'moto_alias' => $current_moto->alias,
                            )) }}">
                            Добавить {{ $current_moto->title_single }}
                        </a>
                    @endif

                    <div id="breadcrumb" class="breadcrumb breadcrumb-product" data-sticky-class="is-sticky">
                        <p class="breadcrumb-title py-2 px-3 mb-0">
                            @if ($current_brand)
                                <span class="d-inline-block">
                                <a href="{{ route('catalog.index', $current_moto->alias) }}"><b>{{ $current_moto->title }}</b></a>
                                <i class="fal fa-angle-right mx-1"></i>
                                </span>
                            @endif
                            <span class="d-inline-block">
                            <a @if(!$user_agent->isMobile())
                               class="brands-hover"
                               href="#"
                               @else
                               data-toggle="collapse" href="#brands"
                               @endif
                               role="button"
                               aria-expanded="false"
                               aria-controls="brands">
                                @if ($current_brand)
                                    <b>{{ $current_brand->title }}</b> <i class="fal fa-angle-right mx-1"></i>
                                @else
                                    Выбрать марку <i class="fal fa-angle-down mx-1"></i>
                                @endif
                            </a>
                            </span>
                            @if ($current_brand)
                                <span class="d-inline-block">
                                <a @if(!$user_agent->isMobile())
                                   class="models-hover"
                                   href="#"
                                   @else
                                   data-toggle="collapse" href="#models"
                                   @endif
                                   role="button"
                                   aria-expanded="false"
                                   aria-controls="models">
{{--                                        data-toggle="collapse" href="#models" role="button" aria-expanded="false"--}}
                                    {{--                                   aria-controls="models">--}}
                                    @if ($current_product)
                                        <b>{{ $current_product->title }}</b> <i class="fal fa-angle-down mx-1"></i>
                                    @else
                                        Выбрать модель <i class="fal fa-angle-down mx-1"></i>
                                    @endif
                                </a>
                                </span>
                            @endif
                        </p>
                        <div class="row">
                            <div class="col-12">
                                <div class="collapse multi-collapse border-top" id="brands">
                                    <div class="p-3 pb-0">
                                        <div class="brands-list">
                                            @foreach ($brands as $brand)

                                                @if ($brand->alias == 'brp' && $current_moto->alias == 'atv')
                                                    <a class="d-block mb-3" style="line-height: 1;"
                                                       href="{{ route('catalog.brands', [$current_moto->alias, $brand->alias]) }}">{{ $brand->title }}
                                                        Can-Am</a>
                                                @elseif ($brand->alias == 'brp' && $current_moto->alias == 'snowmobiles')
                                                    <a class="d-block mb-3" style="line-height: 1;"
                                                       href="{{ route('catalog.brands', [$current_moto->alias, $brand->alias]) }}">{{ $brand->title }}
                                                        Ski-Doo, Lynxs</a>
                                                @elseif ($brand->alias == 'brp' && $current_moto->alias == 'boat-motors')
                                                    <a class="d-block mb-3" style="line-height: 1;"
                                                       href="{{ route('catalog.brands', [$current_moto->alias, $brand->alias]) }}">
                                                        Evinrude</a>
                                                @else
                                                    <a class="d-block mb-3" style="line-height: 1;"
                                                       href="{{ route('catalog.brands', [$current_moto->alias, $brand->alias]) }}">{{ $brand->title }}</a>
                                                @endif

                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @if ($current_brand)
                                {{--                                {{dd($current_brand)}}--}}
                                <div class="col-12">
                                    <div class="collapse multi-collapse border-top" id="models">
                                        <div class="p-3">
                                            @if ($all_products)
                                                <div class="mb-3"><b>{{ $current_brand->title }}</b></div>

                                                @if ($all_products->count() > 0)

                                                    <div class="product-list">

                                                        @foreach ($all_products as $product)

                                                            <a class="d-block mb-3" style="line-height: 1;"
                                                               href="{{ route('catalog.product', [$current_moto->alias, $current_brand->alias, $product->alias]) }}">{{ $product->title }}</a>

                                                        @endforeach

                                                    </div>

                                                @else

                                                    <div>Модели в каталоге не найдены</div>

                                                @endif

                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>


                    {{--    РСЯ --}}
                    {{--                    @if($user_agent->isDesktop())--}}
                    {{--                        <div class="row">--}}
                    {{--                            <div class="col-md-12">--}}

                    {{--                                <ad-block--}}
                    {{--                                        margin="{{ '15' }}"--}}
                    {{--                                        div_id="{{ 'yandex_rtb_R-A-347397-14' }}"--}}
                    {{--                                        script="{{ Helpers::get_ad_block('R-A-347397-14', 'yandex_rtb_R-A-347397-14', 1) }}"--}}
                    {{--                                >--}}
                    {{--                                </ad-block>--}}

                    {{--                            </div>--}}
                    {{--                        </div>--}}
                    {{--                    @endif--}}

                    <div class="row">
                        <div class="col-md-6">
                            <div class="input-group input-group-filter mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Сортировать по:</span>
                                </div>

                                {{--                                {{dd($parameters['sort'])}}--}}
                                <select class="custom-select filter-select"
                                        onchange="top.location='/{{ Request::path() }}?sort='+this.value">
                                    <option
                                            @if (count($parameters) && $parameters['sort']=='view_count')
                                            selected
                                            @endif
                                            value="view_count">
                                        Популярности
                                    </option>
                                    <option
                                            @if (count($parameters) && $parameters['sort']=='price-asc')
                                            selected
                                            @endif
                                            value="price-asc">
                                        Цене (меньше - больше)
                                    </option>
                                    <option
                                            @if (count($parameters) && $parameters['sort']=='price-desc')
                                            selected
                                            @endif
                                            value="price-desc">
                                        Цене (больше - меньше)
                                    </option>
                                    <option
                                            @if (count($parameters) && $parameters['sort']=='v-asc')
                                            selected
                                            @endif
                                            value="v-asc">
                                        Объему (меньше - больше)
                                    </option>
                                    <option
                                            @if (count($parameters) && $parameters['sort']=='v-desc')
                                            selected
                                            @endif
                                            value="v-desc">
                                        Объему (больше - меньше)
                                    </option>
                                </select>
                            </div>
                        </div>
                        @if (count($all_classes_arr) > 0)
                            <div class="col-md-6">
                                <div class="input-group input-group-filter mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Класс:</span>
                                    </div>
                                    {{Form::select(
                                        'class',
                                        $all_classes_arr,
                                        isset($parameters['class']) ? $parameters['class'] : 'all',
                                        array('class' => 'custom-select filter-select', 'onchange' => 'top.location="/'.$current_moto->alias.'/catalog/"+this.value')
                                    )}}
                                </div>
                            </div>
                        @endif

                    </div>

                    @include('catalog.product_card')

                    @if(isset($all_classes_random) && $all_classes_random->count())
                        <h4 class="mt-4 mb-3"><b>Классы {{ $current_moto->title_chego }}</b></h4>
                        <div class="row">
                            @foreach ($all_classes_random as $class)
{{--                                @if ($current_moto->alias == 'motorcycles' && $loop->iteration == 9)--}}
{{--                                    <div class="col-12 mb-3">--}}
{{--                                        <ad-block--}}
{{--                                                div_id="{{ 'yandex_rtb_R-A-347397-14' }}"--}}
{{--                                                script="{{ Helpers::get_ad_block('R-A-347397-14', 'yandex_rtb_R-A-347397-14', 111) }}"--}}
{{--                                        >--}}
{{--                                        </ad-block>--}}
{{--                                    </div>--}}
{{--                                @endif--}}
                                <div class="col-md-3 col-sm-6 col-6 mb-3">
                                    <div class="card class-item h-100">
                                        <img
                                                @if($user_agent->isMobile())
                                                data-src="{{ Helpers::get_image(Storage::url($class['image']), 600, 400) }}"
                                                class="card-img-top border-bottom b-lazy"
                                                src="{{ Helpers::get_image(Storage::url($class['image']), 60, 40) }}"
                                                @else
                                                src="{{ Helpers::get_image(Storage::url($class['image']), 600, 400) }}"
                                                class="card-img-top border-bottom"
                                                @endif
                                                alt="{{ $class['class_name'] }}">
                                        <div class="card-body px-3 py-2 d-flex">
                                            <p class="card-title mb-0 text-center align-self-center">
                                                <a class="stretched-link"
                                                   href="{{ route('catalog.brands', [$current_moto->alias, $class['class_alias']]) }}">
                                                    <b>{{ $class['class_name'] }}</b>
                                                </a>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    <div class="row mb-3">
                        <div class="col-12">
                            @if($user_agent->isMobile())
                                <ad-block
                                        div_id="{{ 'yandex_rtb_R-A-347397-26' }}"
                                        script="{{ Helpers::get_ad_block('R-A-347397-26', 'yandex_rtb_R-A-347397-26', null) }}"
                                >
                                </ad-block>
                            @else
                                <ad-block
                                        div_id="{{ 'yandex_rtb_R-A-347397-13' }}"
                                        script="{{ Helpers::get_ad_block('R-A-347397-13', 'yandex_rtb_R-A-347397-13', null) }}"
                                >
                                </ad-block>
                            @endif
                        </div>
                    </div>

                </div>

                <div class="col-xl-3">

                    @include('catalog.sidebar')

                </div>

            </div>

        </div>

    </div>

@endsection

