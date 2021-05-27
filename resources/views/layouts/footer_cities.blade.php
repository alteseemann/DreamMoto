<div class="alpha mb-2 text-uppercase"><b>Города</b></div>
<div class="pb-3 mb-3"
     style="margin-left: -15px;margin-right: -15px;">
    <div class="cities-wrap px-3">

        @if (isset($current_brand) && $current_brand)

            @foreach ($cities as $alpha)
                <div class="city-alpha">
                    <b class="alpha">{{$alpha['letter']}}</b>
                    @foreach ($alpha['cities'] as $city)
                        <a href="{{ route('city.dealers.brand', [$city['alias'], $current_moto->alias, $current_brand->alias]) }}"
                           class="d-block city-link">
                            {{ $city['title'] }}
                        </a>
                    @endforeach
                </div>
            @endforeach

        @elseif (isset($current_moto) && $current_moto)

            @foreach ($cities as $alpha)
                <div class="city-alpha">
                    <b class="alpha">{{$alpha['letter']}}</b>
                    @foreach ($alpha['cities'] as $city)
                        <a href="{{ route('city.dealers.index', [$city['alias'], $current_moto->alias]) }}"
                           class="d-block city-link">
                            {{ $city['title'] }}
                        </a>
                    @endforeach
                </div>
            @endforeach

        @else

            @foreach ($cities as $alpha)
                <div class="city-alpha">
                    <b class="alpha">{{$alpha['letter']}}</b>
                    @foreach ($alpha['cities'] as $city)
                        <a href="/{{ $city['alias'] }}" class="d-block city-link">
                            {{ $city['title'] }}
                        </a>
                    @endforeach
                </div>
            @endforeach

        @endif

    </div>
</div>

