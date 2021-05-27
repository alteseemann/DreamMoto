@extends('layouts.app')

@section('content')


    <div class="row">

        <div class="col-12">
            <h1 class="font-weight-bold  my-2 my-sm-3">
                Сравнение {{ $current_moto->title_chego }}
            </h1>
            <div class="text-center mt-5"><h5>Страница в разработке</h5></div>
        </div>
    </div>

{{--{{dd($test)}}--}}
{{--    </div>--}}
{{--    <div class="compare-wrap">--}}
{{--        <div class="container-fluid no-gutters">--}}
{{--            <div class="table-tth" style="overflow: auto;white-space: nowrap;">--}}
{{--                @foreach ($test as $tes)--}}
{{--                    <div class="row-group-title"--}}
{{--                         style="color: transparent;">--}}
{{--                        {{ $tes->title }}--}}
{{--                    </div>--}}
{{--                    <div class="row-group-title">--}}
{{--                        <div class="tth-name"--}}
{{--                             style="width:160px;white-space: normal;padding: 5px 10px;position: absolute;background-color:#fff;">--}}
{{--                            {{ $tes->title }}--}}
{{--                        </div>--}}
{{--                        <div class="tth-name"--}}
{{--                             style="width:160px;display:inline-block;white-space: normal;padding: 5px 10px;color: transparent;">--}}
{{--                            {{ $tes->title }}--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                    --}}{{--            {{dd($tes)}}--}}
{{--                    @foreach ($tes->parameter_names as $te)--}}
{{--                        <div>--}}
{{--                            <div class="tth-name"--}}
{{--                                 style="width: 160px;white-space: normal;padding: 5px 10px;position: absolute;background-color:#fff; height: 100%;">--}}
{{--                                {{ $te->title }}--}}
{{--                            </div>--}}
{{--                            <div class="tth-name"--}}
{{--                                 style="width: 160px; display:inline-block;white-space: normal;padding: 5px 10px;color:transparent;">--}}
{{--                                {{ $te->title }}--}}
{{--                            </div>--}}
{{--                            <div class="row-tth" style="display:inline-block;">--}}
{{--                                @foreach ($te->products as $t)--}}
{{--                                    <div class="tth border-left"--}}
{{--                                         style="width: 160px; display:inline-block;white-space: normal;padding: 5px 10px;vertical-align: top;">--}}
{{--                                        {{ $t->pivot->value }}--}}
{{--                                    </div>--}}
{{--                                @endforeach--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    @endforeach--}}
{{--                @endforeach--}}
{{--            </div>--}}
{{--            --}}{{--{{ dd($current_moto) }}--}}


{{--        </div>--}}
{{--    </div>--}}
{{--    <div class="container">--}}
@endsection

