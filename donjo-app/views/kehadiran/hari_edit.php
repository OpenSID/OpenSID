<?php
//hari_edit
$options = array(
        '0'         => 'Hari Biasa',
        '1'           => 'Hari Libur',
      //  '2'         => 'Libur bersama',
        '9'        => 'Lain-lain',
);
 
//echo form_dropdown('shirts', $options, 'large');
//print_r($hari);
?>
<section class="content" id="maincontent">
	<div class="box box-info">
		<form id="frm_tgl" action="<?=$form_action?>" method="POST" enctype="multipart/form-data" class="form-horizontal">
			<input type='hidden' name='action' value='update_tgl' />
			<div class="box-body">
				<div class="form-group">
					<label class="col-sm-3 control-label" for="year">Tanggal</label>
					<div class="col-sm-9">
						<div class="input-group input-group-sm date">
							<div class="input-group-addon">
								<i class="fa fa-calendar"></i>
							</div>
							<input name='tgl_merah' value='<?=@$hari['tgl_merah'];?>'  class="form-control input-sm tgl"
							<?=isset($hari['tgl_merah'])&&$hari['tgl_merah']!='0000-00-00'?'readonly':NULL;?>   />
						</div>
					</div> 
				</div>
				<div class="box-body">
					<div class="form-group">
						<label class="col-sm-3 control-label" for="year">Status </label>
						<div class="col-sm-9">
							<!--select name="tipe" class="form-control input-sm" ></select-->
							<?=form_dropdown('status', $options, @$hari['status']);?>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-3 control-label" for="year">Keterangan </label> 
						<div class="col-sm-9">
							<input name='detail' value='<?=@$hari['detail'];?>'  />
						</div>
					</div>
				</div>
			</div>
			<div class='box-footer'>
				<button type='reset' class='btn btn-social btn-flat btn-danger btn-sm'><i class='fa fa-times'></i> Reset</button>
				<button id='showTanggalMerah' type='button' class='btn btn-social btn-flat btn-info btn-sm pull-right confirm' onclick='hari_edit()'><i class='fa fa-check'></i> Simpan</button>
			</div>
		</form>
	</div>
</section>

<script>

$(document).ready(function()
{
	$('.tgl').datetimepicker(
	{
		format: 'YYYY-MM-DD',
		useCurrent: false,
		locale:'id'
	});
	
});
</script>