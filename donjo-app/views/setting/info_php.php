<div class="content-wrapper">
	<div class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6">
					<h1 class="m-0 text-dark">
						Info Sistem
					</h1>
				</div>
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><a href="<?= site_url('hom_sid'); ?>"><i class="fa fa-home"></i> Home</a></li>
						<li class="breadcrumb-item active">Info Sistem</li>
					</ol>
				</div>
			</div>
		</div>
	</div>
	<section class="content" id="maincontent">
		<div class="row" >
			<div class="col-md-12">
				<div class="card card-outline card-primary">
					<div class="card-body">
						<div class="nav-tabs-custom">
							<ul class="nav nav-tabs">
								<li class="nav-item" class="active"><a class="nav-link active" data-toggle="tab" href="#ekstensi">Kebutuhan Sistem</a></li>
								<li class="nav-item"><a class="nav-link" data-toggle="tab" href="#info_sistem">Info Sistem</a></li>
							</ul>
							<div class="tab-content">
								<div id="ekstensi" class="tab-pane fade show active">
									<?php if ($mysql['sudah_ok']): ?>
										<div class="alert alert-success" role="alert">
											<p>Versi MYSQL terpasang <?= $mysql['versi']?> sudah memenuhi syarat.</p>
										</div>
									<?php else: ?>
										<div class="alert alert-danger" role="alert">
											<p>Versi MYSQL terpasang <?= $mysql['versi']?> tidak memenuhi syarat.</p>
											<p>Update versi MYSQL supaya minimal <?= $mysql['versi_minimal']?>.</p>
										</div>
									<?php endif;?>
									<?php if ($php['sudah_ok']): ?>
										<div class="alert alert-success" role="alert">
											<p>Versi PHP terpasang <?= $php['versi']?> sudah memenuhi syarat.</p>
										</div>
									<?php else: ?>
										<div class="alert alert-danger" role="alert">
											<p>Versi PHP terpasang <?= $php['versi']?> tidak memenuhi syarat.</p>
											<p>Update versi PHP supaya minimal <?= $php['versi_minimal']?>.</p>
										</div>
									<?php endif;?>
									<?php if ( ! $ekstensi['lengkap']): ?>
										<div class="alert alert-danger" role="alert">
											<p>Ada beberapa ekstensi PHP wajib yang tidak tersedia di sistem anda.
											Karena itu, mungkin ada fungsi yang akan bermasalah.</p>
											<p>Aktifkan ekstensi PHP yang belum ada di sistem anda.</p>
										</div>
									<?php else: ?>
										<p>
											Semua ekstensi PHP yang diperlukan sudah aktif di sistem anda.
										</p>
									<?php endif;?>
									<?php foreach ($ekstensi['ekstensi'] as $key => $value): ?>
										<div class="form-group">
											<div class="input-group col-xs-3">
												<span><?= $key?></span>
												<span class="input-group-btn">
													<button class="btn <?= $value ? 'btn-success' : 'btn-danger'?>" type="button"><i class="fa fa-<?= $value ? 'check' : 'times'?> fa-lg"></i></button>
												</span>
											</div>
										</div>
									<?php endforeach; ?>
								</div>
								<div id="info_sistem" class="tab-pane fade show">
									<?php
										ob_start();
										phpinfo();
										$phpinfo = ob_get_contents();
										ob_end_clean();
										$phpinfo = preg_replace('%^.*<body>(.*)</body>.*$%ms', '$1', $phpinfo);
										echo "
												<style type='text/css'>
														#phpinfo {}
														#phpinfo pre {margin: 0; font-family: monospace;}
														#phpinfo a:link {color: #009; text-decoration: none; background-color: #fff;}
														#phpinfo a:hover {text-decoration: underline;}
														#phpinfo table {border-collapse: collapse; border: 0; width: 934px; box-shadow: 1px 2px 3px #ccc;}
														#phpinfo .center {text-align: center;}
														#phpinfo .center table {margin: 1em auto; text-align: left;}
														#phpinfo .center th {text-align: center !important;}
														#phpinfo td, th {border: 1px solid #666; font-size: 75%; vertical-align: baseline; padding: 4px 5px;}
														#phpinfo h1 {font-size: 150%;}
														#phpinfo h2 {font-size: 125%;}
														#phpinfo .p {text-align: left;}
														#phpinfo .e {background-color: #ccf; width: 300px; font-weight: bold;}
														#phpinfo .h {background-color: #99c; font-weight: bold;}
														#phpinfo .v {background-color: #ddd; max-width: 300px; overflow-x: auto; word-wrap: break-word;}
														#phpinfo .v i {color: #999;}
														#phpinfo img {float: right; border: 0;}
														#phpinfo hr {width: 934px; background-color: #ccc; border: 0; height: 1px;}
												</style>
												<div id='phpinfo'>
														$phpinfo
												</div>
												";
									?>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>
