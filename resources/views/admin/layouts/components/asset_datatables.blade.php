@push('css')
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="{{ asset('bootstrap/css/dataTables.bootstrap.min.css') }}">
@endpush

@push('scripts')
    <!-- DataTables JS-->
    <script src="{{ asset('bootstrap/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('bootstrap/js/dataTables.bootstrap.min.js') }}"></script>
    <script>
        $.extend($.fn.dataTable.defaults, {
            responsive: true,
            processing: true,
            serverSide: true,
            autoWidth: false,
            lengthMenu: [
                [10, 25, 50, 100],
                [10, 25, 50, 100]
            ],
            pageLength: 10,
            language: {
                url: "{{ asset('bootstrap/js/dataTables.indonesian.lang') }}",
            }
        });

        $(document).on('keyup', '.dataTables_filter input[type="search"]', function() {
            var inputVal = $(this).val();
            
            if (inputVal.length > 50) {
                $(this).val(inputVal.substring(0, 50));
                var table = $(this).closest('div').find('table').DataTable();
                if (table) {
                    table.search(inputVal.substring(0, 50)).draw();
                }
            } else {
                $(this).closest('.dataTables_filter').find('.search-limit-warning').remove();
            }
        });

        $(document).on('initComplete', function() {
            $('.dataTables_filter input[type="search"]').attr({
                'title': 'Masukkan kata kunci untuk mencari (maksimal 50 karakter)',
                'data-toggle': 'tooltip',
                'data-placement': 'top'
            }).tooltip({
                container: 'body',
                html: true
            });
        });

        $(document).ready(function() {
            setTimeout(function() {
                $('.dataTables_filter input[type="search"]').each(function() {
                    $(this).attr({
                        'title': 'Masukkan kata kunci untuk mencari (maksimal 50 karakter)',
                        'data-toggle': 'tooltip',
                        'data-placement': 'top'
                    }).tooltip({
                        container: 'body',
                        html: true
                    });
                });
            }, 500);
        });

        $(document).on('preXhr.dt', function(e, settings, data) {
            if (data.search && data.search.value && data.search.value.length > 50) {
                data.search.value = data.search.value.substring(0, 50);
            }
        });
    </script>
@endpush
