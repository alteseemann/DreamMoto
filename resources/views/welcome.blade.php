@extends('layouts.app')

@section('content')
    <div class="content">

        <div class="welcome-wrap">

            <div class="row mt-5">

                @foreach ($motos as $moto)

                    <div class="col-md-6 mb-4 mx-auto">

                        <div class="card text-center"
                             style="background:url({{ $moto->image }}) no-repeat center; -webkit-background-size: cover; background-size: cover;color: #fff; text-transform:uppercase; overflow:hidden; border: none;">

                            <div class="card-body py-5 card-body-gradient">
                                <h5 class="card-title mb-0"
                                    style="text-transform:uppercase; text-align:right;">{{ $moto->title }}</h5>
                                <a href="{{ route('catalog.index', $moto->alias) }}"
                                   class="stretched-link"></a>
                            </div>

                        </div>

                    </div>

                @endforeach

                <div class="col-md-6 mb-4 mx-auto">

                    <div class="card text-center"
                         style="background:url('/images/backgrounds/motos/auto.jpg') no-repeat center; -webkit-background-size: cover; background-size: cover;color: #fff; text-transform:uppercase; overflow:hidden; border: none;">

                        <div class="card-body py-5 card-body-gradient">
                            <h5 class="card-title mb-0"
                                style="text-transform:uppercase; text-align:right;">Автомобили</h5>
                            <a target="_blank" href="https://joymotors.ru"
                               class="stretched-link"></a>
                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

    {{--    </div>--}}

@endsection

