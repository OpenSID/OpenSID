<form id="validasi" action="<?= $form_action ?>" method="post" target="_blank">
    <div class="modal-body">
        <div class="form-group">
            <label class="control-label">Tahun Laporan</label>
            <select class="form-control input-sm jenis_link"  name="tahun">>
                <option value="">Pilih Tahun Laporan</option>
                <?php foreach ($tahun_laporan as $tahun): ?>
                    <option value="<?= $tahun['tahun']?>"><?= $tahun['tahun']?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label class="control-label">Pamong Tertanda</label>
            <select class="form-control input-sm jenis_link" name="pamong_ttd">
                <option value="">Pilih Staf Penandatangan</option>
                <?php foreach ($pamong as $data): ?>
                    <option value="<?= $data['pamong_nama']?>" data-jabatan="<?= trim($data['jabatan'])?>" <?php if (stripos($data['jabatan'], 'sekretaris') !== false): ?> selected <?php endif; ?>><?= $data['pamong_nama']?>(<?= $data['jabatan']?>)</option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label class="control-label">Pamong Mengetahui</label>
            <select class="form-control input-sm jenis_link"  name="jabatan_ketahui">
                <option value="">Pilih Staf Mengetahui</option>
                <?php foreach ($pamong as $data): ?>
                    <option value="<?= $data['pamong_nama']?>" data-jabatan="<?= trim($data['jabatan'])?>" <?php if (stripos($data['jabatan'], 'kepala') !== false && stripos($data['jabatan'], 'dusun') === false): ?>selected<?php endif; ?>><?= $data['pamong_nama']?>(<?= $data['jabatan']?>)</option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
    <div class="modal-footer">
        <button type="reset" class="btn btn-social btn-flat btn-danger btn-sm pull-left" data-dismiss="modal"><i class="fa fa-times"></i> Tutup</button>
        <button type="submit" class="btn btn-social btn-flat btn-info btn-sm" id="ok">
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