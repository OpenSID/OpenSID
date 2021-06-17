<?php
/**
 * File ini:
 *
 * View di Modul Identitas Desa
 *
 * donjo-app/views/identitas_desa/form.php
 *
 */

/**
 *
 * File ini bagian dari:
 *
 * OpenSID
 *
 * Sistem informasi desa sumber terbuka untuk memajukan desa
 *
 * Aplikasi dan source code ini dirilis berdasarkan lisensi GPL V3
 *
 * Hak Cipta 2009 - 2015 Combine Resource Institution (http://lumbungkomunitas.net/)
 * Hak Cipta 2016 - 2020 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 *
 * Dengan ini diberikan izin, secara gratis, kepada siapa pun yang mendapatkan salinan
 * dari perangkat lunak ini dan file dokumentasi terkait ("Aplikasi Ini"), untuk diperlakukan
 * tanpa batasan, termasuk hak untuk menggunakan, menyalin, mengubah dan/atau mendistribusikan,
 * asal tunduk pada syarat berikut:
 *
 * Pemberitahuan hak cipta di atas dan pemberitahuan izin ini harus disertakan dalam
 * setiap salinan atau bagian penting Aplikasi Ini. Barang siapa yang menghapus atau menghilangkan
 * pemberitahuan ini melanggar ketentuan lisensi Aplikasi Ini.
 *
 * PERANGKAT LUNAK INI DISEDIAKAN "SEBAGAIMANA ADANYA", TANPA JAMINAN APA PUN, BAIK TERSURAT MAUPUN
 * TERSIRAT. PENULIS ATAU PEMEGANG HAK CIPTA SAMA SEKALI TIDAK BERTANGGUNG JAWAB ATAS KLAIM, KERUSAKAN ATAU
 * KEWAJIBAN APAPUN ATAS PENGGUNAAN ATAU LAINNYA TERKAIT APLIKASI INI.
 *
 * @package	OpenSID
 * @author	Tim Pengembang OpenDesa
 * @copyright	Hak Cipta 2009 - 2015 Combine Resource Institution (http://lumbungkomunitas.net/)
 * @copyright	Hak Cipta 2016 - 2020 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license	http://www.gnu.org/licenses/gpl.html	GPL V3
 * @link 	https://github.com/OpenSID/OpenSID
 */
