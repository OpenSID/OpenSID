<?php
/**
 * File ini:
 *
 * Form kelompok di modul Kelompok
 *
 * donjo-app/views/bumindes/kader_pemberdayaan/form.php
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
 * @package OpenSID
 * @author  Tim Pengembang OpenDesa
 * @copyright Hak Cipta 2009 - 2015 Combine Resource Institution (http://lumbungkomunitas.net/)
 * @copyright Hak Cipta 2016 - 2020 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license http://www.gnu.org/licenses/gpl.html  GPL V3
 * @link  https://github.com/OpenSID/OpenSID
 */
?>

<div class="content-wrapper">
	<section class="content-header">
		<h1>Master Kader Pemberdayaan</h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('hom_sid')?>"><i class="fa fa-home"></i> Home</a></li>
			<li><a href="<?= site_url('kelompok')?>"> Daftar Kader Pemberdayaan</a></li>
			<li class="active">Master Kader Pemberdayaan</li>
		</ol>
	</section>
	<section class="content">
		<div class="row">
			<div class="col-md-12">
				<div class="box box-info">
					<div class="box-header with-border">
						<a href="<?= site_url()?>bumindes_pembangunan/tables/kader_pemberdayaan" class="btn btn-social btn-flat btn-info btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-arrow-circle-left "></i> Kembali Ke Daftar Kader Pemberdayaan</a>
					</div>
					<form id="validasi" action="<?= $form_action?>" method="POST" enctype="multipart/form-data" class="form-horizontal">
						<div class="box-body">
							<div class="form-group">
								<label  class="col-sm-3 control-label" for="nama">Nama</label>
								<div class="col-xs-5">
								<input  id="id" class="form-control" type="hidden" placeholder="ID Kader" name="id">
								<input  id="nama" class="form-control" type="text" placeholder="Nama Kader" name="nama" value="<?= $pembangunan['nama']; ?>">
								</div>
							</div>
							<div class="form-group">
								<label  class="col-sm-3 control-label" for="kode">Umur</label>
								<div class="col-xs-2">
									<input  id="umur" class="form-control" type="text" placeholder="Umur" name="umur" value="<?= $pembangunan['umur']; ?>" readonly>
								</div>
							</div>
							<?php if($pembangunan['jeniskelamin'] == 'L'){
								$jk = 'Laki-laki';
							}elseif($pembangunan['jeniskelamin'] == 'P'){
								$jk = 'Perempuan';
							}else{
								$jk = '';
							}			
							?>
							<div class="form-group">
								<label  class="col-sm-3 control-label" for="id_master">Jenis Kelamin</label>
								<div class="col-sm-7">
								<input  id="pendidikan" class="form-control" type="hidden" placeholder="Pendidikan" name="pendidikan">
								<input  id="jk" class="form-control required" type="text" placeholder="Jenis Kelamin" name="jk" value="<?= $jk; ?>" readonly>
								</div>
							</div>
							<div class="form-group">
								<label  class="col-sm-3 control-label" for="Pendidikan">Pendidikan / Kursus</label>
								<div class="col-sm-7">
								<input type="text" name="pendidikankursus" id="pendidikankursus" class="form-control" value="<?= $pembangunan['pendidikankursus']; ?>" />
								</div>
							</div>
							<div class="form-group">
								<label  class="col-sm-3 control-label" for="Keahlian">Keahlian</label>
								<div class="col-sm-7">
								<input type="text" name="pendidikankeahlian" id="pendidikankeahlian" class="form-control" value="<?= $pembangunan['pendidikanahli']; ?>" />
								 </div>
							</div>
							<div class="form-group">
								<label  class="col-sm-3 control-label" for="kode">Alamat</label>
								<div class="col-xs-6">
									<input  id="alamat" class="form-control" type="text" placeholder="Alamat" name="alamat" value="<?= $pembangunan['alamat']; ?>" readonly>
								</div>
							</div>
							<div class="form-group">
								<label  class="col-sm-3 control-label" for="keterangan">Keterangan</label>
								<div class="col-sm-7">
									<input id="keterangan" class="form-control required" type="text" placeholder="Keterangan" name="keterangan" value="<?= $pembangunan['keterangan']; ?>">
								</div>
							</div>
						</div>
						<div class="box-footer">
							<div class="col-xs-12">
								<button type="reset" class="btn btn-social btn-flat btn-danger btn-sm"><i class="fa fa-times"></i> Batal</button>
								<button type="submit" class="btn btn-social btn-flat btn-info btn-sm pull-right"><i class="fa fa-check"></i> Simpan</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</section>
</div>

<script>
        $(document).ready(function(){
			$('#pendidikankursus').tokenfield({
    autocomplete: {
      source: function (request, response) {
          jQuery.get("<?php echo base_url();?>index.php/bumindes_pembangunan/get_fill", {
              query: request.term
          }, function (data) {
              data = $.parseJSON(data);
              response(data);
          });
      },
      delay: 100
    },
    showAutocompleteOnFocus: true
  });
		
  $('#pendidikankeahlian').tokenfield({
    autocomplete: {
      source: function (request, response) {
          jQuery.get("<?php echo base_url();?>index.php/bumindes_pembangunan/get_fill_keahlian", {
              query: request.term
          }, function (data) {
              data = $.parseJSON(data);
              response(data);
          });
      },
      delay: 100
    },
    showAutocompleteOnFocus: true
  });
		

	          /*
			$("#nama").autocomplete({
              source: "<?php //echo site_url('bumindes_pembangunan/get_autocomplete/?');?>",
			  select: function (event, ui) {
			  $('[name="nama"]').val(ui.item.label); 
             $('[name="umur"]').val(ui.item.label); 
			 // document.getElementById('umur').setAttribute("value", age);
				}
            });
			*/
			 // Initialize 
			 $("#nama").autocomplete({
        source: function( request, response ) {
          // Fetch data
          $.ajax({
            url: "<?php echo site_url('bumindes_pembangunan/get_autocomplete');?>",
            type: 'post',
            dataType: "json",
            data: {
              search: request.term
            },
            success: function( data ) {
              response( data );
            }
          });
        },
        select: function (event, ui) {
		  $('#id').val(ui.item.id);
          $('#nama').val(ui.item.label); // display the selected text
          $('#umur').val(ui.item.value); // save selected id to input
		  $('#jk').val(ui.item.jk); // save selected id to input
		  $('#alamat').val(ui.item.alamat);
          return false;
        }
      });
        });

    </script>