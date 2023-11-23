<form id="validasi" action="<?= $form_action ?>" method="post" target="_blank">
    <input type="hidden" name="tahun">
    <input type="hidden" name="bulan">
    <div class="modal-body">
        <div class="form-group">
            <label for="pamong_ttd">Laporan Ditandatangani</label>
            <select class="form-control input-sm select2 required" name="pamong_ttd">
                <option value="">Pilih Staf <?= ucwords(setting('sebutan_pemerintah_desa')) ?></option>
                <?php foreach ($pamong as $data): ?>
                    <option value="<?= $data['pamong_id']?>" <?= selected($data['jabatan_id'], $getKades); ?>><?= $data['pamong_nama']?> (<?= $data['pamong_jabatan']?>)</option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="laporan_no">Laporan No.</label>
            <input id="laporan_no" class="form-control input-sm required" type="text" placeholder="Laporan No." name="laporan_no" value="">
        </div>
    </div>
    <div class="modal-footer">
        <?= batal() ?>
        <button type="submit" class="btn btn-social btn-info btn-sm" id="ok"><i class='fa fa-check'></i> <?= ucwords($aksi); ?></button>
    </div>
</form>
<?php $this->load->view('global/validasi_form'); ?>
<script>
    $('document').ready(function() {
        $('#validasi').submit(function() {
            if ($('#validasi').valid()) {
                $('#modalBox').modal('hide');
            }
        });
    });
</script>