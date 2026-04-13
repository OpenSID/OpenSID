@if ($paginator->hasPages())
    <nav>
        <ul class="pagination flex gap-2 flex-wrap">
            {{-- First Page Link --}}
            <li class="page-item">
                <a href="{{ $paginator->url(1) }}" class="page-link py-1 px-3 rounded-lg shadow inline-block border hover:border-primary-100 bg-white hover:text-primary-200 pagination-link">
                    <i class="fas fa-arrow-left"></i>
                </a>
            </li>

            {{-- Previous Page Link --}}
            @if (!$paginator->onFirstPage())
                <li class="page-item">
                    <a href="{{ $paginator->previousPageUrl() }}" class="page-link py-1 px-3 rounded-lg shadow inline-block border hover:border-primary-100 bg-white hover:text-primary-200 pagination-link">
                        <i class="fas fa-chevron-left inline-block"></i>
                    </a>
                </li>
            @endif

            {{-- Pagination Numbers (Max 3) --}}
            @php
                $current = $paginator->currentPage();
                $last = $paginator->lastPage();
                $start = max(1, $current - 1);
                $end = min($last, $start + 2);
                
                // Adjust start if we're near the end
                if ($end - $start < 2) {
                    $start = max(1, $end - 2);
                }
            @endphp

            {{-- Show dots if we're not starting from page 1 --}}
            @if ($start > 1)
                <li class="page-item">
                    <a class="page-link py-1 px-3 rounded-lg shadow inline-block border hover:border-primary-100 bg-white hover:text-primary-200 pagination-link" href="{{ $paginator->url(1) }}">1</a>
                </li>
                @if ($start > 2)
                    <li class="page-item disabled" aria-disabled="true">
                        <span class="page-link py-1 px-3 rounded-lg shadow inline-block border bg-gray-100 text-gray-500">...</span>
                    </li>
                @endif
            @endif

            {{-- Display the 3 page numbers --}}
            @for ($page = $start; $page <= $end; $page++)
                @if ($page == $current)
                    <li class="page-item" aria-current="page">
                        <span class="page-link py-1 px-3 rounded-lg shadow inline-block border hover:border-primary-100 bg-primary-100 text-white hover:text-white hover:bg-primary-200">{{ $page }}</span>
                    </li>
                @else
                    <li class="page-item">
                        <a class="page-link py-1 px-3 rounded-lg shadow inline-block border hover:border-primary-100 bg-white hover:text-primary-200 pagination-link" href="{{ $paginator->url($page) }}">{{ $page }}</a>
                    </li>
                @endif
            @endfor

            {{-- Show dots if we're not ending at the last page --}}
            @if ($end < $last)
                @if ($end < $last - 1)
                    <li class="page-item disabled" aria-disabled="true">
                        <span class="page-link py-1 px-3 rounded-lg shadow inline-block border bg-gray-100 text-gray-500">...</span>
                    </li>
                @endif
                <li class="page-item">
                    <a class="page-link py-1 px-3 rounded-lg shadow inline-block border hover:border-primary-100 bg-white hover:text-primary-200 pagination-link" href="{{ $paginator->url($last) }}">{{ $last }}</a>
                </li>
            @endif

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li class="page-item">
                    <a href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="@lang('pagination.next')" class="page-link py-1 px-3 rounded-lg shadow inline-block border hover:border-primary-100 bg-white hover:text-primary-200 pagination-link">
                        <i class="fas fa-chevron-right inline-block"></i>
                    </a>
                </li>
            @endif

            {{-- Last Page Link --}}
            <li class="page-item">
                <a href="{{ $paginator->url($last) }}" class="page-link py-1 px-3 rounded-lg shadow inline-block border hover:border-primary-100 bg-white hover:text-primary-200 pagination-link">
                    <i class="fas fa-arrow-right inline-block"></i>
                </a>
            </li>
        </ul>
    </nav>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Add click handler to all pagination links
        const paginationLinks = document.querySelectorAll('.pagination-link');
        
        paginationLinks.forEach(function(link) {
            link.addEventListener('click', function(e) {
                // Store the scroll target in sessionStorage
                sessionStorage.setItem('scrollToArticles', 'true');
            });
        });
    });
    </script>
@endif