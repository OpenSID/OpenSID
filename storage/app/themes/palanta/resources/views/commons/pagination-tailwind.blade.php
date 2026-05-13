@if ($paginator->hasPages())
    <nav>
        <p class="text-xs lg:text-sm py-3">Halaman {{ $paginator->currentPage() }} dari {{ $paginator->lastPage() }}</p>        
        <ul class="pagination mg-b-0 page-0">
            {{-- Previous Page Link --}}
            <li class="page-item">                
                <a href="{{ $paginator->url(1) }}" class="page-link"><i class="fa fa-angle-double-left"></i></a>
            </li>
            @if ($paginator->onFirstPage())
            
            @else
                <li class="page-item">
                    <a href="{{ $paginator->previousPageUrl() }}" class="page-link"><i data-feather="chevron-left" class="fa fa-angle-left inline-block"></i></a>                    
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
                            <li class="page-item active" aria-current="page"><span class="page-link">{{ $page }}</span></li>
                        @else
                            <li class="page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li class="page-item">
                    <a href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="@lang('pagination.next')" class="page-link"><i class="fa fa-angle-right inline-block"></i></a>
                </li>
            @else
                
            @endif
            <li class="page-item">
                <a href="{{ $paginator->url($paginator->lastPage()) }}" rel="next" aria-label="@lang('pagination.next')" class="page-link"><i class="fa fa-angle-double-right inline-block"></i></a>
            </li>
        </ul>
    </nav>
@endif
