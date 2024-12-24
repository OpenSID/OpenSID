<form id="validasi" action="<?= $form_action ?>" method="POST" enctype="multipart/form-data">
    <div class='modal-body'>
        <div class="form-group">
            <label for="file"  class="control-label">File Master Analisis :</label>
            <div class="input-group input-group-sm">
                <input type="text" class="form-control required" id="file_path2" name="userfile">
                <input type="file" class="hidden" id="file2" name="userfile" accept=".xlsx">
                <span class="input-group-btn">
                    <button type="button" class="btn btn-info btn-flat"  id="file_browser2"><i class="fa fa-search"></i> Browse</button>
                </span>
            </div>
        </div>

        <p class="help-block"><b>Aturan :</b></p>
        <p class="help-block small">1. Data yang dibutuhkan untuk Impor dengan memenuhi aturan data sebagai berikut <a href="<?= asset('import/analisis.xlsx') ?>">Aturan Data</a></p>
        <p class="help-block small">2. Contoh format upload Sensus dapat dilihat pada tautan berikut <a href="<?= asset('import/ppls2.xlsx') ?>">Contoh</a></p>
        <p class="help-block small">3. Format file Impor harus <b>.xlsx</b>, lakukan konversi format file jika belum sesuai.</p>
    </div>
    <div class="modal-footer">
        <button type="reset" class="btn btn-social btn-flat btn-danger btn-sm pull-left" data-dismiss="modal"><i class='fa fa-sign-out'></i> Tutup</button>
        <button type="submit" class="btn btn-social btn-flat btn-info btn-sm" id="ok"><i class='fa fa-check'></i> Simpan</button>
    </div>
</form>
<?php $this->load->view('global/validasi_form'); ?>
