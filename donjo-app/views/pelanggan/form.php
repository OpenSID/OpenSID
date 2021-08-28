<meta charset="utf-8" />
<link rel="stylesheet" href="<?= base_url()?>assets/css/vuetify.min.css">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, minimal-ui">
<div class="content-wrapper">
<section class="content-header">
	<h1>Info Layanan Pelanggan</h1>
	<ol class="breadcrumb">
		<li><a href="<?= site_url('hom_sid')?>"><i class="fa fa-home"></i> Home</a></li>
		<li class="active">Info Layanan Pelanggan</li>
	</ol>
</section>
<section class="content">
	<div class="box box-info">
		<div class="box-body">
			<div class="row">
				<div class="col-sm-12">
					<input type="hidden" id="id_desa" name="id_desa"/>
					<div id="error_msg" class="alert alert-danger" style="display: none;">
						<h5>Data gagal dimuat, Harap diperiksa di bawah ini : <br></h5>
						<h5>* API Key tidak terotentifikasi. Periksa API Key di Pengaturan Aplikasi. <br></h5>
						<h5>* Nama / Kode Desa tidak terdaftar / salah. Periksa Nama Desa yang didaftarkan pada Halaman Pelanggan di pantau.opensid.my.id. <br></h5>
					</div>
					<div id="isi" class="row">
						<div class="col-lg-3 col-xs-6">
							<div class="small-box bg-blue">
								<div id="jenis" class="inner" style="display: none;">
									<h3><span class="jenis_pelanggan"></span></h3>
									<p>JENIS LANGGANAN</p>
								</div>
								<div id="jenis1" class="inner" style="display: none;">
									<h4><span class="jenis_pelanggan"></span></h4>
									<p>JENIS LANGGANAN</p>
								</div>
								<div class="icon">
									<i class="ion ion-card"></i>
								</div>
							</div>
						</div>
						<div class="col-lg-3 col-xs-6">
							<div class="small-box bg-yellow">
								<div class="inner">
									<h3><span class="status_langganan"></span></h3>
									<p>STATUS PELANGGAN</p>
								</div>
								<div class="icon">
									<i class="ion-person-add"></i>
								</div>
							</div>
						</div>
						<div class="col-lg-3 col-xs-6">
							<div class="small-box bg-green">
								<div class="inner">
									<h3><span class="tgl_mulai"></span></h3>
									<p>MULAI BERLANGGANAN</p>
								</div>
								<div class="icon">
									<i class="ion ion-unlocked"></i>
								</div>
							</div>
						</div>
						<div class="col-lg-3 col-xs-6">
							<div class="small-box bg-red">
								<div class="inner">
									<h3><span class="tgl_akhir"></span></h2>
										<p>AKHIR BERLANGGANAN</p>
									</div>
									<div class="icon">
										<i class="ion ion-locked"></i>
									</div>
								</div>
							</div>
						</div>
						<div id="isi1" class="row">
							<div class="col-md-12">
								<div class="row">
									<div class="col-md-12">
										<!-- Tabel Data -->
										<div class="table-responsive">
											<table class="table table-bordered table-striped dataTable table-hover">
												<tbody>
													<tr>
														<tr>
															<th class="horizontal">ID PELANGGAN</th>
															<td> <span class="id"></span></td>
														</tr>
														<tr>
															<th class="horizontal">DOMAIN</th>
															<td> <span class="domain"></span></td>
														</tr>
														<tr>
															<th class="horizontal">KODE <?=strtoupper($this->setting->sebutan_desa)?></th>
															<td> <span class="kode_desa"></span></td>
														</tr>
														<tr>
															<th class="horizontal"><?=strtoupper($this->setting->sebutan_desa)?></th>
															<td> <?=ucwords($this->setting->sebutan_desa)?> <span class="nama_desa"></span></td>
														</tr>
														<tr>
															<th class="horizontal"><?=strtoupper($this->setting->sebutan_kecamatan)?>/<?=strtoupper($this->setting->sebutan_kabupaten)?>/PROVINSI</th>
															<td> <?=ucwords($this->setting->sebutan_kecamatan)?> <span class="nama_kec"></span> <?=ucwords($this->setting->sebutan_kabupaten)?> <span class="nama_kab"></span> Provinsi <span class="nama_prov"></span></td>
														</tr>
														<tr>
															<th class="horizontal">NAMA KONTAK</th>
															<td> <span class="nama"></span></td>
														</tr>
														<tr>
															<th class="horizontal">NO. HP</th>
															<td> <span class="no_hp"></span></td>
														</tr>
														<tr>
															<th class="horizontal">EMAIL</th>
															<td> <span class="email"></td>
														</tr>
														<tr>
															<th class="horizontal">PELAKSANA</th>
															<td> <span class="pelaksana"></td>
														</tr>
														<tr>
															<th class="horizontal">API KEY</th>
															<td> <span class="token"></td>
														</tr>
													</tr>
												</tbody>
											</table>
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
<script src="<?= base_url()?>assets/js/axios.min.js"></script>
<script>
var tracker_host = '<?= (ENVIRONMENT == 'development') ? $this->setting->dev_tracker : $this->setting->tracker ?>';
async function getPelanggan() {
	await axios
	.get(tracker_host + '/index.php/api/pelanggan/customer?token=<?=$this->setting->api_key_opensid?>')
	.then(response => {
		let info = response.data.PELANGGAN_PREMIUM[0];
		let id = info.id;
		let jenis_pelanggan =  <?= strtoupper(json_encode($jenis_pelanggan))?>[info.jenis_langganan];
		let status_langganan = <?= strtoupper(json_encode($status_langganan))?>[info.status_langganan];
		let pelaksana = <?= json_encode($pelaksana)?>[info.pelaksana];
		let nama = info.nama;
		let domain = info.domain;
		let no_hp = info.no_hp;
		let tgl_mulai = new Date(info.tgl_mulai).toLocaleDateString('es-CL');
		let tgl_akhir = new Date(info.tgl_akhir).toLocaleDateString('es-CL');
		let email = info.email;
		let token = info.token;
		let id_desa = info.id_desa;
		let loading = true;
		let errored = false;

		let attributes = ['id','nama','jenis_pelanggan','status_langganan','domain','no_hp','tgl_mulai','tgl_akhir','pelaksana', 'email', 'token'];
		attributes.forEach(function (attr) {
			$(`.${attr}`).html(eval(attr));
		})

		$('[name="id_desa"]').val(info.id_desa);

		if (info.jenis_langganan == 3)
		{
			$('#jenis').show();
		}
		else
		{
			$('#jenis1').show();
		}

		getDesa();

	})
	.catch(error => {
		console.log(error)
		this.errored = true
		$('#error_msg').show();
		$('#isi').hide();
		$('#isi1').hide();
	})
	.finally(() => this.loading = false)

}
getPelanggan();

async function getDesa() {
	var input = document.getElementById("id_desa").value;

	let response = await axios.get(tracker_host + '/index.php/api/wilayah/desa?token=<?=$this->setting->api_key_opensid?>&id_desa=' + input);
	let infodesa = response.data.KODE_WILAYAH[0];
	let nama_desa = infodesa.nama_desa;
	let kode_desa = infodesa.kode_desa;
	let nama_kec = infodesa.nama_kecamatan;
	let kode_kec = infodesa.kode_kecamatan;
	let nama_kab = infodesa.nama_kabupaten;
	let kode_kab = infodesa.kode_kabupaten;
	let nama_prov = infodesa.nama_provinsi;
	let kode_prov= infodesa.kode_provinsi;

	let attributes = ['nama_desa','kode_desa','nama_kec','kode_kec','nama_kab','kode_kab','nama_prov','kode_prov'];
	attributes.forEach(function (attr) {
		$(`.${attr}`).html(eval(attr));
	})
}
</script>
