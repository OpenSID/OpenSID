<div class="modal-body" id="maincontent">

<?php if ($dusun): ?>
  <div class="col-sm-12">
    <div class="form-group">
      <label class="col-sm-3 control-label" for="kepala_lama">Kepala  <?= ucwords($this->setting->sebutan_dusun)?> Sebelumnya</label>
      <div class="col-sm-7">
        <p class="text-muted well well-sm no-shadow" style="margin-top: 10px;">
          <strong><?= $individu["nama"]?></strong>
          <br/>NIK - <?= $individu["nik"]?>
        </p>
      </div>
    </div>
  </div>
<?php endif; ?>

</div>


<?= strtoupper($penduduk['nama'])?>
<?php if ($penduduk['foto']): ?>
   <img class="penduduk profile-user-img img-responsive img-circle" src="<?= AmbilFoto($penduduk['foto'])?>" alt="Foto">
<?php else: ?>
  <img class="penduduk profile-user-img img-responsive img-circle" src="<?= base_url()?>assets/files/user_pict/kuser.png" alt="Foto">
<?php endif; ?>