?>
<div class="content-wrapper">
	<section class="content-header">
		<h1>Identitas <?= $desa; ?></h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('hom_sid'); ?>"><i class="fa fa-home"></i> Home</a></li>
			<li><a href="<?= site_url('identitas_desa'); ?>"></i> Identitas <?= $desa; ?></a></li>
			<li class="active">Ubah Identitas <?= $desa; ?></li>
		</ol>
	</section>
	<section class="content" id="maincontent">
		<div class="row">
			<form id="mainform" action="<?= $form_action; ?>" method="POST" enctype="multipart/form-data" class="form-horizontal" id="validasi">
				<div class="col-md-3">
					<div class="box box-primary">
						<div class="box-body box-profile">
							<img class="profile-user-img img-responsive img-circle" src="<?= gambar_desa($main['logo']); ?>" alt="Logo">
							<br/>
							<p class="text-center text-bold">Lambang <?= $desa; ?></p>
							<p class="text-muted text-center text-red">(Kosongkan, jika logo tidak berubah)</p>
							<br/>
							<div class="input-group input-group-sm">
								<input type="text" class="form-control" id="file_path" >
								<input type="file" class="hidden" id="file" name="logo">
								<input type="hidden" name="old_logo" value="<?= $main['logo']; ?>">
								<span class="input-group-btn">
									<button type="button" class="btn btn-info btn-flat" id="file_browser"><i class="fa fa-search"></i> Browse</button>
								</span>
							</div>
						</div>
					</div>
					<div class="box box-primary">
						<div class="box-body box-profile">
							<img class="img-responsive" src="<?= gambar_desa($main['kantor_desa'], TRUE); ?>" alt="Kantor <?= $desa; ?>">
							<br/>
							<p class="text-center text-bold">Kantor <?= $desa; ?></p>
							<p class="text-muted text-center text-red">(Kosongkan, jika kantor <?= $desa; ?> tidak berubah)</p>
							<br/>
							<div class="input-group input-group-sm">
								<input type="text" class="form-control" id="file_path2" >
								<input type="file" class="hidden" id="file2" name="kantor_desa">
								<input type="hidden" name="old_kantor_desa" value="<?= $main['kantor_desa']; ?>">
								<span class="input-group-btn">
									<button type="button" class="btn btn-info btn-flat" id="file_browser2"><i class="fa fa-search"></i> Browse</button>
								</span>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-9">
					<div class="box box-primary">
						<div class="box-header with-border">
							<a href="<?= site_url('identitas_desa'); ?>" class="btn btn-social btn-flat btn-info btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Kembali Ke Data <?= $desa; ?>"><i class="fa fa-arrow-circle-o-left"></i> Kembali Ke Data Identitas <?= $desa; ?></a>
						</div>
						<div class="box-body">
							<div class="form-group">
								<label class="col-sm-3 control-label" for="nama">Nama <?= $desa; ?></label>
								<div class="col-sm-8">
									<select id="pilih_desa" name="pilih_desa" class="form-control input-sm select-nama-desa" data-placeholder="<?= $main["nama_desa"]; ?> - <?= $main["nama_kecamatan"]; ?> - <?= $main["nama_kabupaten"]; ?> - <?= $main["nama_propinsi"]; ?>" data-token="<?= config_item('token_tracksid')?>" data-tracker='<?= (ENVIRONMENT == 'development') ? $this->setting->dev_tracker : $this->setting->tracker ?>'></select>
								</div>
								<input type="hidden" id="nama_desa" name="nama_desa" value="<?= $main["nama_desa"]; ?>">
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label" for="kode_desa">Kode <?= $desa; ?></label>
								<div class="col-sm-2">
									<input readonly id="kode_desa" name="kode_desa" class="form-control input-sm" type="text" placeholder="Kode <?= $desa; ?>" value="<?= $main["kode_desa"]; ?>" ></input>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label" for="kode_pos">Kode Pos <?= $desa; ?></label>
								<div class="col-sm-2">
									<input id="kode_pos" name="kode_pos" class="form-control input-sm number" minlength="5" maxlength="5" type="text" placeholder="Kode Pos <?= $desa; ?>" value="<?= $main["kode_pos"]; ?>"></input>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label" for="nama_kepala_desa">Kepala <?= $desa; ?></label>
								<div class="col-sm-8">
									<input id="nama_kepala_desa" name="nama_kepala_desa" class="form-control input-sm nama required" maxlength="50" type="text" placeholder="Kepala <?= $desa; ?>" value="<?= $main["nama_kepala_desa"]?>"></input>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label" for="nip_kepala_desa">NIP Kepala <?=$desa; ?></label>
								<div class="col-sm-8">
									<input id="nip_kepala_desa" name="nip_kepala_desa" class="form-control input-sm nomor_sk" maxlength="50" type="text" placeholder="NIP Kepala <?= $desa; ?>" value="<?= $main["nip_kepala_desa"]; ?>"></input>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label" for="alamat_kantor">Alamat Kantor <?= $desa; ?></label>
								<div class="col-sm-8">
									<textarea id="alamat_kantor" name="alamat_kantor" class="form-control input-sm alamat required" maxlength="100" placeholder="Alamat Kantor <?= $desa; ?>" rows="3" style="resize:none;"><?= $main["alamat_kantor"]; ?></textarea>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label" for="email_desa">E-Mail <?= $desa; ?></label>
								<div class="col-sm-8">
									<input id="email_desa" name="email_desa" class="form-control input-sm email" maxlength="50" type="text" placeholder="E-Mail <?= $desa; ?>" value="<?= $main["email_desa"]?>"></input>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label" for="telepon">Telpon <?= $desa; ?></label>
								<div class="col-sm-8">
									<input id="telepon" name="telepon" class="form-control input-sm bilangan" type="text" maxlength="15" placeholder="Telpon <?= $desa; ?>" value="<?= $main["telepon"]; ?>"></input>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label" for="website">Website <?= $desa; ?></label>
								<div class="col-sm-8">
									<input id="website" name="website" class="form-control input-sm url" maxlength="50" type="text" placeholder="Website <?= $desa; ?>" value="<?= $main["website"]; ?>"></input>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label" for="nama_kecamatan">Nama <?= $kecamatan; ?></label>
								<div class="col-sm-8">
									<input readonly id="nama_kecamatan" name="nama_kecamatan" class="form-control input-sm required" type="text" placeholder="Nama <?= $kecamatan; ?>" value="<?= $main["nama_kecamatan"]; ?>"></input>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label" for="kode_kecamatan">Kode <?= $kecamatan; ?></label>
								<div class="col-sm-2">
									<input readonly id="kode_kecamatan" name="kode_kecamatan" class="form-control input-sm required" type="text" placeholder="Kode <?= $kecamatan; ?>" value="<?= $main['kode_kecamatan']; ?>" ></input>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label" for="nama_kecamatan">Nama <?= ucwords($this->setting->sebutan_camat)?></label>
								<div class="col-sm-8">
									<input id="nama_kepala_camat" name="nama_kepala_camat" class="form-control input-sm nama required" maxlength="50" type="text" placeholder="Nama <?= ucwords($this->setting->sebutan_camat); ?>" value="<?= $main["nama_kepala_camat"]; ?>"></input>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label" for="nip_kepala_camat">NIP <?= ucwords($this->setting->sebutan_camat); ?></label>
								<div class="col-sm-4">
									<input id="nip_kepala_camat" name="nip_kepala_camat" class="form-control input-sm nomor_sk" maxlength="50" type="text" placeholder="NIP <?= ucwords($this->setting->sebutan_camat)?>" value="<?= $main["nip_kepala_camat"]; ?>"></input>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label" for="nama_kabupaten">Nama <?= $kabupaten; ?></label>
								<div class="col-sm-8">
									<input readonly id="nama_kabupaten" name="nama_kabupaten" class="form-control input-sm required" type="text" placeholder="Nama <?= $kabupaten; ?>" value="<?= $main["nama_kabupaten"]; ?>"></input>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label" for="kode_kabupaten">Kode <?= $kabupaten; ?></label>
								<div class="col-sm-2">
									<input readonly id="kode_kabupaten" name="kode_kabupaten" class="form-control input-sm required" type="text" placeholder="Kode <?= $kabupaten; ?>" value="<?= $main["kode_kabupaten"]; ?>"></input>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label" for="nama_propinsi">Nama Provinsi</label>
								<div class="col-sm-8">
									<input readonly id="nama_propinsi" name="nama_propinsi" class="form-control input-sm required" type="text" placeholder="Nama Propinsi" value="<?= $main["nama_propinsi"]; ?>"></input>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label" for="kode_propinsi">Kode Provinsi</label>
								<div class="col-sm-2">
									<input readonly id="kode_propinsi" name="kode_propinsi" class="form-control input-sm required" type="text" placeholder="Kode Provinsi" value="<?= $main["kode_propinsi"]; ?>"></input>
								</div>
							</div>
						</div>
						<div class='box-footer'>
							<button type='reset' class='btn btn-social btn-flat btn-danger btn-sm' ><i class='fa fa-times'></i> Batal</button>
							<button type='submit' class='btn btn-social btn-flat btn-info btn-sm pull-right'><i class='fa fa-check'></i> Simpan</button>
						</div>
					</div>
				</div>
			</form>
		</div>
	</section>
