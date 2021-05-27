<div class="my-4 sticky_column">
    {{--    РСЯ --}}
    @if($user_agent->isMobile())
        <ad-block
                margin="{{ '15' }}"
                div_id="{{ 'adblock-11' }}"
                script="{{ Helpers::get_ad_block('R-A-347397-26', 'adblock-11', 2) }}"
        >
        </ad-block>
    @else

        <ad-block
                margin="{{ '15' }}"
                div_id="{{ 'adblock-11' }}"
                script="{{ Helpers::get_ad_block($ads_sidebar->block_id, 'adblock-11', 20) }}"
        >
        </ad-block>

        <ad-block
                margin="{{ '15' }}"
                div_id="{{ 'adblock-12' }}"
                script="{{ Helpers::get_ad_block($ads_sidebar->block_id, 'adblock-12', 21) }}"
        >
        </ad-block>
    @endif

    <div class="card">
        <div class="card-body">
            <h5 class="card-title">
                <strong>Все <a class="link"
                           href="{{ route('catalog.brands', [$current_moto->alias, $current_brand->alias]) }}">
                        <span class="text-lowercase">{{ $current_moto->title }}</span> {{ $current_brand->title }}</a></strong>
            </h5>
            <h5 class="card-title mb-0">
                <strong>Где <a class="link"
                               href="{{ Helpers::set_route_city(route('dealers.brand', [$current_moto->alias, $current_brand->alias], false)) }}">
                        купить {{ $current_brand->title }} {{ $current_product->title }}</a></strong>
            </h5>
        </div>
    </div>
</div>

