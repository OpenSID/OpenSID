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
					</div>
					<div class="box-body">
						<div class="dataTables_wrapper form-inline dt-bootstrap no-footer">
							<form id="mainform" name="mainform" method="post">
								<div class="row">
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