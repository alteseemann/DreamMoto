@extends('layouts.app')

@section('content')

    <div class="content">

        <div class="catalog-wrap">

            <div class="row">

                <div class="col-md-12">

                    <h1 class="font-weight-bold my-2 my-sm-3">
                        Новый {{ $current_moto->title_single }}
                    </h1>

                    <div class="row">
                        <div class="col-md-6">

                            <product-add
                                    :current_moto="{{ $current_moto }}"
                            ></product-add>

                        </div>
                    </div>

                </div>

            </div>

        </div>

    </div>

@endsection

