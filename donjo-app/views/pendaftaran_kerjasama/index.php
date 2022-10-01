<div class="content-wrapper">
	<section class="content-header">
		<h1>Pendaftaran Kerjasama</h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('hom_sid') ?>"><i class="fa fa-home"></i> Home</a></li>
			<li class="active">Pendaftaran Kerjasama</li>
		</ol>
	</section>
	<section class="content" id="maincontent">
		<?php if (null === $response) : ?>
            <div class="box box-danger">
                <div class="box-header with-border">
                    <i class="icon fa fa-ban"></i>
                    <h3 class="box-title"><?= (! cek_koneksi_internet()) ? 'Tidak Terhubung Dengan Jaringan' : $this->session->error_status_langganan ?></h3>
                </div>
                <div class="box-body">
                    <div class="callout callout-danger">
                        <h5>Data Gagal Dimuat, Harap Periksa Jaringan Anda Telebih Dahulu.</h5>
                    </div>
                </div>
            </div>
        <?php else : ?>
		<?php if ($response->data->status_registrasi === 'menunggu verifikasi email') : ?>
			<div class="box box-info">
				<div class="box-header with-border">
					<i class="icon fa fa-ban"></i>
					<h3 class="box-title">Status Registrasi</h3>
				</div>
				<div class="box-body">
					<div class="callout callout-info">
						<h5>Silahkan cek email Anda untuk memverifikasi.</h5>
					</div>
				</div>
			</div>
		<?php elseif ($response->data->status_registrasi === 'menunggu verifikasi pendaftaran') : ?>
			<div class="box box-info">
				<div class="box-header with-border">
					<i class="icon fa fa-ban"></i>
					<h3 class="box-title">Status Registrasi</h3>
				</div>
				<div class="box-body">
					<div class="callout callout-info">
						<h5>Dokumen permohonan Desa anda sedang diperiksa oleh Pelaksana Layanan OpenDesa.</h5>
					</div>
				</div>
			</div>
		<?php endif ?>
		<div class="box box-info">
			<div class="box-header with-border">
				<i class="icon fa fa-info-circle"></i>
				<h3 class="box-title"><?= $response->message ?></h3>
			</div>
			<div class="box-body">
				<h5 class="text-bold">Rincian Pelanggan</h5>
				<div class="table-responsive">
					<table class="table table-bordered table-striped table-hover tabel-rincian">
						<tbody>
							<tr>
								<td width="20%">ID Pelanggan</td>
								<td width="1">:</td>
								<td><?= $response->data->id ?></td>
							</tr>
							<tr>
								<td>Status Registrasi</td>
								<td>:</td>
								<td><?= $response->data->status_langganan ?></td>
							</tr>
							<tr>
								<td>KODE <?= strtoupper($this->setting->sebutan_desa) ?></td>
								<td> : </td>
								<td><?= $response->data->desa->kode_desa ?></td>
							</tr>
							<tr>
								<td><?= strtoupper($this->setting->sebutan_desa) ?></td>
								<td> : </td>
								<td><?= "Desa {$response->data->desa->nama_desa}, Kecamatan {$response->data->desa->nama_kec}, Kabupaten {$response->data->desa->nama_kab}, Provinsi {$response->data->desa->nama_prov}" ?></td>
							</tr>
							<tr>
								<td>Domain Desa</td>
								<td> : </td>
								<td><?= $response->data->domain ?></td>
							</tr>
							<tr>
								<td>Nama Kontak</td>
								<td> : </td>
								<td><?= "{$response->data->nama_kontak} | {$response->data->no_hp_kontak}" ?></td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>
		<?php endif ?>
	</section>
</div>