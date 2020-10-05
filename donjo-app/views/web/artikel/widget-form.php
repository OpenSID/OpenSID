
<div class="content-wrapper">
	<section class="content-header">
		<h1>Pengaturan Widget</h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('hom_sid')?>"><i class="fa fa-home"></i> Home</a></li>
			<li><a href="<?= site_url('web_widget')?>"> Daftar Widget</a></li>
			<li class="active">Pengaturan Widget</li>
		</ol>
	</section>
	<section class="content" id="maincontent">
		<form id="validasi" action="<?= $form_action?>" method="POST" enctype="multipart/form-data" class="form-horizontal">
			<div class="row">
				<div class="col-md-12">
					<div class="box box-info">
						<div class="box-header with-border">
							<a href="<?= site_url("web_widget")?>" class="btn btn-social btn-flat btn-info btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"  title="Tambah Artikel">
								<i class="fa fa-arrow-circle-left "></i>Kembali ke Daftar Widget
							</a>
						</div>
						<div class="box-body">
							<div class="form-group">
								<label class="col-sm-4 control-label" for="judul">Judul Widget</label>
								<div class="col-sm-6">
									<input id="judul" name="judul" class="form-control input-sm required" type="text" placeholder="Judul Widget" value="<?= $widget['judul']?>"></input>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label" for="jenis">Jenis Widget</label>
								<div class="col-sm-6">
									<select id="jenis_widget" name="jenis_widget" class="form-control input-sm">
										<option value="">-- Pilih Jenis Widget --</option>
										<option value="2" <?php selected($widget['jenis_widget'], 2);?>>Statis</option>
										<option value="3" <?php selected($widget['jenis_widget'], 3);?>>Dinamis</option>
									</select>
								</div>
							</div>
							<?php if ($widget['jenis_widget'] AND $widget['jenis_widget'] != 1 AND $widget['jenis_widget'] !=2) $dinamis = true; ?>
							<div id="dinamis" class="form-group" <?php !$dinamis and print('style="display:none;"') ?>>
								<label class="col-sm-4 control-label" for="alamat_kantor">Kode Widget</label>
								<div class="col-sm-6">
									<textarea style="resize:none;height:150px;" id="isi-dinamis" name="isi-dinamis" class="form-control input-sm" placeholder="Kode Widget"><?=$widget['isi']?></textarea>
								</div>
							</div>
							<?php if ($widget['jenis_widget'] AND $widget['jenis_widget'] ==2) $statis = true; ?>
							<div id="statis" class="form-group" <?php !$statis and print('style="display:none;"') ?>>
								<label class="col-sm-4 control-label" for="isi-statis">Nama File Widget (.php)</label>
								<div class="col-sm-6">
									<?php if($list_widget):?>
										<select id="isi-statis" name="isi-statis" class="form-control input-sm">
											<option value="">-- Pilih Widget --</option>
											<?php foreach ($list_widget as $list):?>
												<option value="<?=$list?>" <?php selected($list, $widget['isi']); ?>><?=$list?></option>
											<?php endforeach;?>
										</select>
									<?php else:?>
										<span class="help-block"><code>Widget tidak tersedia atau sudah ditambahkan semua (desa/widgets atau desa/themes/nama_tema/widgets)</code></span>
									<?php endif;?>
									</div>
								</div>
							</div>
							<div class='box-footer'>
								<div class='col-xs-12'>
									<button type='reset' class='btn btn-social btn-flat btn-danger btn-sm' ><i class='fa fa-times'></i> Batal</button>
									<button type='submit' class='btn btn-social btn-flat btn-info btn-sm pull-right confirm'><i class='fa fa-check'></i> Simpan</button>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</form>
	</section>
</div>
<script>
	var elem = document.getElementById("jenis_widget");
	elem.onchange = function()
	{
		var dinamis = document.getElementById("dinamis");
		var statis = document.getElementById("statis");
		dinamis.style.display = (this.value == "3") ? "block":"none";
		statis.style.display = (this.value == "2") ? "block":"none";
	};
</script>
