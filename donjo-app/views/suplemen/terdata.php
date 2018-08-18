<div class="content-wrapper">
	<section class="content-header">
		<h1>Profil Terdata Data Suplemen</h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('hom_desa')?>"><i class="fa fa-dashboard"></i> <?=ucwords($this->setting->sebutan_desa)?></a></li>
			<li class="active">Rincian Data Suplemen</li>
		</ol>
	</section>
	<section class="content" id="maincontent">
		<div class="row">
			<div class="col-md-3">
				<?php $this->load->view('suplemen/menu_kiri.php')?>
			</div>
			<div class="col-md-9">
				<div class="box box-info">
					<div class="box-header with-border">
						<h3 class="box-title">Profil Terdata Data Suplemen</h3>
					</div>
					<div class="box-body ">
						<table class="table table-bordered" >
							<tbody>
								<tr>
									<td style="padding-top : 10px;padding-bottom : 10px;width:20%;" >Nama</td>
									<td> : <?= strtoupper($profil["nama"])?></td>
								</tr>
								<tr>
									<td style="padding-top : 10px;padding-bottom : 10px;" >Keterangan</td>
									<td> : <?= $profil["ndesc"]?></td>
								</tr>
							</tbody>
						</table>
					</div>
					<div class="box-header with-border">
						<h3 class="box-title">Suplemen Yang Terdata</h3>
					</div>
					<div class="box-body">
						<div class="table-responsive">
							<table class="table table-bordered dataTable table-hover">
								<thead class="bg-gray disabled color-palette">
									<tr>
										<th>No</th>
										<th>Nama Suplemen</th>
										<th width="65%">Keterangan</th>
									</tr>
								</thead>
								<tbody>
									<?php
										$nomer = 0;
										foreach ($daftar_suplemen as $item):
											$nomer++;
									?>
										<tr>
											<td align="center" width="2"><?= $nomer; ?></td>
											<td><a href="<?= site_url('suplemen/rincian/1/'.$item["id"].'/')?>"><?= $item["nama"] ?></a></td>
											<td><?= $item["keterangan"];?></td>
										</tr>
									<?php endforeach; ?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>

