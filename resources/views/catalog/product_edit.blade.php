@extends('layouts.app')

@section('content')

    <div class="content">

        <div class="catalog-wrap">

            <div class="row">

                <div class="col-md-12">

                    <h1 class="font-weight-bold my-2 my-sm-3">
                        {{ $current_brand->title }} {{ $current_product->title }} (Редактирование)
                    </h1>

                    <div class="row">
                        <div class="col-md-6">
                            <product-edit :parameters="{{ $parameters->toJson() }}"
                                               current_product="{{ $current_product->id }}"></product-edit>
                        </div>
                        <div class="col-md-6">
                            <image-upload :proguct_images="{{ $product_images }}"
                                          current_product="{{ $current_product->id }}"></image-upload>
                            {{--                                                                    <img src="{{ Storage::url($proguct_images[0]->path) }}" alt="">--}}
                            {{--                                                                    {{dd(Storage::url($proguct_images[0]->path))}}--}}
                        </div>
                    </div>

                </div>

            </div>

        </div>

    </div>

@endsection

