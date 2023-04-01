<?php $this->load->view('global/validasi_form'); ?>
<form action="<?= $form_action; ?>" method="post" id="validasi">
    <div class="modal-body">
        <div class="form-group">
            <label for="program_bantuan">Program Bantuan</label>
            <select class="form-control input-sm select2" name="program_bantuan">
                <option value="<?= JUMLAH ?>" <?= selected($id_program, JUMLAH); ?>>Penduduk Penerima Bantuan</option>
                <option value="<?= BELUM_MENGISI ?>" <?= selected($id_program, BELUM_MENGISI); ?>>Penduduk Bukan Penerima Bantuan</option>
                <?php foreach ($program_bantuan as $data) : ?>
                    <option value="<?= $data['id'] ?>" <?= selected($id_program, $data['id']); ?>><?= $data['nama']; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
    <div class="modal-footer">
        <button type="reset" class="btn btn-social btn-flat btn-danger btn-sm pull-left"><i class="fa fa-times"></i> Batal</button>
        <button type="submit" class="btn btn-social btn-flat btn-info btn-sm"><i class='fa fa-check'></i> Simpan</button>
    </div>
</form>