@if ($paginator->hasPages())
    <nav>
        <p class="text-xs lg:text-sm py-3">Halaman {{ $paginator->currentPage() }} dari {{ $paginator->lastPage() }}</p>
        <ul class="pagination flex gap-2 flex-wrap">
            {{-- Previous Page Link --}}
            <li class="page-item">
                <a href="{{ $paginator->url(1) }}" class="page-link py-1 px-3 rounded-lg shadow inline-block border hover:border-primary-100 bg-white hover:text-primary-200"><i class="fas fa-arrow-left"></i></a>
            </li>
            @if ($paginator->onFirstPage())
            @else
                <li class="page-item">
                    <a href="{{ $paginator->previousPageUrl() }}" class="page-link py-1 px-3 rounded-lg shadow inline-block border hover:border-primary-100 bg-white hover:text-primary-200"><i data-feather="chevron-left" class="fas fa-chevron-left inline-block"></i></a>
                </li>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <li class="page-item disabled" aria-disabled="true"><span class="page-link">{{ $element }}</span></li>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="page-item" aria-current="page"><span class="page-link py-1 px-3 rounded-lg shadow inline-block border hover:border-primary-100 bg-primary-100 text-white hover:text-white hover:bg-primary-200">{{ $page }}</span></li>
                        @else
                            <li class="page-item"><a class="page-link py-1 px-3 rounded-lg shadow inline-block border hover:border-primary-100 bg-white hover:text-primary-200" href="{{ $url }}">{{ $page }}</a></li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li class="page-item">
                    <a href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="@lang('pagination.next')" class="page-link py-1 px-3 rounded-lg shadow inline-block border hover:border-primary-100 bg-white hover:text-primary-200"><i class="fas fa-chevron-right inline-block"></i></a>
                </li>
            @else
            @endif
            <li class="page-item">
                <a href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="@lang('pagination.next')" class="page-link py-1 px-3 rounded-lg shadow inline-block border hover:border-primary-100 bg-white hover:text-primary-200"><i class="fas fa-arrow-right inline-block"></i></a>
            </li>
        </ul>
    </nav>
@endif
