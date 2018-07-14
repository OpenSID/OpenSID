<script>
	(function()
	{
		var opsi_width = (parseInt($('#opsi').width())/2)-10;
		$('#opsi div').css('width',opsi_width);
		$('#opsi label').css('width',opsi_width-36);
		$('#opsi input:checked').parent().css({'background':'#c9cdff','border':'1px solid #7a82eb'});
		$('#opsi input').change(function()
		{
			if ($(this).is(':checked')):
				$(this).parent().css({'background':'#c9cdff','border':'1px solid #7a82eb'});
			else:
				$(this).parent().css({'background':'#fafafa','border':'1px solid #ddd'});
			endif;
		});
		$('#opsi label').click(function()
		{
			$(this).prev().trigger('click');
		})

	})();
</script>
<style>
	#opsi div
	{
		margin:1px 0;
		background:#fafafa;
		border:1px solid #ddd;
	}
	#opsi input
	{
		vertical-align:middle;
		margin:0px 2px;
	}
	#opsi label
	{
		padding:4px 10px 0px 2px;
		font-size:11px;
		line-height:12px;
		font-weight:normal;
	}
</style>
<script type="text/javascript" src="<?=base_url()?>assets/js/jquery.validate.min.js"></script>
<script type="text/javascript" src="<?=base_url()?>assets/js/validasi.js"></script>
<form id="validasi" action="<?= $form_action?>" method="post">
<input type="hidden" name="rt" value="">
	<div class='modal-body'>
		<div class="row">
			<div class="col-sm-12">
				<div class="box-body">
					<table width="100%">
						<?php $last="";foreach ($main AS $data):?>
							<?php	if ($data['pertanyaan']!=$last):?>
								<tr>
									<td colspan2><label><?= $data['pertanyaan']?></label></td>
								</tr>
								<tr>
									<td width="3px"></td>
									<td>
										<div class="checkbox">
											<input type="checkbox" name="id_cb[]" value="<?= $data['id_jawaban']?>"<?php if ($data['cek']){echo " checked";}?>>
											<label><?= $data['kode_jawaban'].". ".$data['jawaban']?></label>
										</div>
									</td>
								</tr>
								<?php	else:?>
									<div class="checkbox">
										<input type="checkbox" name="id_cb[]" value="<?= $data['id_jawaban']?>"<?php if ($data['cek']){echo " checked";}?>>
										<label><?= $data['kode_jawaban'].". ".$data['jawaban']?></label>
									</div>
								<?php endif;?>
							<?php	$last=$data['pertanyaan'];?>
						<?php endforeach;?>
					</table>
				</div>
			</div>
		</div>
	</div>
	<div class="modal-footer">
		<button type="reset" class="btn btn-social btn-flat btn-danger btn-sm" data-dismiss="modal"><i class='fa fa-sign-out'></i> Tutup</button>
		<button type="submit" class="btn btn-social btn-flat btn-info btn-sm" id="ok"><i class='fa fa-check'></i> Simpan</button>
	</div>
</form>