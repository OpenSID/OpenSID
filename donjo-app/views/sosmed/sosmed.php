<div class="content-wrapper">
	<section class="content-header">
		<h1>Pengaturan <?= str_replace('-', ' ', ucwords($media))?></h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('hom_sid')?>"><i class="fa fa-home"></i> Home</a></li>
			<li class="active"> Pengaturan <?= str_replace('-', ' ', ucwords($media))?></li>
		</ol>
	</section>
	<section class="content" id="maincontent">
		<form id="validasi" action="<?= $form_action?>" method="POST" enctype="multipart/form-data" class="form-horizontal">
			<div class="row">
				<div class="col-md-3">
					<div class="box box-info">
						<div class="box-header with-border">
							<h3 class="box-title">Media Sosial</h3>
							<div class="box-tools">
								<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
							</div>
						</div>
						<div class="box-body no-padding">
							<ul class="nav nav-pills nav-stacked">
								<?php foreach ($list_sosmed as $list) :?>
									<?php $nama = str_replace(' ', '-', strtolower($list['nama']))?>
									<li class="<?php ($media === $nama) and print('active')?>"><a href="<?= site_url("sosmed/tab/$nama")?>"><i class="fa fa-<?= $nama?>"></i> <?= $list['nama']?></a></li>
								<?php endforeach;?>
							</ul>
						</div>
					</div>
				</div>
				<div class="col-md-9">
					<div class="box box-info">
						<?php $this->load->view('sosmed/'.$media); ?>
						<div class='box-footer'>
							<div class='col-xs-12'>
								<button type='reset' class='btn btn-social btn-flat btn-danger btn-sm reset' onclick="reset_form($(this).val());"><i class='fa fa-times'></i> Batal</button>
								<button type='submit' class='btn btn-social btn-flat btn-info btn-sm pull-right confirm'><i class='fa fa-check'></i> Simpan</button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</form>
	</section>
</div>
<script>
	<?php if ($media === 'facebook'): ?>
		$('document').ready(function()
		{
			$('#tipe').change(); // Pertama kali buka form
		});

		function ubah_pesan(tipe)
		{
			if (tipe == 1)
			{
				$('#link').attr('placeholder', ' Personal / Halaman, contoh : https://web.facebook.com/tokoopendesa \n\n Isi kolom ini dengan username : tokoopendesa');
			}
			else
			{
				$('#link').attr('placeholder', ' Group, contoh : https://web.facebook.com/groups/opensid \n\n Isi kolom ini dengan username : opensid');
			}
		};
	<?php endif ?>

	<?php if ($media === 'whatsapp'): ?>
		$('document').ready(function()
		{
			$('#tipe').change(); // Pertama kali buka form
		});

		function ubah_pesan(tipe)
		{
			if (tipe == 1)
			{
				$('#link').attr('placeholder', ' Personal chat, contoh : 0851234567890 (Nomor HP)) \n\n Isi kolom ini dengan nomor HP : 0851234567890');
			}
			else
			{
				$('#link').attr('placeholder', ' Group chat, contoh : https://chat.whatsapp.com/CryQ1VyOXghEVJUTFpwFPb \n\n Isi kolom ini dengan id chat : CryQ1VyOXghEVJUTFpwFPb');
			}
		};
	<?php endif ?>

	<?php if ($media === 'telegram'): ?>
		$('document').ready(function()
		{
			$('#tipe').change(); // Pertama kali buka form
		});

		function ubah_pesan(tipe)
		{
			if (tipe == 1)
			{
				$('#link').attr('placeholder', ' Personal chat, contoh  : https://t.me/OpenDesa \n\n Isi kolom ini dengan username : OpenDesa');
			}
			else
			{
				$('#link').attr('placeholder', ' Group chat, contoh  : https://t.me/joinchat/I5antRHvea8ohaU7_RsYYQ \n\n Isi kolom ini dengan id chat : I5antRHvea8ohaU7_RsYYQ');
			}
		};
	<?php endif ?>

	function reset_form()
	{
		<?php if ($main['enabled'] == '1'): ?>
			$("#sx3").addClass('active');
			$("#sx4").removeClass("active");
		<?php endif ?>
		<?php if ($main['enabled'] == '2'): ?>
			$("#sx4").addClass('active');
			$("#sx3").removeClass("active");
		<?php endif ?>

	};
</script>
