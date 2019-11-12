<script type="text/javascript" src="<?= base_url()?>assets/js/script.js"></script>
<script type="text/javascript" src="<?= base_url()?>assets/js/jquery.validate.min.js"></script>
<script type="text/javascript" src="<?= base_url()?>assets/js/validasi.js"></script>
<script type="text/javascript">
	$('document').ready(function()
	{
		$('#validasi').submit(function()
		{
      if (!$('#validasi').valid()) 
      {
      	return;
      }
		});
		$("#data_ekspor").change(function()
		{
			var tgl_dari = $("input[name='tgl_dari']");
			if ($(this).val() == '2')
			{
				$('#tanggal_dari').show();
				tgl_dari.addClass('required');
			}
			else
			{
				$('#tanggal_dari').hide();
				tgl_dari.removeClass('required');
			}
		});
		$('#data_ekspor').trigger('change');

		$('#validasi').submit(function(e){
			if (!$('#validasi').valid()) return;
		  e.preventDefault(); // dont submit multiple times
		  this.submit(); // use native js submit

		  setTimeout(function(){
		  	// TODO: menutup dialog tidak bisa dengan cara biasa, jadi terpaksa refresh screen
			  // $('#validasi').closest(".ui-dialog-content").dialog("close");
			  window.top.location.reload(false);
		  }, 500);
		});

	});

</script>
<form action="<?=$form_action?>" method="post" target="_blank" id="validasi">
	<div class='modal-body'>
		<div class="row">
			<div class="col-sm-12">
				<div class="box box-danger">
					<div class="box-body">
						<p>
							Ekspor data dan dokumen informasi publik untuk diimpor ke aplikasi di tingkat supra-desa, seperti PPID kabupaten atau ke aplikasi OpenDK
						</p>
						<div class="form-group">
							<label class="control-label">Data untuk diekspor</label>
							<select class="form-control input-sm required"  id="data_ekspor">>
								<option value="">Pilih data untuk diekspor</option>
								<option value="1">Semua</option>
								<!-- <option value="2">Perubahan saja</option> -->
							</select>
						</div>
						<div class="form-group" id="tanggal_dari">
							<label class="control-label">Perubahan sejak tanggal</label>
							<div class="input-group input-group-sm date">
								<div class="input-group-addon">
									<i class="fa fa-calendar"></i>
								</div>
								<input class="form-control input-sm pull-right tgl" name="tgl_dari" type="text" value="">
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="modal-footer">
			<button type="reset" class="btn btn-social btn-flat btn-danger btn-sm" data-dismiss="modal"><i class='fa fa-sign-out'></i> Tutup</button>
			<button type="submit" class="btn btn-social btn-flat btn-info btn-sm" id="btn-ok" >
				<i class='fa fa-download'></i> Unduh
			</button>
		</div>
	</div>
</form>
