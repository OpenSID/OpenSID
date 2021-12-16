<div class="content-wrapper">
	<section class="content-header">
		<h1>Pengaturan Kategori Tipe Gariss</h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('hom_sid')?>"><i class="fa fa-home"></i> Home</a></li>
			<li><a href="<?= site_url('line')?>"><i class="fa fa-dashboard"></i> Daftar Tipe Garis</a></li>
			<li class="active">Pengaturan Kategori Tipe Garis</li>
		</ol>
	</section>
	<section class="content" id="maincontent">
	<form id="validasi" action="<?= $form_action?>" method="POST" class="form-horizontal">
			<div class="row">
				<div class="col-md-3">
          <?php $this->load->view('plan/nav.php')?>
				</div>
				<div class="col-md-9">
					<div class="box box-info">
            <div class="box-header with-border">
							<a href="<?= site_url('line')?>" class="btn btn-social btn-flat btn-info btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"  title="Tambah Artikel">
								<i class="fa fa-arrow-circle-left "></i>Kembali ke Daftar Tipe Garis
							</a>
						</div>
						<div class="box-body">
							<div class="form-group">
								<label class="control-label col-sm-3">Nama Jenis Garis</label>
								<div class="col-sm-7">
									<input name="nama" class="form-control input-sm nomor_sk required" maxlength="100" type="text" placeholder="Nama Jenis Garis" value="<?= $line['nama']?>"></input>
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-sm-3">Jenis</label>
								<div class="col-sm-4">
									<select class="form-control input-sm required" id="jenis" name="jenis" >
										<option value="solid" <?= selected($line['jenis'], 'solid'); ?>>Solid</option>
										<option value="dotted" <?= selected($line['jenis'], 'dotted'); ?>>Dotted</option>
										<option value="dashed" <?= selected($line['jenis'], 'dashed'); ?>>Dashed</option>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-sm-3">Warna Garis</label>
								<div class="col-sm-4">
									<div class="input-group my-colorpicker2">
										<input type="text" id="color" name="color" class="form-control input-sm warna required" placeholder="#FFFFFF" value="<?= $line['color']?>">
										<div class="input-group-addon input-sm">
											<i></i>
										</div>
									</div>
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-sm-3">Tebal Garis</label>
								<div class="col-sm-4">
									<input name="tebal" class="form-control input-sm nomor_sk required" id="tebal" type="number" value="<?= $line['tebal'] ?? 3 ?>" min="1" max="10"></input>
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-sm-3"></label>
								<div class="col-sm-7"><br>
									<p id="showline"></p>
								</div>
							</div>
						</div>
						<div class="box-footer">
							<button type="reset" class="btn btn-social btn-flat btn-danger btn-sm"><i class="fa fa-times"></i> Batal</button>
							<button type="submit" class="btn btn-social btn-flat btn-info btn-sm pull-right"><i class="fa fa-check"></i> Simpan</button>
						</div>
					</div>
				</div>
			</div>
		</form>
	</section>
</div>
<script type="text/javascript">
	$("#showline").hide();
	var j = document.getElementById("jenis");
	var t = document.getElementById("tebal");
	var c = document.getElementById("color");

	function show() {
		var isij = document.forms[0].jenis.value;
		var isit = document.forms[0].tebal.value;
		var isic = document.forms[0].color.value;
		$('#showline').css({
			'display': 'block',
			'border-bottom': isit + 'px ' + isij + ' ' + isic
		});
	}
	j.onchange = show;
	t.onkeyup = show;
	t.onclick = show;
	c.onchange = show;
	show();
</script>