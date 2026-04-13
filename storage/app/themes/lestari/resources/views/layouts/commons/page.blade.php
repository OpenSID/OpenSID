@php defined('BASEPATH') || exit('No direct script access allowed'); @endphp

@if ($items->total() > $items->perPage())
    <div class="pagination_area text-center">
        <div>Halaman {{ $items->currentPage() }} dari {{ $items->lastPage() }}</div>
        <ul class="pagination">
            @if ($items->onFirstPage())
                <li class="disabled"><span><i class="fa fa-fast-backward"></i>&nbsp;</span></li>
                <li class="disabled"><span><i class="fa fa-backward"></i>&nbsp;</span></li>
            @else
                <li><a href="{{ $items->url(1) }}" title="Halaman Pertama"><i class="fa fa-fast-backward"></i>&nbsp;</a></li>
                <li><a href="{{ $items->previousPageUrl() }}" title="Halaman Sebelumnya"><i class="fa fa-backward"></i>&nbsp;</a></li>
            @endif

            @foreach ($items->getUrlRange(1, $items->lastPage()) as $page => $url)
                <li class="{{ $items->currentPage() == $page ? 'active' : '' }}"><a href="{{ $url }}" title="{{ 'Halaman ' . $page }}">{{ $page }}</a></li>
            @endforeach

            @if ($items->hasMorePages())
                <li><a href="{{ $items->nextPageUrl() }}" title="Halaman Selanjutnya"><i class="fa fa-forward"></i>&nbsp;</a></li>
                <li><a href="{{ $items->url($items->lastPage()) }}" title="Halaman Terakhir"><i class="fa fa-fast-forward"></i>&nbsp;</a></li>
            @else
                <li class="disabled"><span><i class="fa fa-forward"></i>&nbsp;</span></li>
                <li class="disabled"><span><i class="fa fa-fast-forward"></i>&nbsp;</span></li>
            @endif
        </ul>
    </div>
@endif
