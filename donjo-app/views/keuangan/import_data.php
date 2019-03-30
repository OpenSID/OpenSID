<style type="text/css">
	.nowrap { white-space: nowrap; }
</style>
<script>
	$(function()
	{
		var keyword = <?= $keyword?> ;
		$( "#cari" ).autocomplete(
		{
			source: keyword,
			maxShowItems: 10,
		});
	});
</script>
<div class="content-wrapper">
	<section class="content-header">
		<h1>Keuangan</h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('hom_sid')?>"><i class="fa fa-home"></i> Home</a></li>
			<li class="active">Keuangan</li>
		</ol>
	</section>
	<section class="content" id="maincontent">
    <form id="validasi" action="<?= $form_action?>" method="POST" enctype="multipart/form-data">
  	<div class='modal-body'>
  		<div class="row">
  			<div class="col-sm-12">
  				<div class="box box-danger">
  					<div class="box-body">
  						<div class="form-group">
  							<label for="file"  class="control-label">Versi Database</label>
  							<select class="form-control" name="versi_database">
                  <option value="">.:Pilih Versi Database:.</option>
                  <option value="1">Siskuedes versi 1</option>
                  <option value="2">Siskuedes versi 2</option>
                </select>
  						</div>
  						<div class="form-group">
  							<label for="file"  class="control-label">Tahun Anggaran</label>
  							<select class="form-control" name="tahun_anggaran">
                  <option value="">.:Pilih Tahun Anggaran:.</option>
                  <option value="2018">2018</option>
                  <option value="2019">2019</option>
                </select>
  						</div>
  						<div class="form-group">
  							<label for="file"  class="control-label">Berkas Database Siskuedes :</label>
  							<div class="input-group input-group-sm">
  								<input type="text" class="form-control" id="file_path2">
  								<input type="file" class="hidden" id="file2" name="keuangan">
  								<span class="input-group-btn">
  									<button type="button" class="btn btn-info btn-flat"  id="file_browser2"><i class="fa fa-search"></i> Browse</button>
  								</span>
  							</div>
  							<p class="help-block small">Pastikan format berkas .mde</p>
  						</div>
  					</div>
  					<div class="modal-footer">
  						<button type="submit" class="btn btn-social btn-flat btn-info btn-sm" id="ok"><i class='fa fa-check'></i> Simpan</button>
  					</div>
  				</div>
  			</div>
  		</div>
  	</div>
  </form>


	</section>
</div>
