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
                <a class="nav-link btn btn-outline-danger" href="{{ route('home.sales.add') }}"><i
                            class="fal fa-plus-circle mr-1"></i> Добавить объявление</a>
            </div>
            <hr>
            <!--боковое меню-->
            <div class="nav flex-column nav-pills">
                <a class="nav-link {{ Request::get('type') ? '' : 'active' }}" href="{{ route('home.sales') }}">Все
                    объявления</a>
                @foreach ($current_motos as $moto)
                    <a class="nav-link {{ Request::get('type')==$moto->alias ? 'active' : '' }}"
                       href="{{ route('home.sales') }}?type={{ $moto->alias }}">{{ $moto->title }}</a>
                @endforeach
            </div>
            <!--боковое меню-->
        </div>

        <div class="col-md-9">
            <!--заголовок области объявлений-->
            <h4 class="mb-3">
                @if ($current_moto)
                    {{ $current_moto->title }}
                @else
                    Все объявления
                @endif
            </h4>
            <!--заголовок области объявлений-->

            <div class="list-group list-group-flush">
                @foreach ($sales as $sale)
                    <a href="{{route('sales.show',[$moto->where('id',$sale->product->moto_id)->first()->alias,$sale->product->alias,$sale->id])}}" class="list-group-item list-group-item-action">
                        <div class="row px-2 sale">
                            {{--                            @foreach ($sale->product->images as $image)--}}
                            {{--                                {{dd($image)}}--}}
                            {{--                            @endforeach--}}
                            <div class="col-md-3">
                                <img id="sale_image_{{$sale->id}}" class="img-fluid sale_image"
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
                            <div class="col-md-9 sale_body" id="sale_body_{{$sale->id}}">
                                <div class="row px-2 sale_inform" id="sale_inform_{{$sale->id}}">
                                    <div class="col-md-4">
                                        <h5 class="h5 font-weight-bold text-dark mb-0">{{ $sale->product->brand->title }} {{ $sale->product->title }}</h5>
                                        <small class="text-secondary">{{ $sale->product->moto->title_single }}</small><br>
                                        @if($sale->is_active == 0)
                                            <small style="vertical-align: bottom">Статус: <span style="color: #c87f0a!important;">на модерации</span></small><br>
                                        @endif
                                        @if($sale->is_active == 1)
                                            <small>Статус: <span style="color: #0ac83a!important;">активно</span></small><br>
                                        @endif
                                    </div>

                                    <div class="col-md-4 text-center">
                                        <p class="font-weight-bold text-dark">{{ number_format($sale->price, 0, '', ' ') }}
                                            ₽</p>
                                    </div>

                                    <div class="col-md-2">
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
                                <div class="row sale_description pl-4 pr-4" id="sale_description_{{$sale->id}}">
                                    {{preg_replace('~(?:<!--\s*|\s*-->|</?[a-z\d]+[^>]*>)~', '', $sale->description)}}
                                </div>
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
        </div>
    </div>
@endsection
@section('script')
    <!--Делаю так, чтобы максимальная высота блока с описанием объявления была равна высоте картинки-->
    <script>
        /*for (let id of {{$sales->pluck('id')}}) {
            let maxHeight = document.getElementById('sale_image_' + id).offsetHeight//макс. высота всего объявления
            document.getElementById('sale_body_' + id).style.height = maxHeight + 'px'//высота блока с описанием модели + высота блока с комментарием дилера
            let inform_height = document.getElementById('sale_inform_' + id).offsetHeight//высота блока с описанием модели
            document.getElementById('sale_description_' + id).style.height = (maxHeight - inform_height) + 'px'//высота блока с комментарием дилера
        }*/
    </script>
@endsection
