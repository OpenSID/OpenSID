<div class="content-wrapper">
	<section class="content-header">
		<h1>Formulir Penambahan Terdata</h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('hom_sid')?>"><i class="fa fa-home"></i> Home</a></li>
			<li><a href="<?= site_url('suplemen')?>"> Data Suplemen</a></li>
			<li><a href="<?= site_url()?>suplemen/rincian/1/<?= $suplemen['id']?>"> Rincian Data Suplemen</a></li>
			<li class="active">Formulir Penambahan Terdata</li>
		</ol>
	</section>
	<section class="content">
		<div class="row">
			<div class="col-md-12">
				<div class="box box-info">
					<div class="box-header with-border">
						<div class="col-md-12">
							<a href="<?= site_url('suplemen')?>" class="btn btn-social btn-flat btn-primary btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Kembali Ke Daftar Suplemen"><i class="fa fa-arrow-circle-o-left"></i> Kembali Ke Daftar Suplemen</a>
							<a href="<?= site_url()?>suplemen/rincian/1/<?= $suplemen['id']?>" class="btn btn-social btn-flat btn-info btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-arrow-circle-left"></i> Kembali Ke Rincian Data Suplemen</a>
						</div>
					</div>
					<div class="box-body">
						<div class="row">
							<div class="col-md-12">
								<div class="box-header with-border">
									<h3 class="box-title"Rincian Data Suplemen</h3>
								</div>
								<div class="box-body ">
									<table class="table table-bordered table-striped table-hover" >
										<tbody>
											<tr>
												<td style="padding-top : 10px;padding-bottom : 10px;width:20%;" >Nama Data</td>
												<td> : <?= strtoupper($suplemen["nama"])?></td>
											</tr>
											<tr>
												<td style="padding-top : 10px;padding-bottom : 10px;" >Sasaran Terdata</td>
												<td> : <?= $sasaran[$suplemen["sasaran"]]?></td>
											</tr>
											<tr>
												<td style="padding-top : 10px;padding-bottom : 10px;" >Keterangan</td>
												<td> : <?= $suplemen["keterangan"]?></td>
											</tr>
										</tbody>
									</table>
								</div>
							</div>
							<div class="col-sm-12">
								<div class="box-header with-border">
									<h3 class="box-title">Tambahkan Warga Terdata</h3>
								</div>
								<div class="box-body">
									<form action="" id="main" name="main" method="POST" class="form-horizontal">
										<?php if ($suplemen["sasaran"] == 1): ?>
											<div class="form-group" >
												<label class="col-sm-3 control-label required" for="terdata">NIK / Nama</label>
												<div class="col-sm-8">
													<select class="form-control select2" id="terdata" name="terdata" onchange="formAction('main')" style="width:100%;">
														<option value="">-- Silakan Masukan NIK / Nama --</option>
														<?php foreach ($list_sasaran as $item):
															if (strlen($item["id"])>0): ?>
																<option value="<?= $item['id']?>" <?php if ($individu['id']==$item['id']): ?>selected<?php endif; ?>>Nama : <?= $item['nama']." - ".$item['info']?></option>
															<?php endif;
														endforeach; ?>
													</select>
												</div>
											</div>
										<?php elseif ($suplemen["sasaran"] == 2): ?>
											<div class="form-group" >
												<label for="terdata" class="col-sm-3 control-label">No. KK / Nama KK</label>
												<div class="col-sm-7">
													<select class="form-control select2 required" id="terdata" name="terdata" onchange="formAction('main')" style="width:100%;">
														<option selected="selected">-- Silakan Masukan No. KK / Nama KK --</option>
														<?php foreach ($list_sasaran as $item):
															if (strlen($item["id"])>0): ?>
																<option value="<?= $item['id']?>" <?php if ($individu['id']==$item['id']): ?>selected<?php endif; ?>>Nama :<?= $item['nama']." - ".$item['info']?></option>
															<?php endif;
														endforeach; ?>
													</select>
												</div>
											</div>
										<?php endif; ?>
									</form>
									<div id="form-melengkapi-data-peserta">
										<form id="validasi" action="<?= $form_action?>/<?= $suplemen['id']?>" method="POST" enctype="multipart/form-data" class="form-horizontal">
											<div class="form-group">
												<label class="col-sm-3 control-label"></label>
												<div class="col-sm-8">
													 <input type="hidden" name="id_terdata" value="<?= $individu['id']?>" class="form-control input-sm required">
												 </div>
											</div>
											<?php if ($individu): ?>
												<?php include("donjo-app/views/suplemen/konfirmasi_terdata.php"); ?>
											<?php endif; ?>
											<div class="form-group">
												<label class="col-sm-3 control-label" for="keterangan">Keterangan</label>
												<div class="col-sm-8">
													 <textarea name="keterangan" id="keterangan" class="form-control input-sm" maxlength="100" placeholder="Keterangan" rows="3" style="resize:none;"></textarea>
												 </div>
											</div>
										</form>
									</div>
									<div class="box-footer">
										<div class="col-xs-12">
											<button type="reset" class="btn btn-social btn-flat btn-danger btn-sm"><i class="fa fa-times"></i> Batal</button>
											<button type="submit" class="btn btn-social btn-flat btn-info btn-sm pull-right" onclick="$('#'+'validasi').submit();"><i class="fa fa-check"></i> Simpan</button>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>

