@extends('layouts.app')

@section('content')

    <div class="content">

        <div class="product-wrap">

            <div class="row">

                <div class="col-xl-9">

                    <h1 class="font-weight-bold  my-2 my-sm-3">
                        {{ $current_brand->title }} {{ $current_product->title }}
                    </h1>
                    @if (Auth::check())
                        <a style="margin-top: -15px; margin-bottom: 10px; display:block;" href="{{ route('catalog.product.edit', array(
                            'moto_alias' => $current_moto->alias,
                            'brand_alias' => $current_brand->alias,
                            'product_alias' => $current_product->alias
                            )) }}">
                            Редактировать
                        </a>
                    @endif

                    {{--                        <nav aria-label="breadcrumb">--}}
                    {{--                            <ol class="breadcrumb">--}}
                    {{--                                <li class="breadcrumb-item"><a href="#">Каталог {{ $current_moto->title_chego }}</a></li>--}}
                    {{--                                <li class="breadcrumb-item active" aria-current="page">Library</li>--}}
                    {{--                            </ol>--}}
                    {{--                        </nav>--}}
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
                               href="{{ route('catalog.brands', [$current_moto->alias, $current_brand->alias]) }}"
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

                                                <a class="d-block mb-3" style="line-height: 1;"
                                                   href="{{ route('catalog.brands', [$current_moto->alias, $brand->alias]) }}">{{ $brand->title }}</a>

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

                    @if ($current_product->images->count() > 0)

                        <div class="row">
                            <div class="col-12">
                                @if($user_agent->isMobile())
                                    <div class="price-mobile-block">
                                        <a href="{{ Helpers::set_route_city(route('dealers.brand', [$current_moto->alias, $current_brand->alias], false)) }}"
                                           class="where-buy-mobile mr-3">Где купить</a>
                                        <div class="price-mobile">
                                            @if ($current_product->price_catalog)
                                                <span class="mr-4">от</span>
                                                <b>{{ number_format($current_product->price_catalog, 0, ',', ' ') }}</b>
                                                ₽
                                            @else
                                                Цена не установлена
                                            @endif
                                        </div>
                                    </div>
                                @else
                                    <div class="price">
                                        @if ($current_product->price_catalog)
                                            <span class="mr-3">от</span>
                                            <b>{{ number_format($current_product->price_catalog, 0, ',', ' ') }}</b> ₽
                                        @else
                                            Цена не установлена
                                        @endif
                                    </div>
                                    <a href="{{ Helpers::set_route_city(route('dealers.brand', [$current_moto->alias, $current_brand->alias], false)) }}"
                                       class="where-buy">Где купить</a>
                                @endif

                                <moto-slider
                                        is_robot="{{ $user_agent->isRobot() ? '1' : '0' }}"
                                        current_hour="{{ intval(date('H')) }}"
                                        is_mobile="{{ $user_agent->isMobile() ? '1' : '0' }}"
                                        :images="{{ $product_images }}"></moto-slider>
                                {{--                                <img class="img-fluid" src="{{ Storage::url($current_product->images[0]->path) }}" alt="">--}}
                            </div>
                        </div>

                    @endif

                    {{--    РСЯ --}}
                    {{--                    @if($user_agent->isDesktop())--}}
                    <div class="row">
                        <div class="col-md-12">

                            <ad-block
                                    margin="{{ '15' }}"
                                    div_id="{{ 'yandex_rtb_R-A-347397-14' }}"
                                    script="{{ Helpers::get_ad_block('R-A-347397-14', 'yandex_rtb_R-A-347397-14', 1) }}"
                            >
                            </ad-block>

                        </div>
                    </div>
                    {{--                    @endif--}}

                    {{--                    @if($user_agent->isMobile())--}}
                    {{--                        <div class="row mb-3">--}}
                    {{--                            <div class="col-12">--}}
                    {{--                                <ad-block--}}
                    {{--                                        div_id="{{ 'yandex_rtb_R-A-347397-17-500' }}"--}}
                    {{--                                        script="{{ Helpers::get_ad_block('R-A-347397-17', 'yandex_rtb_R-A-347397-17-500', 500) }}"--}}
                    {{--                                >--}}
                    {{--                                </ad-block>--}}
                    {{--                            </div>--}}
                    {{--                        </div>--}}
                    {{--                    @endif--}}

                    <div class="row mb-3">
                        <div class="col-12">
                            {!! $current_product->description !!}
                        </div>
                    </div>

                    <div class="row">
                        @if ($current_product->moto_class)
                            <div class="col-md-6">
                                <a class="btn btn-block btn-primary mb-3 text-uppercase"
                                   href="{{route('catalog.brands', [$current_moto->alias, $current_product->moto_class->alias])}}"
                                   role="button">Смотреть
                                    все <b>{{ $current_product->moto_class->seo_title }} {{ $current_moto->title }}</b></a>
                            </div>
                        @endif

                        @if (count($all_classes_arr) > 1)
                            <div class="col-md-6">
                                <div class="input-group input-group-filter mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Другие классы:</span>
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

                    <div class="row">
                        <div class="col-12">
                            <div class="d-flex">
                                <div class="w-50">
                                    <h4 class="mb-4">Характеристики</h4>
                                </div>
                                <div class="w-50 d-flex justify-content-end">
                                    <button class="btn btn-block btn-primary mb-3 text-uppercase w-50" onclick="$('#exampleModalScrollable').modal()"><b>Сравнить</b></button>
                                </div>
                            </div>


                            <div class="tth-columns">
                                @foreach ($parameters as $key => $group)
                                    <div class="tth-block mb-4">
                                        <table class="tth-table">
                                            <thead>
                                            <tr>
                                                <th colspan="2">{{ $group->title }}</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach ($group->parameter_names as $parameter)
                                                <tr>
                                                    <th class="tth-title">
                                                        {{ $parameter->title }}
                                                    </th>
                                                    <td>
                                                        @if ($parameter->val)
                                                            {{ $parameter->val }} {{ $parameter->unit }}
                                                        @else
                                                            <span style="opacity: .5">N/A</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    @if ($key == 0)
                                        @if($user_agent->isMobile())
                                            <ad-block
                                                    margin="{{ '20' }}"
                                                    div_id="{{ 'adblock-111' }}"
                                                    script="{{ Helpers::get_ad_block('R-A-347397-17', 'adblock-111', 111) }}"
                                            >
                                            </ad-block>
                                        @endif
                                    @endif
                                @endforeach
                            </div>

                        </div>
                    </div>

                    @if($same_products && $same_products->count())
                        <div class="row mt-3">
                            <div class="col-12 shadow pt-3">
                                <h4 class="font-weight-bold mb-4">{{ $current_moto->title }} с похожими
                                    характеристиками</h4>

                                <same-moto-slider
                                        is_mobile="{{ $user_agent->isMobile() ? '1' : '0' }}"
                                        :same_products="{{ $same_products }}"></same-moto-slider>
                            </div>
                        </div>
                    @endif

                    {{--    РСЯ --}}
                    @if($user_agent->isDesktop())
                        <div class="row mt-3">
                            <div class="col-md-12">

                                <ad-block
                                        margin="{{ '20' }}"
                                        div_id="{{ 'yandex_rtb_R-A-347397-20' }}"
                                        script="{{ Helpers::get_ad_block('R-A-347397-20', 'yandex_rtb_R-A-347397-20', 1) }}"
                                >
                                </ad-block>

                            </div>
                        </div>
                    @endif


                </div>

                <div class="col-xl-3">

                    @include('catalog.product_sidebar')

                </div>

            </div>

        </div>

    </div>
<!--Всплывающее окно сравнения-->
    <div class="modal" id="exampleModalScrollable" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalCenteredLabel">Сравнение</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    ...
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal">Продолжить</button>
                </div>
            </div>
        </div>
    </div>
@endsection

