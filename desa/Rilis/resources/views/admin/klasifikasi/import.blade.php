<script>
    $('#file_browser2').click(function(e) {
        e.preventDefault();
        $('#file2').click();
    });

    $('#file2').change(function() {
        $('#file_path2').val($(this).val());
    });

    $('#file_path2').click(function() {
        $('#file_browser2').click();
    });
</script>
{!! form_open_multipart($form_action, 'id="validasi"') !!}
<div class='modal-body'>
    <div class="form-group">
        <label for="file" class="control-label">Berkas Klasifikasi Surat :</label>
        <div class="input-group input-group-sm">
            <input type="text" class="form-control" id="file_path2">
            <input type="file" class="hidden" id="file2" name="klasifikasi" accept=".xls,.xlsx,.xlsm">
            <span class="input-group-btn">
                <button type="button" class="btn btn-info btn-flat" id="file_browser2"><i class="fa fa-search"></i> Browse</button>
            </span>
        </div>
        <p class="help-block small">Pastikan format berkas telah sesuai. Format yang dibutuhkan dapat diunduh menggunakan tombol Unduh.</p>
        <a href="{{ ci_route('unduh', encrypt('assets/import/format_impor_klasifikasi_surat.xlsx')) }}" class="btn btn-social bg-purple btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block text-center"><i class="fa fa-file-excel-o"></i> Contoh Format Impor
            Klasifikasi</a>
    </div>
</div>
<div class="modal-footer">
    {!! batal() !!}
    <button type="submit" class="btn btn-social btn-info btn-sm" id="ok"><i class='fa fa-check'></i> Simpan</button>
</div>
</form>
