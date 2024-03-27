<div class="form-group subtitle_head">
    <label class="col-sm-12 control-label">TAMBAHKAN KE SURAT KELUAR</label>
</div>

<div class="form-group">
    <label class="col-sm-3 control-label">Simpan Sebagai Arsip Surat Keluar</label>
    <div class="col-sm-6 col-lg-4">
        <div class="btn-group col-xs-12 col-sm-8" data-toggle="buttons" style="padding: 0px;">
            <label class="btn btn-info btn-flat btn-sm col-xs-6 col-sm-5 col-lg-3 form-check-label">
                <input type="radio" name="surat_keluar" class="form-check-input" value="1" autocomplete="off">Ya</label>
            <label class="btn btn-info btn-flat btn-sm col-xs-6 col-sm-5 col-lg-3 form-check-label active">
                <input type="radio" name="surat_keluar" class="form-check-input" value="0" autocomplete="off">Tidak
            </label>
        </div>
    </div>
</div>

<div id="modul-surat-keluar">
    <div class="form-group">
        <label class="col-sm-3 control-label">Tanggal Surat</label>
        <div class="col-sm-6 col-lg-4">
            <div class="input-group input-group-sm date">
                <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                </div>
                <input class="form-control input-sm pull-right required" id="tgl_2" name="tanggal_surat" type="text">
            </div>
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-3 control-label">Tujuan</label>
        <div class="col-sm-6 col-lg-4">
            <input id="tujuan" name="tujuan" class="form-control input-sm required" type="text" placeholder="Tujuan">
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-3 control-label">Tanggal Surat</label>
        <div class="col-sm-6 col-lg-4">
            <textarea id="isi_singkat" name="isi_singkat" class="form-control input-sm required" placeholder="Isi Singkat/Perihal" rows="3" style="resize:none;"></textarea>
        </div>
    </div>
</div>

@push('scripts')
    <script type="text/javascript">
        $(function() {
            surat_keluar();
            $('input[name="surat_keluar"]').on('change', function(e) {
                surat_keluar()
            });

            function surat_keluar() {
                if ($('input[name="surat_keluar"]').filter(':checked').val() == 1) {
                    $('input[name="tanggal_surat"]').attr("required", true);
                    $('input[name="tujuan"]').attr("required", true);
                    $('input[name="isi_singkat"]').attr("required", true);
                    $('#modul-surat-keluar').show();
                } else {
                    $('input[name="tanggal_surat"]').attr("required", false);
                    $('input[name="tujuan"]').attr("required", false);
                    $('input[name="isi_singkat"]').attr("required", false);
                    $('#modul-surat-keluar').hide();
                }
            }
        });
    </script>
@endpush
