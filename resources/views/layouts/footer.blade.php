<footer class="mt-5 pt-3">
    <div class="container">

        <div class='row sub-footer pb-3 mb-3' style="border-bottom: solid 1px rgba(167,179,203,.25);">
            <div class="col-6">
                <div class="logo-footer mr-3 d-inline">
                    <a href="{{ Helpers::set_route_city(route('welcome')) }}">
                        <img src="/images/logo.svg" alt="{{ config('app.name', 'Laravel') }}"
                             style="height: 17px; margin-top: -5px;">
                    </a>
                </div>
                <span class="d-inline-block">
                    <i class="fal fa-copyright"></i> {{ date('Y') }}
                </span>
            </div>
            <div class="col-6 d-flex">
                <callback></callback>
            </div>
        </div>

        @include('layouts.footer_brands')

        @include('layouts.footer_cities')

        <div class="row py-4" style="border-top: solid 1px rgba(167,179,203,.25);">
            <div class="col-12 text-center small text-white">
                Указанные на сайте данные дилеров, цены, характеристики и фотографии моделей носят информационный характер и не являются публичной офертой.<br>
                Технические характеристики, цены и внешний облик мотоциклов могут быть изменены производителем.
            </div>
        </div>

    </div>
</footer>
