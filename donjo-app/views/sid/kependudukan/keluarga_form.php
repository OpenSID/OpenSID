<?php if ($this->CI->cek_hak_akses('u')): ?>
	<div class="content-wrapper">
		<section class="content-header">
			<h1>Data Keluarga</h1>
			<ol class="breadcrumb">
				<li><a href="<?= site_url('hom_sid')?>"><i class="fa fa-home"></i> Home</a></li>
				<li><a href="<?= site_url('keluarga/clear')?>"> Daftar Keluarga</a></li>
				<li class="active">Data Keluarga</li>
			</ol>
		</section>
		<section class="content" id="maincontent">
			<form id="mainform" name="mainform" action="<?= $form_action?>" method="post" enctype="multipart/form-data">
				<div class="row">
					<div id="nik_kepala" name="nik_kepala"></div>
						<div class="col-md-12">
							<div class='box box-primary'>
								<div class="box-header with-border">
									<a href="<?=site_url('keluarga')?>" class="btn btn-social btn-flat btn-info btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"  title="Kembali Ke Daftar Penduduk">
										<i class="fa fa-arrow-circle-left "></i>Kembali Ke Daftar Keluarga
									</a>
								</div>
								<div class='box-body'>
									<div class="row">
										<div class='col-sm-7'>
											<div class='form-group'>
												<label for="no_kk"> Nomor KK <code id="tampil_nokk" style="display: none;"> (Sementara) </code></label>
												<?php
                                                    // $penduduk dipakai kalau validasi data gagal
                                                    if ($penduduk):
                                                        $no_kk = $penduduk['no_kk'];
                                                    else:
                                                        $no_kk = $kk['no_kk'];
                                                    endif;
?>
												<div class="input-group input-group-sm">
													<span class="input-group-addon">
														<input type="checkbox" title="Centang jika belum memiliki No. KK" id="nokk_sementara" <?= jecho($cek_nokk, '0', 'checked ="checked"') ?>>
													</span>
													<input id="no_kk" name="no_kk" class="form-control input-sm required no_kk" type="text" placeholder="Nomor KK" value="<?= $no_kk?>" <?= jecho($cek_nokk, '0', 'readonly') ?>></input>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="row">
								<div class='col-sm-12'>
									<div class="form-group bg-primary" style="padding:10px">
										<strong>DATA KEPALA KELUARGA :</strong>
									</div>
								</div>
								<?php $this->load->view('sid/kependudukan/penduduk_form_isian'); ?>
							</div>
						</div>
					</div>
				</div>
			</form>
		</section>
	</div>
	<script type="text/javascript">
		$(document).ready(function() {
			$('#nokk_sementara').change(function() {
				var cek_nokk = '<?= $cek_nokk ?>';
				var nokk_sementara_berikut = '<?= $nokk_sementara; ?>';
				var nokk_asli = '<?= $no_kk; ?>';
				if ($('#nokk_sementara').prop('checked')) {
					$('#no_kk').removeClass('no_kk');
					if (cek_nokk != '0') $('#no_kk').val(nokk_sementara_berikut);
					$('#no_kk').prop('readonly', true);
					$('#tampil_nokk').show();
				} else {
					$('#no_kk').addClass('no_kk');
					$('#no_kk').val(nokk_asli);
					$('#no_kk').prop('readonly', false);
					$('#tampil_nokk').hide();
				}
			});

			$('#nokk_sementara').change();
		});
	</script>
<?php endif; ?>