<?php if (can('u')): ?>
	<div class="content-wrapper">
		<section class="content-header">
			<h1>Biodata Penduduk</h1>
			<ol class="breadcrumb">
				<li><a href="<?= site_url('beranda')?>"><i class="fa fa-home"></i> Beranda</a></li>
				<li><a href="<?= site_url('penduduk/clear')?>"> Daftar Penduduk</a></li>
				<li class="active">Biodata Penduduk</li>
			</ol>
		</section>
		<section class="content" id="maincontent">
			<form id="mainform" name="mainform" method="POST" enctype="multipart/form-data" onreset="reset_hamil();">
				<div class="row">
					<?php $this->load->view('sid/kependudukan/penduduk_form_isian'); ?>
				</div>
			</form>
		</section>
	</div>
<?php endif; ?>