
</script>
<div class="content-wrapper">
	<section class="content-header">
		<h1>Arsip Layanan Surat</h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('hom_sid')?>"><i class="fa fa-home"></i> Home</a></li>
			<li><a href="<?= site_url('keluar')?>">Arsip Layanan Surat</a></li>
			<li class="active"> Ubah Keterangan Arsip Layanan Surat</li>
		</ol>
	</section>
	<section class="content" id="maincontent">
		<div class="row">
			<div class="col-md-12">
				<div class="box box-info">
					<div class="box-header with-border">
						<a href="<?= site_url("keluar")?>" class="btn btn-social btn-flat btn-info btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"  title="Kembali Ke Arsip Layanan Surat">
							<i class="fa fa-arrow-circle-left "></i>Kembali Ke Arsip Layanan Surat
           	</a>
					</div>
					<div class="box-body">
							<form id="validasi" action="<?= $form_action?>" method="POST" enctype="multipart/form-data"  class="form-horizontal">							
							<div class="form-group">								
							
								<label class="col-sm-3 control-label" for="keperluan">Keterangan</label>
								<div class="col-sm-8">
									<input id="Keterangan" name="Keterangan" class="form-control input-sm required" type="text" placeholder="Keterangan" value="<?= $keluar['keterangan']?>"></input>
								</div>
							</div>
						</div>
						
						<div class='box-footer'>
							<div class='col-xs-12'>
								<button type='reset' class='btn btn-social btn-flat btn-danger btn-sm' ><i class='fa fa-times'></i> Batal</button>
								<button type='submit' class='btn btn-social btn-flat btn-info btn-sm pull-right'><i class='fa fa-check'></i> Simpan</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</section>
</div>

