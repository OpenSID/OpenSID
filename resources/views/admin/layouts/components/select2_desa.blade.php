<script>
// Select2 - Cari Nama Desa di API Server Pantau
$('.select-nama-desa').select2({
  ajax: {
    url: function () {
      return $(this).data('tracker') + '/index.php/api/wilayah/caridesa?&token=' + $(this).data('token');
    },
    dataType: 'json',
    data: function (params) {
      return {
        q: params.term || '',
        page: params.page || 1,
      };
    },
    processResults: function (data) {
        return {
          results: data.results,
          pagination: data.pagination,
        }
      }
    },
    placeholder: '--  Cari Nama Desa --',
    minimumInputLength: 0,
});
</script>