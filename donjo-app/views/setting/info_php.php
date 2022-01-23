<style>
	.scroll {
		height: 500px;
		overflow-y: scroll;
	}
</style>
<div class="content-wrapper">
	<section class="content-header">
		<h1>Info Sistem</h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('hom_sid') ?>"><i class="fa fa-home"></i> Home</a></li>
			<li class="active">Info Sistem</li>
		</ol>
	</section>
	<section class="content" id="maincontent">
		<div class="box box-primary">
			<div class="box-body">
				<div class="nav-tabs-custom">
					<ul class="nav nav-tabs">
						<li class="active"><a data-toggle="tab" href="#log_viewer">Logs</a></li>
						<li><a data-toggle="tab" href="#ekstensi">Kebutuhan Sistem</a></li>
						<li><a data-toggle="tab" href="#info_sistem">Info Sistem</a></li>
					</ul>
					<div class="tab-content">
						<div id="log_viewer" class="tab-pane fade in active">
							<div class="row">
								<div class="col-md-3">
									<div class="box box-info">
										<div class="box-header with-border">
											<h3 class="box-title">File logs</h3>
										</div>
										<div class="box-body no-padding">
											<ul class="nav nav-pills nav-stacked scroll">
												<?php if (empty($files)) : ?>
													<li><a href="#"><?= $file; ?>File log tidak ditemukan</a></li>
												<?php else : ?>
													<?php foreach ($files as $file) : ?>
														<li class="<?= $currentFile === $file ? 'active' : '' ?>"><a href="?f=<?= base64_encode($file) ?>"><i class='fa fa-list'></i><?= $file; ?></a></li>
													<?php endforeach ?>
												<?php endif ?>
											</ul>
										</div>
									</div>
								</div>
								<div class="col-md-9">
									<div class="box box-info">
										<div class="box-header with-border">
											<?php if ($currentFile) : ?>
												<a href="?dl=<?= base64_encode($currentFile) ?>" class="btn btn-social btn-flat btn-success btn-sm" title="Unduh file log"><i class="fa fa-download"></i> Unduh</a>
												<?php if ($this->CI->cek_hak_akses_url('u')): ?>
													<a href="#" data-href="?del=<?= base64_encode($currentFile) ?>" class="btn btm-social btn-flat btn-danger btn-sm" title="Hapus log file" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash-o"></i>Hapus log file</a>
												<?php endif; ?>
											<?php endif ?>
										</div>
										<div class="box-body">
											<div class="row">
												<div class="col-sm-12">
													<div class="dataTables_wrapper form-inline dt-bootstrap no-footer">
														<div class="row">
															<div class="col-sm-12">
																<div class="table-responsive">
																	<?php if (is_null($logs)) : ?>
																		<div>
																			<br><br>
																			<strong>File log lebig dari 500 Mb, silahkan unduh.</strong>
																		</div>
																	<?php else : ?>
																		<div class="table-responsive">
																			<table id="tabel-logs" class="table table-bordered dataTable table-hover">
																				<thead class="bg-gray">
																					<tr>
																						<th width="5%">Level</th>
																						<th width="15%">Tanggal</th>
																						<th>Pesan</th>
																					</tr>
																				</thead>
																				<tbody>
																					<?php foreach ($logs as $key => $log) : ?>
																						<tr>
																							<td><h6><span class="label label-<?= $log['class'] ?>"><?= $log['level'] ?></span></h6></td>
																							<td><?= $log['date'] ?></td>
																							<td class="text">
																								<?php if (array_key_exists("extra", $log)) : ?>
																									<a class="pull-right btn btn-primary btn-xs" data-toggle="collapse" href="#collapse<?= $key ?>" aria-expanded="false" aria-controls="collapse<?= $key ?>">
																										<span class="glyphicon glyphicon-search"></span>
																									</a>
																								<?php endif; ?>
																								<?= $log['content']; ?>
																								<?php if (array_key_exists("extra", $log)) : ?>
																									<div class="collapse" id="collapse<?= $key ?>">
																										<?= $log['extra'] ?>
																									</div>
																								<?php endif; ?>
																							</td>
																						</tr>
																					<?php endforeach ?>
																				</tbody>
																			</table>
																		</div>
																	<?php endif ?>
																</div>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div id="ekstensi" class="tab-pane fade in">
							<?php if ($mysql['sudah_ok']) : ?>
								<div class="alert alert-success" role="alert">
									<p>Versi MYSQL terpasang <?= $mysql['versi'] ?> sudah memenuhi syarat.</p>
								</div>
							<?php else : ?>
								<div class="alert alert-danger" role="alert">
									<p>Versi MYSQL terpasang <?= $mysql['versi'] ?> tidak memenuhi syarat.</p>
									<p>Update versi MYSQL supaya minimal <?= $mysql['versi_minimal'] ?>.</p>
								</div>
							<?php endif; ?>
							<?php if ($php['sudah_ok']) : ?>
								<div class="alert alert-success" role="alert">
									<p>Versi PHP terpasang <?= $php['versi'] ?> sudah memenuhi syarat.</p>
								</div>
							<?php else : ?>
								<div class="alert alert-danger" role="alert">
									<p>Versi PHP terpasang <?= $php['versi'] ?> tidak memenuhi syarat.</p>
									<p>Update versi PHP supaya minimal <?= $php['versi_minimal'] ?>.</p>
								</div>
							<?php endif; ?>
							<?php if (!$ekstensi['lengkap']) : ?>
								<div class="alert alert-danger" role="alert">
									<p>Ada beberapa ekstensi PHP wajib yang tidak tersedia di sistem anda.
										Karena itu, mungkin ada fungsi yang akan bermasalah.</p>
									<p>Aktifkan ekstensi PHP yang belum ada di sistem anda.</p>
								</div>
							<?php else : ?>
								<p>
									Semua ekstensi PHP yang diperlukan sudah aktif di sistem anda.
								</p>
							<?php endif; ?>
							<?php foreach ($ekstensi['ekstensi'] as $key => $value) : ?>
								<div class="form-group">
									<div class="input-group col-xs-3">
										<span><?= $key ?></span>
										<span class="input-group-btn">
											<button class="btn <?= $value ? 'btn-success' : 'btn-danger' ?>" type="button"><i class="fa fa-<?= $value ? 'check' : 'times' ?> fa-lg"></i></button>
										</span>
									</div>
								</div>
							<?php endforeach; ?>
						</div>
						<div id="info_sistem" class="tab-pane fade in">
							<?php
							ob_start();
							phpinfo();
							$phpinfo = array('phpinfo' => array());
							if (preg_match_all('#(?:<h2>(?:<a name=".*?">)?(.*?)(?:</a>)?</h2>)|(?:<tr(?: class=".*?")?><t[hd](?: class=".*?")?>(.*?)\s*</t[hd]>(?:<t[hd](?: class=".*?")?>(.*?)\s*</t[hd]>(?:<t[hd](?: class=".*?")?>(.*?)\s*</t[hd]>)?)?</tr>)#s', ob_get_clean(), $matches, PREG_SET_ORDER)) :
								foreach ($matches as $match) :
									if (strlen($match[1])) :
										$phpinfo[$match[1]] = array();
									elseif (isset($match[3])) :
										$phpinfo[end(array_keys($phpinfo))][$match[2]] = isset($match[4]) ? array($match[3], $match[4]) : $match[3];
									else :
										$phpinfo[end(array_keys($phpinfo))][] = $match[2];
									endif;
								endforeach;
							?>
								<?php $i = 0; ?>
								<?php foreach ($phpinfo as $name => $section) : ?>
									<?php $i++; ?>
									<?php if ($i == 1) : ?>
										<div class='table-responsive'>
											<table class='table table-bordered dataTable table-hover'>
											<?php else : ?>
												<h3><?= $name ?></h3>
												<div class='table-responsive'>
													<table class='table table-bordered dataTable table-hover'>
													<?php endif ?>
													<?php foreach ($section as $key => $val) : ?>
														<?php if (is_array($val)) : ?>
															<tr>
																<td class="col-md-4 info"><?= $key ?></td>
																<td><?= $val[0] ?></td>
																<td><?= $val[1] ?></td>
															</tr>
														<?php elseif (is_string($key)) : ?>
															<tr>
																<td class="col-md-4 info"><?= $key ?></td>
																<td colspan='2'><?= $val ?></td>
															</tr>
														<?php else : ?>
															<tr>
																<td class="btn-primary" colspan='3'><?= $val ?></td>
															</tr>
														<?php endif; ?>
													<?php endforeach; ?>
													</table>
												</div>
											<?php endforeach; ?>
										<?php endif; ?>
										</div>
						</div>
					</div>
				</div>
			</div>
	</section>
</div>
<?php $this->load->view('global/confirm_delete'); ?>
<script>
	$(function() {
		$('#tabel-logs').DataTable({
			"processing": true,
			"autoWidth": false,
			'pageLength': 10,
			"order": [[1, "desc"]],
			"columnDefs": [ {
				"targets": [0, 2],
				"orderable": false
			}]
		});
	});
</script>