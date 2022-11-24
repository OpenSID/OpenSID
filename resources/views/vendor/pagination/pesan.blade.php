@if ($paginator->hasPages())
  <div class="pull-right">
    {{ (($paginator->currentPage() * $paginator->perPage()) - $paginator->perPage() + 1) }} - {{ (($paginator->perPage() * $paginator->currentPage()) > $paginator->total())? $paginator->total() : ($paginator->perPage() * $paginator->currentPage())}}/{{ $paginator->total() }}
    <div class="btn-group">
        <a type="button"
          id="prev-links"
          data-current-page="{{ $paginator->currentPage() }}"
          class="btn btn-default btn-sm"><i class="fa fa-chevron-left"></i></a>
        <a type="button"
          id="next-links"
          data-last-page="{{ $paginator->lastPage() }}"
          data-current-page="{{ $paginator->currentPage() }}"
          class="btn btn-default btn-sm"><i class="fa fa-chevron-right"></i></a>
    </div>
  </div>
@endif