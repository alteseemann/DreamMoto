@if (isset($brands) && $brands && $current_moto)
    <div class="alpha mb-2 text-uppercase"><b>{{ $current_moto->title }} - все марки</b></div>
    <div class="pb-3 mb-3"
         style="border-bottom: 1px solid rgba(167, 179, 203, 0.25);margin-left: -15px;margin-right: -15px;">
        <div class="cities-wrap px-3">
            @foreach ($brands as $brand)

                @if ($brand->alias == 'brp' && $current_moto->alias == 'atv')
                    <a class="d-block city-link"
                       href="{{ route('catalog.brands', [$current_moto->alias, $brand->alias]) }}">{{ $brand->title }}
                        Can-Am</a>
                @elseif ($brand->alias == 'brp' && $current_moto->alias == 'snowmobiles')
                    <a class="d-block city-link"
                       href="{{ route('catalog.brands', [$current_moto->alias, $brand->alias]) }}">{{ $brand->title }}
                        Ski-Doo, Lynxs</a>
                @elseif ($brand->alias == 'brp' && $current_moto->alias == 'boat-motors')
                    <a class="d-block city-link"
                       href="{{ route('catalog.brands', [$current_moto->alias, $brand->alias]) }}">{{ $brand->title }}
                        Evinrude</a>
                @else
                    <a class="d-block city-link"
                       href="{{ route('catalog.brands', [$current_moto->alias, $brand->alias]) }}">{{ $brand->title }}</a>
                @endif

            @endforeach
        </div>
    </div>
@endif