</div>

<script>
$(document).ready(function()
{
	var tracker_host = '<?= (ENVIRONMENT == 'development') ? $this->setting->dev_tracker : $this->setting->tracker ?>';

	// Ambil Nama dan Kode Wilayah dari API Server
	$('[name="pilih_desa"]').change(function(){
		$.ajax({
        type: 'GET',
        url: tracker_host + '/index.php/api/wilayah/ambildesa?token=' + '<?= config_item("token_tracksid")?>' + '&id_desa=' + $(this).val(),
        dataType: 'json',
        success: function(data) {
					$('[name="nama_desa"]').val(data.KODE_WILAYAH[0].nama_desa);
				  $('[name="kode_desa"]').val(data.KODE_WILAYAH[0].kode_desa);
				  $('[name="nama_kecamatan"]').val(data.KODE_WILAYAH[0].nama_kec);
				  $('[name="kode_kecamatan"]').val(data.KODE_WILAYAH[0].kode_kec);
				  $('[name="nama_kabupaten"]').val(hapus_kab_kota(huruf_awal_besar(data.KODE_WILAYAH[0].nama_kab)));
				  $('[name="kode_kabupaten"]').val(data.KODE_WILAYAH[0].kode_kab);
				  $('[name="nama_propinsi"]').val(huruf_awal_besar(data.KODE_WILAYAH[0].nama_prov));
				  $('[name="kode_propinsi"]').val(data.KODE_WILAYAH[0].kode_prov);
        }
    });
	});

	function hapus_kab_kota(str) {
		return str.replace(/KAB |KOTA /gi, '');
	}

});
</script>
