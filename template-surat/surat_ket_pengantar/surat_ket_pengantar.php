<style>
	.error {
		color: #dd4b39;
	}
</style>
<div class="content-wrapper">
	<?php $this->load->view("surat/form/breadcrumb.php"); ?>
	<section class="content">
		<div class="row">
			<div class="col-md-12">
				<div class="box box-info">
					<div class="box-header with-border tdk-permohonan tdk-periksa">
						<a href="<?=site_url("surat")?>" class="btn btn-social btn-flat btn-info btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"  title="Kembali Ke Daftar Wilayah">
							<i class="fa fa-arrow-circle-left "></i>Kembali Ke Daftar Cetak Surat
						</a>
					</div>
					<div class="box-body">
						<form action="" id="main" name="main" method="POST" class="form-horizontal">
							<?php include("donjo-app/views/surat/form/_cari_nik.php"); ?>
						</form>
						<form id="validasi" action="<?= $form_action?>" method="POST" target="_blank" class="form-surat form-horizontal">
							<input type="hidden" id="url_surat" name="url_surat" value="<?= $url ?>">
							<input type="hidden" id="url_remote" name="url_remote" value="<?= site_url('surat/nomor_surat_duplikat')?>">
							<?php if ($individu): ?>
								<?php include("donjo-app/views/surat/form/konfirmasi_pemohon.php"); ?>
							<?php	endif; ?>
							<div class="row jar_form">
								<label for="nomor" class="col-sm-3"></label>
								<div class="col-sm-8">
									<input class="required" type="hidden" name="nik" value="<?= $individu['id']?>">
								</div>
							</div>
							<?php include("donjo-app/views/surat/form/nomor_surat.php"); ?>
							<div class="form-group">
								<label for="keperluan" class="col-sm-3 control-label">Keperluan</label>
								<div class="input-group col-sm-8">
									<textarea name="keperluan" id="keperluan" class="form-control input-sm required" placeholder="Keperluan"></textarea>
									<span class="input-group-addon">
										<button id="keperluan-opener" class="btn btn-default" type="button"><i class="fa fa-keyboard-o"></i></button>
									</span>
								</div>
							</div>
							<div class="form-group">
								<label for="keterangan" class="col-sm-3 control-label">Keterangan</label>
								<div class="input-group col-sm-8">
									<textarea name="keterangan" id="keterangan" class="form-control input-sm required" placeholder="Keterangan"></textarea>
									<span class="input-group-addon">
										<button id="keterangan-opener" class="btn btn-default" type="button"><i class="fa fa-keyboard-o"></i></button>
									</span>
								</div>
							</div>
							<?php include("donjo-app/views/surat/form/tgl_berlaku.php"); ?>
							<?php include("donjo-app/views/surat/form/_pamong.php"); ?>
						</form>
					</div>
					<?php include("donjo-app/views/surat/form/tombol_cetak.php"); ?>
				</div>
			</div>
		</div>
	</section>
</div>
<script type='text/javascript'>

	$(function(){

		$('#keperluan')
		.keyboard({
			openOn : null,
			stayOpen : true,
			layout: 'custom',

			display: {
				'bksp'   : '\u2190',
				'enter'  : '\u23CE',
				'normal' : 'ABC',
				'accept' : 'Lanjut',
				'cancel' : 'Tutup'
			},

			layout: 'custom',
			customLayout: {
			'normal': [
				'a b c d e f g h i j k l',
				'm n o p q r s t u v w x',
				'y z 1 2 3 4 5 6 7 8 9 0',
				'{enter} {bksp} {space} {cancel} {accept}'
			]
		}
		})
		.addTyping();

		$('#keterangan')
		.keyboard({
			openOn : null,
			stayOpen : true,
			layout: 'custom',

			display: {
				'bksp'   : '\u2190',
				'enter'  : '\u23CE',
				'normal' : 'ABC',
				'accept' : 'Lanjut',
				'cancel' : 'Tutup'
			},

			layout: 'custom',
			customLayout: {
			'normal': [
				'a b c d e f g h i j k l',
				'm n o p q r s t u v w x',
				'y z 1 2 3 4 5 6 7 8 9 0',
				'{enter} {bksp} {space} {cancel} {accept}'
			]
		}
		})
		.addTyping();

		$('#keperluan-opener').click(function(){
			var kb1 = $('#keperluan').getkeyboard();
			if ( kb1.isOpen ) {
				kb1.close();
			} else {
				kb1.reveal();
			}
		});

		$('#keterangan-opener').click(function(){
			var kb2 = $('#keterangan').getkeyboard();
			if ( kb2.isOpen ) {
				kb2.close();
			} else {
				kb2.reveal();
			}
		});

	});

</script>
