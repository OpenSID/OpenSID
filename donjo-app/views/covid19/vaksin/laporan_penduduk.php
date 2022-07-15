<div class="content-wrapper">
	<section class="content-header">
		<h1>Pendataan Penerima Vaksin Covid-19</h1>
		<ol class="breadcrumb">
			<li>
				<a href="<?= site_url('hom_sid') ?>"><i class="fa fa-home"></i> Home</a>
			</li>
			<li class="active">Data Penerima Vaksin</li>
		</ol>
	</section>
	<section class="content" id="maincontent">
		<div class="row">
			<div class="col-md-4 col-lg-3">
				<div class="box box-info">
					<div class="box-body no-padding">
						<?php $this->load->view('covid19/vaksin/side') ?>
					</div>
				</div>
			</div>
			<div class="col-md-8 col-lg-9">
				<div class="box box-info">
					<div class="box-header with-border">
						<a href="javascript:;" title="Cetak" class="btn btn-social btn-flat bg-olive btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" data-remote="false" data-toggle="modal" data-target="#cetakBox"><i class="fa fa-print"></i> Cetak</a>
						<a href="javascript:;" title="Unduh" class="btn btn-social btn-flat bg-olive btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" data-remote="false" data-toggle="modal" data-target="#unduhBox"><i class="fa fa-file-excel-o"></i> Unduh</a>
						<a href="<?= site_url("{$this->controller}/clear/laporan_penduduk"); ?>" class="btn btn-social btn-flat bg-purple btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-refresh"></i>Bersihkan</a>
					</div>
					<div class="box-body">
						<div class="dataTables_wrapper form-inline dt-bootstrap no-footer">
							<form id="mainform" name="mainform" method="post">
								<div class="row">
									<div class="col-sm-12">
										<select class="form-control input-sm" name="vaksin" onchange="formAction('mainform', '<?= site_url($this->controller) . '/filter/vaksin/laporan_penduduk' ?>')" style="margin-bottom: 5px;">
											<option value="">-- Status Vaksin --</option>
											<option value="1" <?= selected($vaksin, '1'); ?>>Vaksin Dosis 1</option>
											<option value="2" <?= selected($vaksin, '2'); ?>>Vaksin Dosis 2</option>
											<option value="3" <?= selected($vaksin, '3'); ?>>Vaksin Dosis 3</option>
											<option value="4" <?= selected($vaksin, '4'); ?>>Belum</option>
											<option value="5" <?= selected($vaksin, '5'); ?>>Tunda</option>
										</select>
										<select class="form-control input-sm " name="dusun" onchange="formAction('mainform','<?= site_url("{$this->controller}/filter/dusun/laporan_penduduk"); ?>')" style="margin-bottom: 5px;">
											<option value="">-- Pilih <?= ucwords($this->setting->sebutan_dusun); ?> --</option>
											<?php foreach ($list_dusun as $data) : ?>
												<option value="<?= $data['dusun']; ?>" <?= selected($dusun, $data['dusun']); ?>><?= set_ucwords($data['dusun']); ?></option>
											<?php endforeach; ?>
										</select>
										<select class="form-control input-sm " name="jenis_vaksin" onchange="formAction('mainform','<?= site_url("{$this->controller}/filter/jenis_vaksin/laporan_penduduk"); ?>')" style="margin-bottom: 5px;">
											<option value="">-- Pilih Jenis Vaksin --</option>
											<?php foreach ($list_vaksin as $data) : ?>
												<option value="<?= $data; ?>" <?= selected($jenis_vaksin, $data); ?>><?= set_ucwords($data); ?></option>
											<?php endforeach; ?>
										</select>
										<div class="input-group input-group-sm" style="margin-bottom: 5px;">
											<div class="input-group-addon">
												<i class="fa fa-calendar"></i>
											</div>
											<input type="text" class="form-control input-sm tgl-datepicker"  name="tanggal_vaksin" value="<?= ($tanggal_vaksin) ?>"  >
										</div>
										<div class="input-group input-group-sm" style="margin-bottom: 5px;">
											<div class="input-group-addon">
												<i class="fa fa-filter"></i>
											</div>
											<input name="umur" id="umur" class="form-control ui-autocomplete-input" placeholder="Rentang Umur" title="Contoh : 20-30" type="text" value="<?= ($umur == 0) ? '' : $umur ?>" onkeypress="if (event.keyCode == 13){$('#'+'mainform').attr('action', '<?= site_url($this->controller . '/filter/umur/laporan_penduduk'); ?>');$('#mainform').submit();}" autocomplete="off">
										</div>

									</div>
									<div class="col-sm-12">
										<?php $this->load->view('covid19/vaksin/laporan_penduduk_table') ?>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>

<!-- modal -->
<div id="cetakBox" class="modal fade" role="dialog" style="padding-top:30px;">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Cetak Laporan Penduduk Penerima Vaksin Covid-19</h4>
			</div>
			<?php $this->load->view('global/dialog_cetak', ['form_action' => site_url($this->controller . '/laporan_penduduk_cetak/cetak'), 'aksi' => 'Cetak']) ?>
		</div>
	</div>
</div>

<!-- modal unduh -->
<div id="unduhBox" class="modal fade" role="dialog" style="padding-top:30px;">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Unduh Laporan Penduduk Penerima Vaksin Covid-19 <?= $form_action ?></h4>
			</div>
			<?php $this->load->view('global/dialog_cetak', ['form_action' => site_url($this->controller . '/laporan_penduduk_cetak/unduh'), 'aksi' => 'Unduh']) ?>
		</div>
	</div>
</div>

<script type="text/javascript">
	$(document).ready(function() {
		$('.tgl-datepicker').datetimepicker({
			format: 'DD-MM-YYYY',
			useCurrent: false
		});

		$('.tgl-datepicker').on('dp.change', function(e) {
			formAction('mainform', '<?= site_url("{$this->controller}/filter/tanggal_vaksin/laporan_penduduk"); ?>')
		});

	});
</script>