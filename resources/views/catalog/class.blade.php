@extends('layouts.app')

@section('content')

    <div class="content">

        <div class="catalog-wrap">

            <div class="row">

                <div class="col-xl-9">
                    {{--{{dd($current_brand)}}--}}
                    <h1 class="my-3 font-weight-bold">
                        {{ $title }}
                        @if ($current_brand)
                            {{ $current_brand->title }}
                        @endif
                    </h1>

                    <div id="breadcrumb" class="breadcrumb" data-sticky-class="is-sticky">
                        <p class="breadcrumb-title py-2 px-3 mb-0">
                            <span class="d-inline-block">
                            {{--                            @if ($current_brand)--}}
                            <a href="{{ route('catalog.index', $current_moto->alias) }}"><b>Все <span
                                            style="text-transform:lowercase;">{{ $current_moto->title }}</span></b></a>
                            <i class="fal fa-angle-right mx-1"></i>
                            </span>
                            {{--                            @endif--}}
                            <span class="d-inline-block">
                            <a data-toggle="collapse" href="#brands" role="button" aria-expanded="false"
                               aria-controls="brands">
                                Выберите марку <i class="fal fa-angle-down mx-1"></i>
                            </a>
                            </span>
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
                        <div class="col-md-8">
                            <form>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="input-group input-group-filter mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text sort-label"></span>
                                            </div>
                                            {{Form::select(
                                            'sort',
                                            array(
                                                'view_count' => 'Популярности',
                                                'price-asc' => 'Цена (меньше - больше)',
                                                'price-desc' => 'Цена (больше - меньше)',
                                                'v-asc' => 'Объем (меньше - больше)',
                                                'v-desc' => 'Объем (больше - меньше)',
                                            ),
                                            isset($parameters['sort']) ? $parameters['sort'] : 'view_count',
                                            array('class' => 'custom-select filter-select', 'onchange' => 'this.form.submit()')
                                            )}}
                                        </div>
                                    </div>

                                    @if (count($all_brands_arr) > 0)
                                        <div class="col-md-6">
                                            <div class="input-group input-group-filter mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text filter-label"></span>
                                                </div>
                                                {{Form::select(
                                                'brand',
                                                $all_brands_arr,
                                                isset($parameters['brand']) ? $parameters['brand'] : 'all',
                                                array('class' => 'custom-select filter-select', 'onchange' => 'this.form.submit()')
                                                )}}
                                            </div>
                                        </div>
                                    @endif

                                </div>
                            </form>
                        </div>
                        {{--                            {{dd($current_class)}}--}}
                        @if (count($all_classes_arr) > 0)
                            <div class="col-md-4">
                                <div class="input-group input-group-filter mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Класс:</span>
                                    </div>
                                    {{Form::select(
                                        'class',
                                        $all_classes_arr,
                                        isset($parameters['class']) ? $parameters['class'] : $current_class->alias,
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

