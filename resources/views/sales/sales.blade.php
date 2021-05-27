@extends('layouts.app')

@section('content')

    <div class="content">

        <div class="sale-wrap">

            <div class="row">

                <div class="col-md-9">

                    <h1 class="my-3 font-weight-bold">
                        Продажа {{ $current_moto->title_chego }}
                        @if ($current_brand)
                            {{ $current_brand->title }}
                        @endif
                        @if ($current_product)
                            {{ $current_product->title }}
                        @endif
                    </h1>

                    <div class="breadcrumb">
                        <p class="py-2 px-3 mb-0">
                            @if ($current_brand)
                                <span class="d-inline-block">
                                <a href="{{ Helpers::set_route_city(route('parameter1', $current_moto->alias, false)) }}"><b>{{ $current_moto->title }}</b></a>
                                <i class="fal fa-angle-right mx-1"></i>
                                </span>
                            @endif
                            <span class="d-inline-block">
                            <a data-toggle="collapse" href="#brands" role="button" aria-expanded="false"
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
                                    <a data-toggle="collapse" href="#models" role="button" aria-expanded="false"
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
                                    <div class="p-3">
                                        <div class="brands-list">
                                            @foreach ($brands as $brand)

                                                <a class="d-block mb-1"
                                                   href="{{ Helpers::set_route_city(route('parameter2', [$current_moto->alias, $brand->alias], false)) }}">{{ $brand->title }}</a>

                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @if ($current_brand)
                                <div class="col-12">
                                    <div class="collapse multi-collapse border-top" id="models">
                                        <div class="p-3">
                                            @if ($all_products)
                                                <div class="mb-3"><b>{{ $current_brand->title }}</b></div>

                                                @if ($all_products->count() > 0)

                                                    <div class="product-list">

                                                        @foreach ($all_products as $product)

                                                            <a class="d-block mb-1"
                                                               href="{{  Helpers::set_route_city(route('parameter3', [$current_moto->alias, $current_brand->alias, $product->alias], false)) }}">{{ $product->title }}</a>
                                                            {{--                                                            <a class="d-block mb-1"--}}
                                                            {{--                                                               href="{{ route('catalog.product', [$current_moto->alias, $current_brand->alias, $product->alias]) }}">{{ $product->title }}</a>--}}

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

                    {{--                {{dd($sales)}}--}}

                    @if ($sales)

                        <div class="list-group list-group-flush">
                            @foreach ($sales as $sale)
                                <a href="{{route('sales.show',[$motos->where('id',$sale->product->moto_id)->first()->alias,$sale->product->alias,$sale->id])}}" class="list-group-item list-group-item-action">
                                    <div class="row px-2">
                                        {{--                            @foreach ($sale->product->images as $image)--}}
                                        {{--                                {{dd($image)}}--}}
                                        {{--                            @endforeach--}}
{{--                                        {{dd($sale)}}--}}
                                        <div class="col-md-3 p-0">
                                            <img class="img-fluid"
                                                 @if ($sale->images->count())
                                                 @foreach ($sale->images as $image)
                                                 src="{{ Helpers::get_image(Storage::url($image->path), 600, 400) }}"
                                                 @endforeach
                                                 @else
                                                 @foreach ($sale->product->images as $image)
                                                 src="{{ Helpers::get_image(Storage::url($image->path), 600, 400) }}"
                                                 @endforeach
                                                 @endif
                                                 {{--                                    class="card-img-top border-bottom"--}}
                                                 alt="">
                                        </div>

                                        <div class="col-md-4">
                                            <h5 class="h5 font-weight-bold text-dark mb-0">{{ $sale->product->brand->title }} {{ $sale->product->title }}</h5>
                                            <small class="text-secondary">{{ $sale->product->moto->title_single }}</small>
                                            <p class="font-weight-bold text-dark mb-0">{{ $sale->dealer->city->title }}</p>
                                            <p class="text-secondary mb-0">{{ $sale->dealer->title }}</p>
                                        </div>

                                        <div class="col-md-2">
                                            <p class="font-weight-bold text-dark">{{ number_format($sale->price, 0, '', ' ') }}
                                                ₽</p>
                                        </div>

                                        <div class="col-md-1">
                                            <p class="text-dark">{{ $sale->year }}</p>
                                        </div>

                                        <div class="col-md-2">
                                            @if ($sale->mileage)
                                                <p class="text-dark">{{ $sale->mileage }} км</p>
                                            @else
                                                <p class="text-dark">Новый</p>
                                            @endif
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>

                        @if ($sales->lastPage()>1)
                            <div class="row">
                                <div class="col-12 mt-4">
                                    {{ $sales->links() }}
                                </div>
                            </div>
                        @endif

                    @else

                        <p class="mt-5 text-center">
                            Извините, объявления о продаже {{ $current_moto->title_chego }}
                            @if ($current_brand)
                                {{ $current_brand->title }}
                            @endif
                            @if ($current_product)
                                {{ $current_product->title }}
                            @endif
                            пока не размещены
                        </p>
                        <p class="mb-3 text-center">
                            <a class="link" href="{{ route('catalog.index', [$current_moto->alias], false) }}">
                                Смотреть каталог {{ $current_moto->title_chego }}
                            </a>
                        </p>

                    @endif
                    {{--                {{dd($products)}}--}}
                    {{--@if ($current_product)--}}
                    {{--<h3>{{ $current_product->title }}</h3>--}}
                    {{--@endif--}}

                </div>

                <div class="col-md-3 pt-3">

                    @if (isset($ads_sidebar))

                        {!! $ads_sidebar->script !!}

                    @endif

                </div>

            </div>

        </div>

    </div>


@endsection

