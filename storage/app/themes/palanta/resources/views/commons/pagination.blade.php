<div class="pagination-container text-center" id="pagination-container">
    <hr style="margin: 10px -20px">
    <p class="text-xs lg:text-sm py-3" id="pagination-info">Halaman 0 dari 0</p>
    <div class="pagination mg-b-0 page-0" id="pagination-list">
    </div>
</div>

@push('scripts')
    <script src="{{ theme_asset('js/pagination.js') }}"></script>
@endpush

@push('styles')
    <style>
        #pagination-container ul.pagination li.page-item{
            cursor: pointer;
        }
    </style>
@endpush