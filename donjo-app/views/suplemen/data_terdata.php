<div class="content-wrapper">
	<section class="content-header">
		<h1>Data Terdata Suplemen</h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('hom_sid')?>"><i class="fa fa-home"></i> Home</a></li>
      <li><a href="<?= site_url()?>suplemen/rincian/1/<?= $suplemen['id']?>"><i class="fa fa-home"></i> Rincian Suplemen</a></li>
			<li class="active">Data Terdata Suplemen</li>
		</ol>
	</section>
	<section class="content" id="maincontent">
		<div class="row">
			<div class="col-md-12">
				<div class="box box-info">
          <div class="box-header with-border">
						<a href="<?= site_url()?>suplemen/rincian/1/<?= $suplemen['id']?>" class="btn btn-social btn-flat btn-info btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-arrow-circle-left"></i> Kembali Ke Rincian Suplemen</a>
					</div>
					<div class="box-header with-border">
						<h3 class="box-title">Rincian Suplemen</h3>
					</div>
					<div class="box-body ">
						<table class="table table-bordered" >
							<tbody>
								<tr>
									<td style="padding-top : 10px;padding-bottom : 10px;width:20%;" >Nama Suplemen</td>
									<td> : <?= strtoupper($suplemen["nama"])?></td>
								</tr>
								<tr>
									<td style="padding-top : 10px;padding-bottom : 10px;" >Sasaran</td>
									<td> : <?= $sasaran[$suplemen["sasaran"]]?></td>
								</tr>
                <tr>
									<td style="padding-top : 10px;padding-bottom : 10px;" >Keterangan</td>
									<td> : <?= $suplemen["keterangan"]?></td>
								</tr>
							</tbody>
						</table>
					</div>
					<div class="box-header with-border">
						<h3 class="box-title">Data Terdata</h3>
					</div>
					<div class="box-body">
						<div class="table-responsive">
              <table class="table table-bordered" >
                <tbody>
                  <tr>
                    <?php if ($suplemen["sasaran"] == 1): ?>
                      <td style="padding-top : 10px;padding-bottom : 10px;width:20%;" >NIK /Nama</td>
                    <?php elseif ($suplemen["sasaran"] == 2): ?>
                      <td style="padding-top : 10px;padding-bottom : 10px;width:20%;" >No. KK / Nama KK</td>
                    <?php endif; ?>
                    <td> <?= $terdata["terdata_nama"]." / ".$terdata["terdata_info"]?></td>
                  </tr>
                  <?php if ($suplemen["sasaran"] == 1): ?>
                    <tr>
                      <td style="padding-top : 10px;padding-bottom : 10px;width:20%;" >Alamat</td>
                      <td>
                        <?= $individu['alamat_wilayah']; ?>
                      </td>
                    </tr>
                    <tr>
                      <td style="padding-top : 10px;padding-bottom : 10px;width:20%;" >Tempat Tanggal Lahir (Umur)</td>
                      <td>
                        <?= $individu['tempatlahir']?> <?= tgl_indo($individu['tanggallahir'])?> (<?= $individu['umur']?> Tahun)
                      </td>
                    </tr>
                    <tr>
                      <td style="padding-top : 10px;padding-bottom : 10px;width:20%;" >Pendidikan</td>
                      <td>
                        <?= $individu['pendidikan']?>
                      </td>
                    </tr>
                    <tr>
                      <td style="padding-top : 10px;padding-bottom : 10px;width:20%;" >Warganegara / Agama</td>
                      <td>
                        <?= $individu['warganegara']?> / <?= $individu['agama']?>
                      </td>
                    </tr>
                  <?php elseif ($suplemen["sasaran"] == 2): ?>
                    <tr>
                      <td style="padding-top : 10px;padding-bottom : 10px;width:20%;" >Alamat Keluarga</td>
                      <td>
                        <?= $individu['alamat_wilayah']; ?>
                      </td>
                    </tr>
                    <tr>
                      <td style="padding-top : 10px;padding-bottom : 10px;width:20%;" >Tempat Tanggal Lahir (Umur) KK</td>
                      <td>
                        <?= $individu['tempatlahir']?> <?= tgl_indo($individu['tanggallahir'])?> (<?= $individu['umur']?> Tahun)
                      </td>
                    </tr>
                    <tr>
                      <td style="padding-top : 10px;padding-bottom : 10px;width:20%;" >Pendidikan KK</td>
                      <td>
                        <?= $individu['pendidikan']?>
                      </td>
                    </tr>
                    <tr>
                      <td style="padding-top : 10px;padding-bottom : 10px;width:20%;" >Warganegara / Agama KK</td>
                      <td>
                        <?= $individu['warganegara']?> / <?= $individu['agama']?>
                      </td>
                    </tr>
                  <?php endif; ?>
                  <tr>
                    <td style="padding-top : 10px;padding-bottom : 10px;" >Keterangan</td>
                    <td> <?= $terdata["keterangan"]?></td>
                  </tr>
                </tbody>
              </table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>

