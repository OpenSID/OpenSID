<form id="validasi" action="<?= $form_action ?>" method="post" target="_blank">
    <input type="hidden" name="tahun">
    <input type="hidden" name="bulan">
    <div class="modal-body">
        <div class="form-group">
            <label for="pamong_ttd">Laporan Ditandatangani</label>
            <select class="form-control input-sm select2 required" name="pamong_ttd" width="100%">
                <option value="">Pilih Staf <?= ucwords(setting('sebutan_pemerintah_desa')) ?></option>
                <?php foreach ($pamong as $data): ?>
                    <option value="<?= $data['pamong_id']?>" <?php selected($data['jabatan_id'], kades()->id); ?>><?= $data['pamong_nama']?> (<?= $data['pamong_jabatan']?>)</option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
    <div class="modal-footer">
        <?= batal() ?>
        <button type="submit" class="btn btn-social btn-info btn-sm" id="ok" data-dismiss="modal" onclick="$('#validasi').submit();"><i class='fa fa-check'></i> <?= $aksi?></button>
    </div>
</form>
<?php $this->load->view('global/validasi_form'); ?>
<!-- Diperlukan karena di hosting yg lambat form belum lengkap sebelum $('#modalBox').on('show.bs.modal' dijalankan di script.js, sehingga csrf field belum ditambahkan -->
<script type="text/javascript">
    $(document).ready(function () {
        addCsrfField($('#validasi')[0]);
    });
</script>
