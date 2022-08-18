<!--
*
* Kalau ada file form surat di folder desa, pakai file itu.
* Urutan: (1) LOKASI_SURAT_DESA/<folder_surat_ini>
*         (2) LOKASI_SURAT_FORM_DESA
* Kalau tidak ada, pakai file form surat yang disediakan di release SID
* di donjo-app/surat/<folder_surat_ini>
*
 -->
<!-- TODO: Pindahkan ke external css -->
<style>
	.error {
		color: #dd4b39;
	}
</style>
<div class="content-wrapper">
	<?php $this->load->view('surat/form/breadcrumb.php'); ?>
	<section class="content">
		<div class="row">
			<div class="col-md-12">
				<div class="box box-info">
					<div class="box-body">
						<form id="validasi" action="<?= $form_action?>" method="POST" target="_blank" class="form-surat form-horizontal">
							<input type="hidden" id="url_surat" name="url_surat" value="<?= $url ?>">
							<input type="hidden" id="url_remote" name="url_remote" value="<?= site_url('surat/nomor_surat_duplikat')?>">
							<div class="form-group cari_nik">
                                <label for="nik"  class="col-sm-3 control-label">NIK / Nama <?= $pemohon?></label>
                                <div class="col-sm-6 col-lg-4">
                                <select class="form-control input-sm readonly-permohonan readonly-periksa" id="nik" name="nik" style ="width:100%;">
                                    <?php if ($individu): ?>
                                        <option value="<?= $individu['id']?>" selected><?= $individu['nik'] . ' - ' . $individu['tag_id_card'] . ' - ' . $individu['nama']?></option>
                                    <?php endif; ?>
                                </select>
                                </div>
                            </div>
                            <?php if ($individu): ?>
								<?php include 'donjo-app/views/surat/form/konfirmasi_pemohon.php'; ?>
							<?php	endif; ?>
							<div class="row jar_form">
								<label for="nomor" class="col-sm-3"></label>
								<div class="col-sm-8">
									<input class="required" type="hidden" name="nik" value="<?= $individu['id']?>">
								</div>
							</div>
							<?php include 'donjo-app/views/surat/form/nomor_surat.php'; ?>
							<?php foreach ($kode_isian as $item): ?>
                            <div class="form-group">
                                <label for="<?= $item->nama ?>" class="col-sm-3 control-label"><?= $item->nama ?></label>
                                <div class="col-sm-8">
                                    <textarea name="<?= underscore($item->nama, true, true) ?>" class="form-control input-sm required"
                                        placeholder="<?= $item->deskripsi ?>"></textarea>
                                </div>
                            </div>
                            <?php endforeach ?>
							<?php include 'donjo-app/views/surat/form/tgl_berlaku.php'; ?>
							<?php include 'donjo-app/views/surat/form/_pamong.php'; ?>
							<?php include 'donjo-app/views/surat/form/tampil_foto.php'; ?>
						</form>
					</div>
					<?php include 'donjo-app/views/surat/form/tombol_cetak.php'; ?>
				</div>
			</div>
		</div>
	</section>
</div>

<textarea id="isian_form" hidden="hidden"><?= $isian_form?></textarea>

<script type="text/javascript">
  $(document).ready(function() {
    // Di form surat ubah isian admin menjadi disabled
    $("#periksa-permohonan .readonly-periksa").attr('disabled', true);
    if ($('#isian_form').val())
    {
      setTimeout(function() {isi_form();}, 100);
    }
  });

  function isi_form()
  {
    var isian_form = JSON.parse($('#isian_form').val(), function(key, value)
    {
      if (key)
      {
        var elem = $('*[name=' + key + ']');
        elem.val(value);
        elem.change();
        // Kalau isian hidden, akan ada isian lain untuk menampilkan datanya
        if (elem.is(":hidden"))
        {
          var show = $('#' + key + '_show');
          show.val(value);
          show.change();
        }
      }
    });
  }
</script>

