<div class='modal-body'>
    <form id="validasi" action="{{ ci_route('analisis_respon.' . $analisis_master['id'] . '.data_unduh') }}" method="POST" enctype="multipart/form-data">
        <p>
            Unduh data respon dalam format yang siap diimpor. Gunakan untuk mengisi/mengupdate data respon
            secara massal atau untuk memasukkan data respon ke aplikasi lain.
        <div class="timeline-footer row">
            <button
                name="tipe"
                value="1"
                type="submit"
                onclick="refreshFormCsrf()"
                class="btn btn-social btn-info btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block margin"
                wrap
                target="_blank"
            ><i class="fa fa-download"></i> Form Excel + Isi Data</button>
        </div>
        </p>
        <p>
            Unduh form kosong menampilkan daftar kode untuk setiap kolom.
        <div class="timeline-footer row">
            <button
                name="tipe"
                value="2"
                type="submit"
                onclick="refreshFormCsrf()"
                class="btn btn-social btn-info btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block margin"
                wrap
                target="_blank"
            ><i class="fa fa-download"></i> Form Excel + Kode Data</button>
        </div>
        </p>
        <input type="hidden" name="params">
    </form>
</div>
<div class="modal-footer">
    <button type="reset" class="btn btn-social btn-danger btn-sm" data-dismiss="modal"><i class='fa fa-sign-out'></i>
        Tutup</button>
</div>
<script>
    $(document).ready(function() {
        let _objParams = $('#tabeldata').DataTable().ajax.params()
        delete(_objParams.draw)
        delete(_objParams.search)
        $('form#validasi').append(`<input name="params" type="hidden" value='${JSON.stringify(_objParams)}'>`)
    })
</script>
