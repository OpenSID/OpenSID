{{-- catatan : ubah gunakan paginasi laravel --}}
@if (isset($links))
    {!! $links->links('theme::commons.pagination-tailwind') !!}
@endif
