@extends('layouts.app')

@section('content')

    <div class="row">
        <div class="col-12">
            <h1 class="font-weight-bold my-2 my-sm-3">
                Мои объявления
            </h1>
        </div>
    </div>

    <div class="row">
        <div class="col-md-3">
            <div class="nav flex-column nav-pills">
                <a class="nav-link btn btn-outline-danger {{ Request::is('*/sales/add') ? 'active' : '' }}"
                   href="{{ route('home.sales') }}"><i
                            class="fal fa-plus-circle mr-1"></i> Добавить объявление</a>
            </div>
            <hr>
            <div class="nav flex-column nav-pills">
                <a class="nav-link {{ Request::get('type') || Request::is('*/sales/add') ? '' : 'active' }}"
                   href="{{ route('home.sales') }}">Все
                    объявления</a>
                @foreach ($dealer->motos as $moto)
                    <a class="nav-link {{ Request::get('type')==$moto->alias ? 'active' : '' }}"
                       href="{{ route('home.sales') }}?type={{ $moto->alias }}">{{ $moto->title }}</a>
                @endforeach
            </div>
        </div>

        <div class="col-md-9">
            <h4 class="mb-3">
                Новое объявление
            </h4>
            <sale-add :dealer="{{ $dealer }}"
                      :sale_images="{{ $sale_images }}"
                      :moto_brands="{{$moto_brands}}"></sale-add>
        </div>
    </div>

@endsection
