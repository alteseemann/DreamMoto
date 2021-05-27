@extends('layouts.app')

@section('content')

    <div class="row">

        <div class="col-md-12">

            <h1 class="font-weight-bold  my-2 my-sm-3">
                Новый дилер
            </h1>


            <dealer-add
                    :motoss="{{ $motos }}"
                    :cities="{{ $cities }}"></dealer-add>


        </div>

    </div>

@endsection
