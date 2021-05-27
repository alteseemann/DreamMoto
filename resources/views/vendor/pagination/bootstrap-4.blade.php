@if($user_agent->isMobile())
    {{--    Мобильная пагинация--}}

    @if ($paginator->hasPages())
        <ul class="pagination" role="navigation">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li class="page-item disabled" aria-disabled="true" aria-label="@lang('pagination.previous')">
                    <span class="page-link" aria-hidden="true">Назад</span>
                </li>
            @else
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev"
                       aria-label="@lang('pagination.previous')">Назад</a>
                </li>
            @endif
            {{-- Pagination Elements --}}
            @foreach ($elements as $element)

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->lastPage() && $page == $paginator->currentPage() && $paginator->lastPage()>2)
                            <li class="page-item disabled" aria-disabled="true"><span
                                        class="page-link">...</span>
                            </li>
                        @endif

                        @if ($page == 1 || $page == $paginator->lastPage())
                            @if ($page == $paginator->currentPage())
                                <li class="page-item active" aria-current="page"><span
                                            class="page-link">{{ $page }}</span>
                                </li>
                            @else
                                <li class="page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                            @endif
                        @else
                            @if ($page == $paginator->currentPage())

                                @if ($page > 2)
                                    <li class="page-item disabled" aria-disabled="true"><span
                                                class="page-link">...</span>
                                    </li>
                                @endif

                                <li class="page-item active" aria-current="page"><span
                                            class="page-link">{{ $page }}</span>
                                </li>

                                @if ($page < $paginator->lastPage()-1)
                                    <li class="page-item disabled" aria-disabled="true"><span
                                                class="page-link">...</span>
                                    </li>
                                @endif

                            @endif
                        @endif

                        @if ($page == 1 && $page == $paginator->currentPage() && $paginator->lastPage()>2)
                            <li class="page-item disabled" aria-disabled="true"><span
                                        class="page-link">...</span>
                            </li>
                        @endif

                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next"
                       aria-label="@lang('pagination.next')">Далее</a>
                </li>
            @else
                <li class="page-item disabled" aria-disabled="true" aria-label="@lang('pagination.next')">
                    <span class="page-link" aria-hidden="true">Далее</span>
                </li>
            @endif
        </ul>
    @endif

@else
    {{-- Десктопная пагинация--}}
    {{--        {{dd($paginator->lastPage())}}--}}
    @if ($paginator->hasPages())
        <ul class="pagination" role="navigation">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li class="page-item disabled" aria-disabled="true" aria-label="@lang('pagination.previous')">
                    <span class="page-link" aria-hidden="true">Назад</span>
                </li>
            @else
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev"
                       aria-label="@lang('pagination.previous')">Назад</a>
                </li>
            @endif
            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <li class="page-item disabled" aria-disabled="true"><span class="page-link">{{ $element }}</span>
                    </li>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="page-item active" aria-current="page"><span class="page-link">{{ $page }}</span>
                            </li>
                        @else
                            <li class="page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next"
                       aria-label="@lang('pagination.next')">Далее</a>
                </li>
            @else
                <li class="page-item disabled" aria-disabled="true" aria-label="@lang('pagination.next')">
                    <span class="page-link" aria-hidden="true">Далее</span>
                </li>
            @endif
        </ul>
    @endif

@endif
