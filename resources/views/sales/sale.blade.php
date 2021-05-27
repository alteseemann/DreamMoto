@extends('layouts.app')
@section('additional_plugins')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <link  href="https://cdnjs.cloudflare.com/ajax/libs/fotorama/4.6.4/fotorama.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fotorama/4.6.4/fotorama.js"></script>

@endsection
@section('content')
    <!--Слайдер изображений-->
    <div class="row justify-content-center mt-2">
        <div class="sale-bg" id="fotorama">
            <!--Ценник-->
            <div class="sale-price text-center">
                <span>{{$sale->price.' ₽'}}</span>
            </div>
            <!--Ссылка на каталог-->
            <div class="sale-catalog">
                <a href="{{route('catalog.product',[$current_moto->alias,$brand->alias,$product->alias])}}">
                    <img src="/images/svg/catalog.png" class="ml-1 mr-1" title="Смотреть в каталоге" style="width: 100%;height: 100%">
                </a>
            </div>
            <!--Слайдер-->
            <div class="fotorama"
                 data-nav="thumbs"
                 data-allowfullscreen="true"
                 data-loop="true">
                @foreach ($sale->images as $image)
                    <img class="d-block" src="{{ Helpers::get_image(Storage::url($image->path)) }}" data-src="holder.js/900x400?theme=industrial" alt="Second slide">
                @endforeach
            </div>
        </div>
    </div>
    <!--Слайдер изображений-->
    <!--Описание объявления-->
    <div class="row justify-content-center mt-3">
        <div class="sale-bg" id="sale_description">
            <ul class="nav nav-tabs">

                <li class="nav-item">
                    <a class="nav-link active" data-toggle="tab" href="#description">
                        <img src="/images/svg/description.svg" width="15" height="15" class="ml-1 mr-1">
                        Описание
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#characteristics">
                        <img src="/images/svg/parameters.svg" width="15" height="15" class="ml-1 mr-1">
                        Характеристики
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#contacts">
                        <img src="/images/svg/contact.svg" width="15" height="15" class="ml-1 mr-1">
                        Контакты
                    </a>
                </li>
                @if(Auth::user() && Auth::user()->dealer_id == $dealer->id)
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#edit">
                            <img src="/images/svg/edit.svg" width="15" height="15" class="ml-1 mr-1">
                            Редактирование
                        </a>
                    </li>
                @endif

            </ul>
            <div class="tab-content mt-2">
                <div class="tab-pane fade show active ml-2" id="description">
                    {{strip_tags($sale->description)}}
                </div>
                <div class="tab-pane fade ml-2" id="characteristics">
                    <div class="row">
                        <div class="column w-50 sale_parameter">
                            Марка
                        </div>
                        <div class="column w-50 sale_parameter text-left">
                            {{$brand->title}}
                        </div>
                    </div>
                    <div class="row">
                        <div class="column w-50 sale_parameter">
                            Модель
                        </div>
                        <div class="column w-50 sale_parameter text-left">
                            {{$product->title}}
                        </div>
                    </div>
                    <div class="row">
                        <div class="column w-50 sale_parameter">
                            Продавец
                        </div>
                        <div class="column w-50 sale_parameter text-left">
                            <a href="#">{{$dealer->title}}</a>
                        </div>
                    </div>
                    <div class="row">
                        <div class="column w-50 sale_parameter">
                            Цена
                        </div>
                        <div class="column w-50 sale_parameter text-left">
                            {{$sale->price.' руб.'}}
                        </div>
                    </div>
                    <div class="row">
                        <div class="column w-50 sale_parameter">
                            Год выпуска
                        </div>
                        <div class="column w-50 sale_parameter text-left">
                            {{$sale->year}}
                        </div>
                    </div>
                    <div class="row">
                        <div class="column w-50 sale_parameter">
                            Пробег
                        </div>
                        <div class="column w-50 sale_parameter text-left">
                            {{$sale->mileage.' км'}}
                        </div>
                    </div>
                    <div class="row">
                        <div class="column w-50 sale_parameter">
                            Описание модели
                            <a class="font-weight-bold" href="{{route('catalog.product',[$current_moto->alias,$brand->alias,$product->alias])}}">
                                (перейти в каталог
                                <img src="/images/svg/bw-catalog.svg" width="15" height="15" class="ml-1 mr-1">)
                            </a>
                        </div>
                        <div class="column w-50 sale_parameter text-justify">
                            {{strip_tags($product->description)}}
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade ml-2" id="contacts">
                    <div class="row">
                        <div class="column w-50 sale_parameter">
                            Адрес
                        </div>
                        <div class="column w-50 sale_parameter text-left">
                            {{$dealer->address}}
                        </div>
                    </div>
                    <div class="row">
                        <div class="column w-50 sale_parameter">
                            Телефон
                        </div>
                        <div class="column w-50 sale_parameter text-left">
                            {{$dealer->phone}}
                        </div>
                    </div>
                    <div class="row">
                        <div class="column w-50 sale_parameter">
                            E-mail
                        </div>
                        <div class="column w-50 sale_parameter text-left">

                        </div>
                    </div>
                    <div class="row">
                        <div class="column w-50 sale_parameter">
                            Сайт продавца
                        </div>
                        <div class="column w-50 sale_parameter text-left">
                            <a href="{{$dealer->site}}">{{$dealer->site}}</a>
                        </div>
                    </div>

                    <!--Карта-->
                    <div class="container">
                        <sale-map v-bind:coordinates="{{$coords}}"></sale-map>
                    </div>

                </div>

                <div class="tab-pane fade ml-2" id="edit">
                    <form class="form-inline" method="POST" action="">
                        @csrf
                        <label class="sr-only" for="edit_price">Цена</label>
                        <input type="text" class="form-control mb-2 mr-sm-2" id="edit_price">

                        <button type="submit" class="btn btn-primary mb-2">Submit</button>
                    </form>
                </div>

            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>

    </script>
@endsection
