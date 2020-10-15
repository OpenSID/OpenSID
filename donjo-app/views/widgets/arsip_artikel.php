<!-- widget Arsip Artikel -->

<style type="text/css">
	#arsip_artikel .nav > li.active > a { color: green }
	#arsip_artikel img { width: 30%; margin:0 6px 4px 0; float: left;}
	#arsip_artikel td { padding-bottom: 2px; }
</style>
<div class="box box-primary box-solid">
	<div class="box-header">
		<h3 class="box-title"><a href="<?= site_url("arsip")?>"><i class="fa fa-archive"></i> Arsip Artikel</a></h3>
	</div>
	<div id="arsip_artikel" class="box-body">
		<ul class="nav nav-tabs">
			<li class="active"><a data-toggle="tab" href="#terkini">Terkini</a></li>
			<li><a data-toggle="tab" href="#populer">Populer</a></li>
			<li><a data-toggle="tab" href="#acak">Acak</a></li>
		</ul>
		<div class="tab-content">

			<?php foreach (array('terkini' => 'arsip_terkini', 'populer' => 'arsip_populer', 'acak' => 'arsip_acak') as $jenis => $jenis_arsip) : ?>
				<div id="<?= $jenis ?>" class="tab-pane fade in <?php ($jenis == 'terkini') and print('active') ?>">
					<table>
						<?php foreach ($$jenis_arsip as $arsip): ?>
							<tr>
								<td>
									<a href="<?= site_url('artikel/'.buat_slug($arsip))?>">
										<?php if (is_file(LOKASI_FOTO_ARTIKEL."kecil_$arsip[gambar]")): ?>
											<img class="img-fluid img-thumbnail" src="<?= base_url(LOKASI_FOTO_ARTIKEL."sedang_$arsip[gambar]")?>"/>
										<?php else: ?>
											<img class="img-fluid img-thumbnail" src="<?= base_url("assets/images/404-image-not-found.jpg")?>"/>
										<?php endif;?>
										<div align="justify">
											<small><span class="meta_date"><?= tgl_indo($arsip['tgl_upload']) ?> | <i class="fa fa-eye"></i> <?= hit($arsip['hit']) ?></span><br><font color="green"><?= $arsip['judul']?></font></small>
										</div>
									</a>
								</td>
							</tr>
						<?php endforeach; ?>
					</table>
				</div>
			<?php endforeach ?>
		</div>
	</div>
</div>
