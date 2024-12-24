<div class="form-group">
  <label class="col-sm-3 control-label" for="tampil_foto">Tampilkan Foto Penduduk di Surat</label>
  <div class="btn-group col-xs-12 col-sm-8" data-toggle="buttons">
    <label id="m1" class="tipe btn btn-info btn-flat btn-sm col-xs-12 col-sm-6 col-lg-2 form-check-label <?= jecho($tampil_foto, 1, 'active'); ?>">
      <input id="foto1" type="radio" name="tampil_foto" class="form-check-input" type="radio" value="1" <?= jecho($tampil_foto, 1, 'checked'); ?> autocomplete="off">Ya
    </label>
    <label id="m2" class="tipe btn btn-info btn-flat btn-sm col-xs-12 col-sm-6 col-lg-2 form-check-label <?= jecho($tampil_foto != 1, true, 'active'); ?>">
      <input id="foto2" type="radio" name="tampil_foto" class="form-check-input" type="radio" value="0" <?= jecho($tampil_foto != 1, true, 'checked'); ?> autocomplete="off">Tidak
    </label>
  </div>
</div>

