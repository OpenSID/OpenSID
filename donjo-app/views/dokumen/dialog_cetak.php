<form id="validasi" action="<?= $form_action ?>" method="post" target="_blank">
    <div class="modal-body">
        <div class="form-group">
            <label class="control-label">Tahun Laporan</label>
            <select class="form-control input-sm jenis_link select2"  name="tahun">>
                <option value="">Pilih Tahun Laporan</option>
                <?php foreach ($tahun_laporan as $tahun): ?>
                    <option value="<?= $tahun['tahun']?>"><?= $tahun['tahun']?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <?php if ($kat == 3): ?>
            <div class="form-group">
                <label class="control-label">Jenis Peraturan</label>
                    <select class="form-control input-sm select2" name="jenis_peraturan" style="width: 100%;">
                        <option value=''>-- Pilih Jenis Peraturan --</option>
                        <?php foreach ($jenis_peraturan as $item): ?>
                            <option value="<?= $item ?>"><?= $item?></option>
                        <?php endforeach; ?>
                    </select>
            </div>
        <?php endif; ?>
        <?php if ($pamong): ?>
            <div class="form-group">
                <label class="control-label">Pamong Tertanda</label>
                <select class="form-control input-sm jenis_link select2 required" name="pamong_ttd">
                    <option value="">Pilih Staf Penandatangan</option>
                    <?php foreach ($pamong as $data): ?>
                        <option value="<?= $data['pamong_id']?>" <?= selected($pamong_ttd['pamong_id'], $data['pamong_id'])?>><?= $data['pamong_nama']?> (<?= $data['pamong_jabatan']?>)</option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label class="control-label">Pamong Mengetahui</label>
                <select class="form-control input-sm jenis_link select2 required" name="pamong_ketahui">
                    <option value="">Pilih Staf Mengetahui</option>
                    <?php foreach ($pamong as $data): ?>
                        <option value="<?= $data['pamong_id']?>" <?= selected($pamong_ketahui['pamong_id'], $data['pamong_id'])?>><?= $data['pamong_nama']?> (<?= $data['pamong_jabatan']?>)</option>
                    <?php endforeach; ?>
                </select>
            </div>
        <?php endif; ?>
    </div>
    <div class="modal-footer">
        <?= batal(); ?>
        <button type="submit" class="btn btn-social btn-info btn-sm" id="btn-ok" >
            <?php if (strpos($form_action, '/cetak') !== false): ?>
                <i class='fa fa-print'></i> Cetak
            <?php else: ?>
                <i class='fa fa-download'></i> Unduh
            <?php endif; ?>
        </button>
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