<form id="validasi" action="<?= $form_action ?>" method="post" target="_blank">
    <div class="modal-body">
        <div class="form-group">
            <label for="tahun">Tahun</label>
            <select class="form-control input-sm select2" name="tahun">
                <option value="">Semua</option>
                <?php foreach ($tahun_surat as $tahun): ?>
                    <option value="<?= $tahun['tahun']?>" <?php selected($filter, $tahun['tahun']) ?>><?= $tahun['tahun']?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <?php if ($pamong): ?>
            <div class="form-group">
                <label for="pamong_ttd">Laporan Ditandatangani</label>
                <select class="form-control input-sm select2 required" name="pamong_ttd" width="100%">
                    <option value="">Pilih Staf <?= ucwords(setting('sebutan_pemerintah_desa')) ?></option>
                    <?php foreach ($pamong as $data): ?>
                        <option value="<?= $data['pamong_id']?>" <?= selected($pamong_ttd['pamong_id'], $data['pamong_id'])?>><?= $data['pamong_nama']?> (<?= $data['pamong_jabatan']?>)</option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="pamong_ketahui">Laporan Diketahui</label>
                <select class="form-control input-sm select2 required" name="pamong_ketahui" width="100%">
                    <option value="">Pilih Staf <?= ucwords(setting('sebutan_pemerintah_desa')) ?></option>
                    <?php foreach ($pamong as $data): ?>
                        <option value="<?= $data['pamong_id']?>" <?= selected($pamong_ketahui['pamong_id'], $data['pamong_id'])?>><?= $data['pamong_nama']?> (<?= $data['pamong_jabatan']?>)</option>
                    <?php endforeach; ?>
                </select>
            </div>
        <?php endif; ?>
    </div>
    <div class="modal-footer">
        <button type="reset" class="btn btn-social btn-flat btn-danger btn-sm pull-left" data-dismiss="modal"><i class="fa fa-times"></i> Tutup</button>
        <button type="submit" class="btn btn-social btn-flat btn-info btn-sm" id="ok"><i class='fa fa-check'></i> <?= $aksi?></button>
    </div>
</form>
<?php $this->load->view('global/validasi_form'); ?>
<script type="text/javascript">
    $('document').ready(function() {
        $('#validasi').submit(function() {
            if ($('#validasi').valid()) {
                $('#modalBox').modal('hide');
            }
        });
    });
</script>