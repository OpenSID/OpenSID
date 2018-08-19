<div class="content-wrapper">
	<section class="content-header">
		<h1>Panduan Data Persil <?=ucwords($this->setting->sebutan_desa)?> <?= $desa["nama_desa"];?></h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('hom_sid')?>"><i class="fa fa-home"></i> Home</a></li>
			<li><a href="<?= site_url('data_persil/clear')?>"> Daftar Persil</a></li>
			<li class="active">Panduan Persil</li>
		</ol>
	</section>
	<section class="content" id="maincontent">
		<form id="mainform" name="mainform" action="" method="post">
			<div class="row">
				<div class="col-md-3">
          <?php $this->load->view('data_persil/menu_kiri.php')?>
				</div>
				<div class="col-md-9">
					<div class="box box-info">
						<div class="box-body">
							<h4>Keterangan</h4>
							<p><strong>Modul Data Persil</strong> adalah modul untuk pengelolaan data dokumen tentang kepemilikan lahan.</p>
							<h4>Panduan</h4>
							<p>Cara menyimpan/memperbarui data Persil adalah dengan mengisikan formulir yang terdapat dari menu Tulis Data Persil Baru:</p>
							<p>
								<ol>
									<li>Kolom <strong>Data Pemilik</strong>
										<p>Tuliskan NIK atau Nama Pemilik Persil. Sistem akan memberikan pilihan sesuai dengan data yang tersimpan di SID</p>
									</li>
									<li>Kolom <strong>Nomor Persil</strong>
										<p>Nomor Persil wajib diisi</p>
									</li>
									<li>Kolom <strong>Keterangan Surat</strong>
										<p>Pilih sesuai dengan jenis surat persil</p>
									</li>
									<li>Kolom <strong>Luas Tanah</strong>
										<p>Isikan dengan menggunakan angka saja. Bila data luasan tidak bulat, gunakan tanda titik(.) untuk menggantikan data koma pada nilai desimal. Nilai luasan dalam satuan meter persegi (m<sup>2</sup>)</p>
									</li>
									<li>Kolom <strong>Nomor SPPT PBB</strong>
										<p>Isikan sesuai dengan data nomor SPPT PBB atas persil tersebut</p>
									</li>
								</ol>
							</p>
						</div>
					</div>
				</div>
			</div>
		</form>
	</section>
</div>

