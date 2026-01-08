@include('admin.layouts.components.form_modal_validasi')
<script>
    $(function() {
        $('input[name=judul]').val($('#judul-statistik').text());

        const checkedIds = $('#tabeldata input[name="id_cb[]"]:checked').map(function() {
            return this.value;
        }).get();

        if (checkedIds.length === 0) {
            return;
        }

        $('#checkbox_div').append(`<input type="hidden" name="id_cb" value='${JSON.stringify(checkedIds)}'>`);
    })

    function cetak() {
        const table = $('#tabeldata').DataTable();
        
        // Retrieve DataTable parameters
        let params = table.ajax.params();
        
        // Simpan original length sebelum diubah
        const originalLength = params.length;

        // Jika checkbox "Cetak Semua Data" di-centang, ubah length parameter ke -1
        if ($('#cetak_semua').is(':checked')) {
            params.length = -1;
        }

        // Convert params object to query string
        let queryString = $.param(params);

        // Get checkbox value
        const privasi_nik = $('#privasi_nik').is(':checked') ? '1' : '0';

        // Set form action with query parameters
        $("#form-cetak").attr("action", `{{ $action }}/${privasi_nik}?${queryString}`);

        // Reset params.length ke original value
        params.length = originalLength;

        // Hide modal
        $('#modalBox').modal('hide');
    }
</script>

@yield('script')

<form target="_blank" action="" method="post" id="form-cetak">
    <div class='modal-body'>
        <div class="alert alert-info alert-dismissible fade in" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <h4 class="alert-heading"><i class="fa fa-info-circle"></i> Informasi Batasan Data</h4>
            <p><strong>Tampilan di Layar:</strong> Dibatasi maksimal <strong>100 baris per halaman</strong> untuk menjaga performa sistem. Gunakan pencarian, filter, atau paginasi untuk melihat data lainnya.</p>
            <p class="mb-0">
                <strong>Opsi Cetak/Unduh Semua Data:</strong> Centang untuk memproses data tanpa paginasi.
                Untuk dataset sangat besar (>10.000 baris), proses mungkin memakan waktu lama atau timeout.
                Pertimbangkan menggunakan filter atau pencarian untuk mempersempit data terlebih dahulu sebelum cetak/unduh.
            </p>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <label for="nama">{{ $labelSensorNik ?? 'Centang kotak berikut apabila NIK/No. KK ingin disensor' }}</label>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <input type="hidden" name="judul" value="">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="privasi_nik">
                        <label class="form-check-label" for="privasi_nik">{{ $labelSensor ?? 'Sensor NIK/No. KK' }}</label>
                    </div>
                </div>
            </div>

            <div class="col-sm-12">
                <label for="semua_data">Memproses seluruh data dalam sistem (mungkin memerlukan waktu lebih lama).</label>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="cetak_semua">
                        <label class="form-check-label" for="cetak_semua">Cetak/Unduh Semua Data</label>
                    </div>
                </div>
            </div>

            @yield('fields')

            <div class="form-group hide" id="checkbox_div"></div>
        </div>
    </div>
    <div class="modal-footer">
        {!! batal() !!}
        <button type="submit" onclick="cetak()" class="btn btn-social btn-info btn-sm"><i class="fa fa-check"></i> {{ ucwords($aksi) }}</button>
    </div>
</form>
