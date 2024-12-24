<script>
    // Select2 - Cari Nama Desa di API Server Pantau

    $.fn.select2.defaults.set('language', {
        errorLoading: function() {
            return 'Hasil tidak dapat dimuat. <a href="' + $('.select-nama-desa').data('tracker') + '/index.php/api/wilayah/caridesa?&token=' + $('.select-nama-desa').data(
                'token') + '" target="_blank">Lihat Url</a>';
        },
        loadingMore: function() {
            return "Memuat hasil lainnya…"
        },
        noResults: function() {
            return 'Tidak ada hasil yang ditemukan. <a href="' + $('.select-nama-desa').data('tracker') + '/index.php/api/wilayah/caridesa?&token=' + $('.select-nama-desa').data(
                'token') + '" target="_blank">Lihat Url</a>';
        },
        searching: function() {
            return "Mencari…"
        }
    });

    $('.select-nama-desa').select2({
        ajax: {
            url: function() {
                return $(this).data('tracker') + '/index.php/api/wilayah/caridesa?&token=' + $(this).data(
                    'token');
            },
            dataType: 'json',
            data: function(params) {
                return {
                    q: params.term || '',
                    page: params.page || 1,
                };
            },
            processResults: function(data) {
                return {
                    results: data.results,
                    pagination: data.pagination,
                }
            },
            cache: true
        },
        placeholder: '--  Cari Nama Desa --',
        minimumInputLength: 0,
        allowClear: true,
        escapeMarkup: function(markup) {
            return markup;
        },
    });
</script>
