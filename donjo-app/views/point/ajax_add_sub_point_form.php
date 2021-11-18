<script type="text/javascript" src="<?= base_url()?>assets/js/jquery.validate.min.js"></script>
<script type="text/javascript" src="<?= base_url()?>assets/js/validasi.js"></script>
<script type="text/javascript" src="<?= base_url()?>assets/js/localization/messages_id.js"></script>
<!-- TODO: Pindahkan ke external css -->
<style>
	.input-hidden[type=radio]:checked  +  label
	{
		border : 1px solid #fff;
		box-shadow:0 0 3px 3px #090;
	}

	.bs-glyphicons
	{
		padding-left: 0;
		padding-bottom: 1px;
		margin-bottom: 20px;
		list-style: none;
		overflow: hidden;
	}

	.bs-glyphicons li
	{
		float: left;
		width: 25%;
		height: 115px;
		padding: 10px;
		margin: 0 -1px -1px 0;
		font-size: 12px;
		line-height: 1.4;
		text-align: center;
		border: 1px solid #ddd;
	}

	.bs-glyphicons .glyphicon
	{
		margin-top: 5px;
		margin-bottom: 10px;
		font-size: 24px;
	}

	.bs-glyphicons .glyphicon-class
	{
		display: block;
		text-align: center;
		word-wrap: break-word; /* Help out IE10+ with class names */
	}

	.bs-glyphicons li:hover
	{
		background-color: #605ca8;
		color:#fff;
	}
	.bs-glyphicons li.active
	{
		background-color: #605ca8;
		color:#fff;
	}
	@media (min-width: 768px)
	{
		.bs-glyphicons li
		{
			width: 12.5%;
		}
	}

	.vertical-scrollbar
	{
		overflow-x: hidden; /*for hiding horizontal scroll bar*/
		overflow-y: auto; /*for vertical scroll bar*/
	}
</style>
<form id="validasi" action="<?= $form_action?>" method="POST" enctype="multipart/form-data">
	<div class='modal-body'>
		<div class="row">
			<div class="col-sm-12">
				<div class="box box-danger">
					<div class="box-body">
						<div class="form-group">
							<label class="control-label" for="nama">Nama Kategori Lokasi</label>
							<input id="nama" name="nama" class="form-control input-sm nomor_sk required" maxlength="100" type="text" placeholder="Nama Kategori Lokasi" value="<?=$point['nama']?>"></input>
						</div>
						<div class="form-group">
							<label for="nomor"  class="control-label">Simbol</label>
							<?php if ($point['simbol'] != ''): ?>
								<img src="<?= base_url(LOKASI_SIMBOL_LOKASI)?><?= $point['simbol']?>"/>
							<?php else: ?>
								<img src="<?= base_url(LOKASI_SIMBOL_LOKASI)?>default.png"/>
							<?php endif; ?>
						</div>
						<div class="form-group">
							<label for="id_master" class="control-label">Ganti Simbol</label>
							<div  class="vertical-scrollbar" style="max-height:200px;">
								<div class="bs-glyphicons">
								  <ul class="bs-glyphicons">
										<?php foreach ($simbol as $data): ?>
											<li <?php if ($point['simbol'] == $data['simbol']): ?>class="active"<?php endif; ?> onclick="li_active($(this).val());">
												<label>
													<input type="radio" name="simbol" id="simbol" class="input-hidden hidden" value="<?= $data['simbol']?>" value="<?= $data['simbol']?>" <?php if ($point['simbol'] == $data['simbol']): ?>checked<?php endif; ?>>
													<img src="<?= base_url(LOKASI_SIMBOL_LOKASI)?><?= $data['simbol']?>">
													<span class="glyphicon-class"><?= $data['simbol']?></span>
												</label>
											</li>
										<?php endforeach; ?>
									</ul>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="modal-footer">
			<button type="reset" class="btn btn-social btn-flat btn-danger btn-sm" data-dismiss="modal"><i class='fa fa-sign-out'></i> Tutup</button>
			<button type="submit" class="btn btn-social btn-flat btn-info btn-sm" id="ok"><i class='fa fa-check'></i> Simpan</button>
		</div>
	</div>
</form>
<script>
	function li_active()
	{
    $('li').click( function()
		{
      $('li.active').removeClass('active');
      $(this).addClass('active');
      $(this).children("input[type=radio]").click();
    });
	};
</script>
