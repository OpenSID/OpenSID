<style type="text/css">
  .disabled
	{
     pointer-events: none;
     cursor: default;
  }
</style>
<?php
	$nama=""; $nomor="";$asubjek="";
	if (isset($_SESSION['subjek_tipe'])):
		$subjek = $_SESSION['subjek_tipe'];
		switch ($subjek):
			case 1: $nama="Nama"; $nomor="NIK";$asubjek="Penduduk"; break;
			case 2: $nama="Kepala Keluarga"; $nomor="Nomor KK";$asubjek="Keluarga"; break;
			case 3: $nama="Kepala Rumahtangga"; $nomor="Nomor Rumahtangga";$asubjek="Rumahtangga"; break;
			case 4: $nama="Nama Kelompok"; $nomor="ID Kelompok";$asubjek="Kelompok"; break;
			default: return null;
		endswitch;
	endif;
?>
<div class="content-wrapper">
	<section class="content-header">
		<h1><?= $_SESSION['analisis_nama']; ?> [ <?= $asubjek?> ]</h1>
		<ol class="breadcrumb">
			<li><a href="<?=site_url('hom_sid')?>"><i class="fa fa-home"></i> Home</a></li>
			<li><a href="<?=site_url('analisis_master')?>"> Master Analisis</a></li>
			<li class="active"><?= $analisis_master['nama']; ?></li>
		</ol>
	</section>
	<section class="content" id="maincontent">
		<form id="mainform" name="mainform" action="" method="post">
			<div class="row">
				<div class="col-md-4 col-lg-3">
					<?php $this->load->view('analisis_master/left', $data);?>
				</div>
				<div class="col-md-8 col-lg-9">
					<div class="box box-info">
            <div class="box-header with-border">
							<a href="<?= site_url('analisis_master') ?>" class="btn btn-social btn-flat btn-info btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-arrow-circle-left "></i> Kembali Ke Master Analisis</a>
						</div>
						<div class="box-body">
							<div class="col-sm-12">
								<div class="row">
									<h4 class="box-title"><b><?= $analisis_master['nama']; ?></b></h4>
									<div class="box-footer box-comments">	<?= $analisis_master['deskripsi']; ?></div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</form>
	</section>
</div>

