<div class="content-wrapper">

	<section class="content-header">
		<h1>Formulir Penambahan Terdata</h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('hom_sid')?>"><i class="fa fa-home"></i> Home</a></li>
			<li><a href="<?= site_url('covid19')?>"> Data Pemudik Saat Covid-19</a></li>
			<li class="active">Formulir Penambahan Terdata</li>
		</ol>
	</section>
	
	<section class="content">
		<div class="row">
			<div class="col-md-12">
				<div class="box box-info">
					<div class="box-header with-border">
						<div class="col-md-12">
							<a href="<?= site_url('covid19')?>" class="btn btn-social btn-flat btn-primary btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Kembali Ke Daftar Suplemen"><i class="fa fa-arrow-circle-o-left"></i> Kembali Ke Daftar Pemudik Saat Covid-19</a>
						</div>
					</div>
					<div class="box-body">
						<div class="row">
							<div class="col-md-12">
								<div class="box-header with-border">
									<h3 class="box-title"Rincian Data Suplemen</h3>
								</div>
								<div class="box-body ">
									<table class="table table-bordered table-striped table-hover" >
										<tbody>
											<tr>
												<td style="padding-top : 10px;padding-bottom : 10px;width:20%;" >Nama Data</td>
												<td> : Covid-19</td>
											</tr>
											<tr>
												<td style="padding-top : 10px;padding-bottom : 10px;" >Sasaran Terdata</td>
												<td> :  Penduduk</td>
											</tr>
											<tr>
												<td style="padding-top : 10px;padding-bottom : 10px;" >Keterangan</td>
												<td> : Pemudik</td>
											</tr>
										</tbody>
									</table>
								</div>
							</div>
							<div class="col-sm-12">
								<div class="box-header with-border">
									<h3 class="box-title">Tambahkan Warga Pemudik</h3>
								</div>
								<div class="box-body">
									<form action="" id="main" name="main" method="POST"  class="form-horizontal">

										<div class="form-group" >
											<label class="col-sm-3 control-label required"  for="terdata">NIK / Nama</label>
											<div class="col-sm-8">
												<select class="form-control select2" id="terdata" name="terdata"  onchange="formAction('main')" >
													<option value="">-- Silakan Masukan NIK / Nama--</option>
													<?php foreach ($list_penduduk as $item):
														if (strlen($item["id"])>0): ?>
															<option value="<?= $item['id']?>" <?php if ($individu['id']==$item['id']): ?>selected<?php endif; ?>>Nama : <?= $item['nama']." - ".$item['info']?></option>
														<?php endif;
													endforeach; ?>
												</select>
												<small id="data_h_plus_msg" class="form-text text-muted">
													Untuk warga pendatang/tidak tetap. Masukkan data terlebih dahulu di menu 'Kependudukan' => 'Penduduk' => '+Penduduk Domisili' dengan pilihan Status Penduduk 'Tidak Tetap'/'Pendatang'
												</small>
											</div>
										</div>
										
									</form>
									<div id="form-melengkapi-data-peserta">
										<form id="validasi" action="<?= $form_action?>" method="POST" enctype="multipart/form-data" class="form-horizontal">
											<div class="form-group">
												<label  class="col-sm-3 control-label"></label>
												<div class="col-sm-8">
													 <input type="hidden" name="id_terdata" value="<?= $individu['id']?>" class="form-control input-sm required">
												 </div>
											</div>
											<?php if ($individu): ?>
												<?php include("donjo-app/views/covid19/konfirmasi_pemudik.php"); ?>
											<?php endif; ?>
											
											<?php include("donjo-app/views/covid19/form_isian_pemudik.php"); ?>

										</form>
									</div>
									<div class="box-footer">
										<div class="col-xs-12">
											<button type="reset" class="btn btn-social btn-flat btn-danger btn-sm"><i class="fa fa-times"></i> Batal</button>
											<button type="submit" class="btn btn-social btn-flat btn-info btn-sm pull-right" onclick="$('#'+'validasi').submit();"><i class="fa fa-check"></i> Simpan</button>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
	
</div>


<script type="text/javascript">
	$(document).ready(function()
	{

		$("#validasi").validate(
		{
		    rules: 
		    {
					terdata: "required",
					asal_pemudik: "required",
					tanggal_tiba: "required",
					durasi_pemudik: 
					{
						number: true
					}
		    },
		    // Specify validation error messages
		    messages: 
		    {
					terdata: "Harus memilik NIK/Nama",
					asal_pemudik: "Isi kota asal pemudik",
					tanggal_tiba: "Tanggal harus diisi",
					durasi_pemudik: "Harus diisi angka"
		    },
		    submitHandler: function(form) 
		    {
		      form.submit();
		    }
	  	});
				
	});
</script>