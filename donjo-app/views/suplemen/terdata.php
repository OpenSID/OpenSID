<div class="content-wrapper">
	<section class="content-header">
		<h1>Profil Terdata Data Suplemen</h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('hom_sid')?>"><i class="fa fa-home"></i> Home</a></li>
      <li><a href="<?= site_url()?>suplemen?>"><i class="fa fa-home"></i> Data Suplemen</a></li>
			<li class="active">Profil Data Terdata Suplemen</li>
		</ol>
	</section>
	<section class="content" id="maincontent">
		<div class="row">
			<div class="col-md-9">
				<div class="box box-info">
          <div class="box-header with-border">
						<a href="<?= site_url()?>suplemen?>" class="btn btn-social btn-flat btn-info btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-arrow-circle-left"></i> Kembali Ke Data Suplemen</a>
					</div>
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

