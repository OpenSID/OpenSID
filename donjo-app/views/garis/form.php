<?php
/**
 * File ini:
 *
 * View untuk modul Pemetaan (Garis)
 *
 * /donjo-app/views/garis/form.php
 *
 */

/**
 *
 * File ini bagian dari:
 *
 * OpenSID
 *
 * Sistem informasi desa sumber terbuka untuk memajukan desa
 *
 * Aplikasi dan source code ini dirilis berdasarkan lisensi GPL V3
 *
 * Hak Cipta 2009 - 2015 Combine Resource Institution (http://lumbungkomunitas.net/)
 * Hak Cipta 2016 - 2020 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 *
 * Dengan ini diberikan izin, secara gratis, kepada siapa pun yang mendapatkan salinan
 * dari perangkat lunak ini dan file dokumentasi terkait ("Aplikasi Ini"), untuk diperlakukan
 * tanpa batasan, termasuk hak untuk menggunakan, menyalin, mengubah dan/atau mendistribusikan,
 * asal tunduk pada syarat berikut:

 * Pemberitahuan hak cipta di atas dan pemberitahuan izin ini harus disertakan dalam
 * setiap salinan atau bagian penting Aplikasi Ini. Barang siapa yang menghapus atau menghilangkan
 * pemberitahuan ini melanggar ketentuan lisensi Aplikasi Ini.

 * PERANGKAT LUNAK INI DISEDIAKAN "SEBAGAIMANA ADANYA", TANPA JAMINAN APA PUN, BAIK TERSURAT MAUPUN
 * TERSIRAT. PENULIS ATAU PEMEGANG HAK CIPTA SAMA SEKALI TIDAK BERTANGGUNG JAWAB ATAS KLAIM, KERUSAKAN ATAU
 * KEWAJIBAN APAPUN ATAS PENGGUNAAN ATAU LAINNYA TERKAIT APLIKASI INI.
 *
 * @package OpenSID
 * @author  Tim Pengembang OpenDesa
 * @copyright Hak Cipta 2009 - 2015 Combine Resource Institution (http://lumbungkomunitas.net/)
 * @copyright Hak Cipta 2016 - 2020 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license http://www.gnu.org/licenses/gpl.html  GPL V3
 * @link  https://github.com/OpenSID/OpenSID
 */
?>

<div class="content-wrapper">
	<section class="content-header">
		<h1>Pengaturan Garis</h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('hom_sid')?>"><i class="fa fa-home"></i> Home</a></li>
			<li><a href="<?= site_url('garis')?>"><i class="fa fa-dashboard"></i> Daftar Garis</a></li>
			<li class="active">Pengaturan Garis</li>
		</ol>
	</section>
	<section class="content" id="maincontent">
	<form id="validasi" action="<?= $form_action?>" method="POST" enctype="multipart/form-data" class="form-horizontal">
			<div class="row">
				<div class="col-md-3">
          <?php $this->load->view('plan/nav.php')?>
				</div>
				<div class="col-md-9">
					<div class="box box-info">
            <div class="box-header with-border">
							<a href="<?= site_url("garis")?>" class="btn btn-social btn-flat btn-info btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"  title="Tambah Artikel">
								<i class="fa fa-arrow-circle-left "></i>Kembali ke Daftar Garis
            	</a>
						</div>
						<div class="box-body">
							<div class="form-group">
								<label class="control-label col-sm-3">Nama Garis / Properti</label>
								<div class="col-sm-7">
									<input name="nama" class="form-control input-sm nomor_sk required" maxlength="100" type="text" value="<?=$garis['nama']?>"></input>
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-sm-3">Kategori</label>
								<div class="col-sm-7">
									<select class="form-control input-sm" id="ref_line" name="ref_line" style="width:100%;">
									<option value="">Kategori</option>
									<?php foreach ($list_subline AS $data): ?>
										<option <?php if ($garis['ref_line']==$data['id']): ?>selected<?php endif ?> value="<?= $data['id']?>"><?= $data['nama']?></option>
									<?php endforeach;?>
									</select>
								</div>
							</div>
							<?php if ($garis["foto"]): ?>
								<div class="form-group">
									<label class="control-label col-sm-3"></label>
									<div class="col-sm-7">
									  <img class="attachment-img img-responsive img-circle" src="<?= base_url().LOKASI_FOTO_GARIS?>kecil_<?= $garis['foto']?>" alt="Foto">
									</div>
								</div>
							<?php endif; ?>
							<div class="form-group">
								<label class="control-label col-sm-3">Ganti Foto</label>
								<div class="col-sm-7">
									<div class="input-group input-group-sm">
										<input type="text" class="form-control" id="file_path">
										<input id="file" type="file" class="hidden" name="foto" value="<?= $garis['foto']?>">
										<span class="input-group-btn">
											<button type="button" class="btn btn-info btn-flat"  id="file_browser"><i class="fa fa-search"></i> Browse</button>
										</span>
									</div>
									<p class="help-block small text-red">Kosongkan jika tidak ingin mengubah foto.</p>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">Keterangan</label>
								<div class="col-sm-7">
									<textarea id="desk" name="desk" class="form-control input-sm" style="height: 200px;"><?= $garis['desk']?></textarea>
								</div>
							</div>
							<div class="form-group">
								<label class="col-xs-12 col-sm-3 col-lg-3 control-label" for="status">Status</label>
								<div class="btn-group col-xs-12 col-sm-9" data-toggle="buttons">
									<label id="sx3" class="btn btn-info btn-flat btn-sm col-xs-6 col-sm-4 col-lg-2 form-check-label <?php if ($garis['enabled'] =='1' OR $garis['enabled'] == NULL): ?>active<?php endif ?>">
										<input id="sx1" type="radio" name="enabled" class="form-check-input" type="radio" value="1" <?php if ($garis['enabled'] =='1' OR $garis['enabled'] == NULL): ?>checked <?php endif ?> autocomplete="off"> Aktif
									</label>
									<label id="sx4" class="btn btn-info btn-flat btn-sm col-xs-6 col-sm-4 col-lg-2 form-check-label <?php if ($garis['enabled'] == '2' ): ?>active<?php endif ?>">
										<input id="sx2" type="radio" name="enabled" class="form-check-input" type="radio" value="2" <?php if ($garis['enabled'] == '2' ): ?>checked<?php endif ?> autocomplete="off"> Tidak Aktif
									</label>
								</div>
							</div>
						</div>
						<div class='box-footer'>
							<div class='col-xs-12'>
								<button type='reset' class='btn btn-social btn-flat btn-danger btn-sm' onclick="reset_form($(this).val());"><i class='fa fa-times'></i> Batal</button>
								<button type='submit' class='btn btn-social btn-flat btn-info btn-sm pull-right confirm'><i class='fa fa-check'></i> Simpan</button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</form>
	</section>
</div>
<script>
	function reset_form()
	{
		<?php if ($lokasi['enabled'] =='1' OR $lokasi['enabled'] == NULL): ?>
			$("#sx3").addClass('active');
			$("#sx4").removeClass("active");
		<?php endif ?>
		<?php if ($lokasi['enabled'] =='2'): ?>
			$("#sx4").addClass('active');
			$("#sx3").removeClass("active");
		<?php endif ?>
	};
</script>
