@push('styles')
    <style>
        .pagination {
            margin-top: 10px;
        }
    </style>
@endpush

<div class="pagination-container text-center" id="pagination-container">
    <hr style="margin: 10px -20px">
    <p class="text-xs lg:text-sm py-3" id="pagination-info">Halaman 1 dari 2</p>
    <ul class="pagination flex justify-center gap-2 flex-wrap" id="pagination-list">
    </ul>
</div>

@push('scripts')
    <script src="{{ theme_asset('js/pagination.js') }}"></script>
@endpush
