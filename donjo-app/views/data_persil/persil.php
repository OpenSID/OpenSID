<?php
/**
 * File ini:.
 *
 * View untuk daftar Persil
 *
 * /donjo-app/views/data_persil/persil.php
 */

/*
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

 * Pemberitahuan hak cipta di atas dan pemberitahuan izin ini harus disertakan dalam
 * setiap salinan atau bagian penting Aplikasi Ini. Barang siapa yang menghapus atau menghilangkan
 * pemberitahuan ini melanggar ketentuan lisensi Aplikasi Ini.

 * PERANGKAT LUNAK INI DISEDIAKAN "SEBAGAIMANA ADANYA", TANPA JAMINAN APA PUN, BAIK TERSURAT MAUPUN
 * TERSIRAT. PENULIS ATAU PEMEGANG HAK CIPTA SAMA SEKALI TIDAK BERTANGGUNG JAWAB ATAS KLAIM, KERUSAKAN ATAU
 * KEWAJIBAN APAPUN ATAS PENGGUNAAN ATAU LAINNYA TERKAIT APLIKASI INI.
 *
 * @package OpenSID
 * @author  Tim Pengembang OpenDesa
 * @copyright Hak Cipta 2009 - 2015 Combine Resource Institution (http://lumbungkomunitas.net/)
 * @copyright Hak Cipta 2016 - 2020 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license http://www.gnu.org/licenses/gpl.html  GPL V3
 * @link  https://github.com/OpenSID/OpenSID
 */
?>
<script>
	$( function() {
		$( "#cari" ).autocomplete({
			source: function( request, response ) {
				$.ajax( {
					type: "POST",
					url: '<?= site_url("data_persil/autocomplete")?>',
					dataType: "json",
					data: {
						cari: request.term
					},
					success: function( data ) {
						response( JSON.parse( data ));
					}
				} );
			},
			minLength: 1,
		} );
	} );
</script>
<div class="content-wrapper">
	<section class="content-header">
		<h1>Daftar Persil <?= ucwords($this->setting->sebutan_desa)?> <?= $desa["nama_desa"];?></h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('hom_sid')?>"><i class="fa fa-home"></i> Home</a></li>
			<li class="active">Daftar Persil</li>
		</ol>
	</section>
	<section class="content" id="maincontent">
		<form id="mainform" name="mainform" action="" method="post">
			<div class="row">
				<div class="col-md-4 col-lg-3">
					<?php $this->load->view('data_persil/menu_kiri.php')?>
				</div>
				<div class="col-md-8 col-lg-9">
					<div class="box box-info">
						<div class="box-header">
							<h4 class="text-center"><strong>DAFTAR PERSIL</strong></h4>
						</div>
						<div class="box-body">
							<div class="row">
								<div class="col-sm-12">
									<div class="dataTables_wrapper form-inline dt-bootstrap no-footer">
										<a href="<?=site_url("data_persil/form/")?>" class="btn btn-social btn-flat btn-success btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"  title="Tambah Persil">
											<i class="fa fa-plus"></i>Tambah Persil
										</a>
										<a href="<?=site_url("data_persil/cetak")?>" class="btn btn-social btn-flat bg-purple btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Cetak Data" target="_blank">
											<i class="fa fa-print"></i>Cetak
										</a>
										<a href="<?=site_url("data_persil/unduh")?>" class="btn btn-social btn-flat bg-navy btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Unduh Data" target="_blank">
											<i class="fa fa-download"></i>Unduh
										</a>
										<a href="<?= site_url("data_persil/clear")?>" class="btn btn-social btn-flat bg-purple btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-refresh"></i>Bersihkan</a>
										<form id="mainform" name="mainform" action="" method="post">
											<div class="row">
												<div class="col-sm-12">
													<div class="box-tools">
														<div class="input-group input-group-sm pull-right">
															<input name="cari" id="cari" class="form-control" placeholder="Cari..." type="text" value="<?=html_escape($cari)?>" onkeypress="if (event.keyCode == 13){$('#'+'mainform').attr('action', '<?= site_url("data_persil/search")?>');$('#'+'mainform').submit();}">
															<div class="input-group-btn">
																<button type="submit" class="btn btn-default" onclick="$('#'+'mainform').attr('action', '<?= site_url("data_persil/search")?>');$('#'+'mainform').submit();"><i class="fa fa-search"></i></button>
															</div>
														</div>
													</div>
												</div>
											</div>
											<div class="row">
												<div class="col-sm-12">
													<div class="table-responsive">
														<table class="table table-bordered table-striped dataTable table-hover">
															<thead class="bg-gray disabled color-palette">
																<tr>
																	<th>No</th>
																	<th>Aksi</th>
																	<th>No. Persil : No. Urut Bidang</th>
																	<th>Kelas Tanah</th>
																	<th>Luas (M2)</th>
																	<th>Lokasi</th>
																	<th>C-Desa Awal</th>
																	<th>Jml Mutasi</th>
																</tr>
															</thead>
															<tbody>
																<?php foreach ($persil as $item): ?>
																	<tr>
																		<td><?= $item['no']?></td>
																		<td nowrap>
																			<?php if ($item['jml_bidang'] > 0): ?>
																				<a href="<?= site_url("data_persil/rincian/".$item["id"])?>" class="btn bg-purple btn-flat btn-sm" title="Rincian"><i class="fa fa-bars"></i></a>
																			<?php else: ?>
																				<a class="btn bg-purple btn-flat btn-sm" disabled title="Rincian"><i class="fa fa-bars"></i></a>
																			<?php endif ?>
																			<a href="<?= site_url("data_persil/form/".$item["id"])?>" class="btn bg-orange btn-flat btn-sm"  title="Ubah Data"><i class="fa fa-edit"></i></a>
																			<?php if ($item['jml_bidang'] == 0): ?>
																				<a href="#" data-href="<?= site_url("data_persil/hapus/".$item["id"])?>" class="btn bg-maroon btn-flat btn-sm" title="Hapus" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash-o"></i></a>
																			<?php else: ?>
																				<a class="btn bg-maroon btn-flat btn-sm" disabled><i class="fa fa-trash-o"></i></a>
																			<?php endif ?>
																			</td>
																			<td><?= $item['nomor'].' : '.$item['nomor_urut_bidang']?></td>
																			<td><?= $persil_kelas[$item["kelas"]]['kode']?></td>
																			<td><?= $item['luas_persil']?></td>
																			<td><?= $item['alamat'] ?: $item['lokasi']?></td>
																			<td><a href="<?= site_url("cdesa/mutasi/$item[cdesa_awal]/$item[id]")?>"><?= $item['nomor_cdesa_awal']?></a></td>
																			<td><?= $item['jml_bidang']?></td>
																		</tr>
																	<?php endforeach; ?>
																</tbody>
															</table>
														</div>
													</div>
												</div>
											</form>
											<?php $this->load->view('global/paging');?>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</form>
	</section>
</div>
<?php $this->load->view('global/confirm_delete');?>
