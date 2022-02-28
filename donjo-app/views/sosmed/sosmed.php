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
									<li class="<?php ($media === $nama) && print 'active'?>"><a href="<?= site_url("sosmed/tab/{$nama}")?>"><i class="fa fa-<?= $nama?>"></i> <?= $list['nama']?></a></li>
								<?php endforeach; ?>
							</ul>
						</div>
					</div>
				</div>
				<div class="col-md-9">
					<div class="box box-info">
						<?php $this->load->view('sosmed/' . $media); ?>
						<div class='box-footer'>
							<div class='col-xs-12'>
								<button type='reset' class='btn btn-social btn-flat btn-danger btn-sm reset' onclick="reset_form($(this).val());"><i class='fa fa-times'></i> Batal</button>
								<?php if ($this->CI->cek_hak_akses('u')): ?>
									<button type='submit' class='btn btn-social btn-flat btn-info btn-sm pull-right confirm'><i class='fa fa-check'></i> Simpan</button>
								<?php endif; ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</form>
	</section>
</div>
<script>
	$('document').ready(function() {
		$('#tipe').change();
	})

	<?php if ($media === 'facebook'): ?>
		function ubah_pesan(tipe) {
			if (tipe == '1') {
				$('#link').attr('placeholder', 'tokoopendesa');
				$('#ex_facebook').text('Contoh : https://web.facebook.com/tokoopendesa atau tokoopendesa');
			} else {
				$('#link').attr('placeholder', 'opensid');
				$('#ex_facebook').text('Contoh : https://web.facebook.com/groups/opensid atau opensid');
			}
		}
	<?php endif ?>

	<?php if ($media === 'whatsapp'): ?>
		function ubah_pesan(tipe) {
			if (tipe == '1') {
				$('#link').attr('pattern', '(((https?:\\\/\\\/)?api\\.whatsapp\\.com\\\/send(?:\\\/|)[?&]+(\\w+)+=([^&]+).*)|\\d+)');
				$('#link').addClass('bilangan');
				$('#link').attr('minlength', '10');
				$('#link').attr('maxlength', '13');
				$('#link').attr('placeholder', '0851234567890');
				$('#ex_whatsapp').text('Contoh : 0851234567890 (Nomor HP saja) atau https://api.whatsapp.com/send?phone=62851234567890');
			} else {
				$('#link').attr('pattern', '^((https?:\\\/\\\/)?chat\\.whatsapp\\.com\\\/(?:invite\\\/)?([a-zA-Z0-9_-]{22})|([a-zA-Z0-9_-]{22}))');
				$('#link').removeClass('bilangan');
				$('#link').attr('minlength', '20');
				$('#link').attr('maxlength', '30');
				$('#link').attr('placeholder', 'CryQ1VyOXghEVJUTFpwFPb');
				$('#ex_whatsapp').text('Contoh : https://chat.whatsapp.com/CryQ1VyOXghEVJUTFpwFPb atau CryQ1VyOXghEVJUTFpwFPb');
			}
		}
	<?php endif ?>

	<?php if ($media === 'telegram'): ?>
		function ubah_pesan(tipe) {
			if (tipe == '1') {
				$('#link').attr('placeholder', 'OpenDesa');
				$('#link').attr('pattern', '^(?:|(https?:\\\/\\\/)?(|www)[.]?((t|telegram)\\.me)\\\/)[a-zA-Z0-9_]{5,32}$');
				$('#ex_telegram').text('Contoh : https://t.me/OpenDesa atau OpenDesa');
			} else {
				$('#link').attr('placeholder', 'I5antRHvea8ohaU7_RsYYQ');
				$('#link').attr('pattern', '^(?:|(https?:\\\/\\\/)?(|www)[.]?((t|telegram)\\.me)\\\/joinchat\\\/)[a-zA-Z0-9_]{5,32}$');
				$('#ex_telegram').text('Contoh : https://t.me/joinchat/I5antRHvea8ohaU7_RsYYQ atau I5antRHvea8ohaU7_RsYYQ');
			}
		}
	<?php endif ?>

	function reset_form() {
		<?php if ($main['enabled'] === '1'): ?>
			$("#sx3").addClass('active');
			$("#sx4").removeClass("active");
		<?php endif ?>
		<?php if ($main['enabled'] === '2'): ?>
			$("#sx4").addClass('active');
			$("#sx3").removeClass("active");
		<?php endif ?>
	};
</script>