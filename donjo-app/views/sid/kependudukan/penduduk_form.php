<div class="content-wrapper">
	<div class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6">
					<h1 class="m-0 text-dark">
						Biodata Penduduk
					</h1>
				</div>
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><a href="<?= site_url('hom_sid'); ?>"><i class="fa fa-home"></i> Home</a></li>
						<li class="breadcrumb-item"><a href="<?= site_url('penduduk/clear')?>"> Daftar Penduduk</a></li>
						<li class="breadcrumb-item active">Biodata Penduduk</li>
					</ol>
				</div>
			</div>
		</div>
	</div>
	<section class="content" id="maincontent">
		<form id="mainform" name="mainform" action="<?= $form_action?>" method="POST" enctype="multipart/form-data" onreset="reset_hamil();">
			<div class="row">
				<?php include("donjo-app/views/sid/kependudukan/penduduk_form_isian.php"); ?>
			</div>
		</form>
	</section>
</div>
