<style type="text/css">
	.nowrap { white-space: nowrap; }
</style>
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
			<input type="hidden" name="jenis_import" id="jenis_import" value="baru">
			<input type="hidden" name="id_keuangan_master" id="id_keuangan_master" value="0">
  	<div class='modal-body'>
  		<div class="row">
  			<div class="col-sm-12">
  				<div class="box box-danger">
  					<div class="box-body">
  						<div class="form-group">
  							<label for="file"  class="control-label">Versi Database</label>
  							<select class="form-control" name="versi_database" id="versi_database">
                  <option value="">.:Pilih Versi Database:.</option>
                  <option value="1">Siskuedes versi 1</option>
                  <option value="2">Siskuedes versi 2</option>
                </select>
  						</div>
  						<div class="form-group">
  							<label for="file"  class="control-label">Tahun Anggaran</label>
  							<select class="form-control" name="tahun_anggaran" id="tahun_anggaran">
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
  						<!-- <button type="submit" class="btn btn-social btn-flat btn-info btn-sm" id="ok" ><i class='fa fa-check'></i> Simpan</button> -->
  						<button type="button" class="btn btn-social btn-flat btn-info btn-sm" id="ok" onclick="simpan()"><i class='fa fa-check'></i> Simpan</button>
  					</div>
  				</div>
  			</div>
  		</div>
  	</div>
  </form>
	</section>
</div>

 <div class="modal modal-danger fade"  id="getCodeModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Peringatan</h4>
              </div>
              <div class="modal-body">
                <p>Versi Database yang akan anda import sudah ada pad sistem!</p>
                <p>Apakah anda ingin melanjutkan proses import untuk menindih datanya?</p>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Batalkan</button>
                <button type="button" class="btn btn-outline" onclick="simpanDataUpdate()">Lanjutkan Import</button>
              </div>
            </div>
          </div>
        </div>

<script type="text/javascript">
		function simpan() {
			if ($("#versi_database").val() == '') {
				alert('Pilih Versi Database!');
				$("#versi_database").focus();
			}else if ($("#tahun_anggaran").val() == '') {
				alert('Pilih Tahun Anggaran!');
				$("#tahun_anggaran").focus();
			}else if ($("#file2").val() == '') {
				alert('Pilih Berkas File Import!');
				$("#file2").focus();
			}else{
				$.ajax({
						 url: '<?= site_url("keuangan/cekVersiDatabase")?>',
							type: "POST",
							datatype:"json",
						 data: $('#validasi').serialize(),
						 success: function(response) {
								 if (response == 0) {
									  $('#validasi').submit();
								 }else{
									 $("#id_keuangan_master").val(response);
									 $("#getCodeModal").modal('show');
								 }
						 }
				 });
			}
		}
		function simpanDataUpdate() {
			$("#jenis_import").val('update');
			$('#validasi').submit();
		}
</script>
