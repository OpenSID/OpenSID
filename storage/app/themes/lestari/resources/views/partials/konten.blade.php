@if (isset($halaman))
    @include("partials.{$halaman}")
@else
    @include('theme::commons.not_found')
@endif
