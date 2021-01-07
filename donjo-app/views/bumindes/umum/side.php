<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * File ini:
 *
 * View untuk menu navigasi di komponen Buku Administrasi Desa
 *
 * donjo-app/views/bumindes/umum/side.php
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

<style type="text/css">
  .mini-submenu{
    display:none;
    background-color: rgba(0, 0, 0, 0);
    border: 1px solid rgba(0, 0, 0, 0.9);
    border-radius: 4px;
    padding: 9px;
    /*position: relative;*/
    width: 42px;
    cursor: pointer;
  }

  #slide-submenu{
    display: inline-block;
    padding: 2px;
    background-color: rgba(0, 0, 0, 0);
    border: 1px solid rgba(0, 0, 0, 0.9);
    border-radius: 4px;
    padding: 6px;
    cursor: pointer;
  }
</style>

<button class="mini-submenu">
  <i class="fa fa-bars"></i>
</button>

<div class="list-group">
    <div class="box box-info">
    	<div class="box-header with-border">
    		<h3 class="box-title">Administrasi Umum</h3>
    		<div class="box-tools">
    			<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
        		<button class="hide-menu" id="slide-submenu">
                    <i class="fa fa-bars"></i>
                </button>
    		</div>
    	</div>
    	<div class="box-body no-padding">
    		<ul class="nav nav-stacked">
              <li class="<?php compared_return($selected_nav, "peraturan", "active"); ?>"><a href="<?= site_url('dokumen_sekretariat/clear/3') ?>">Buku Peraturan Desa</a></li>
              <li class="<?php compared_return($selected_nav, "keputusan", "active"); ?>"><a href="<?= site_url('dokumen_sekretariat/clear/2') ?>">Buku Keputusan Kepala Desa</a></li>
              <li class="<?php compared_return($selected_nav, "aparat", "active"); ?>"><a href="<?= site_url('pengurus') ?>">Buku Aparat Pemerintah Desa</a></li>
              <li class="<?php compared_return($selected_nav, "agenda_keluar", "active"); ?>"><a href="<?= site_url('surat_keluar') ?>">Buku Agenda - Surat Keluar</a></li>
              <li class="<?php compared_return($selected_nav, "agenda_masuk", "active"); ?>"><a href="<?= site_url('surat_masuk') ?>">Buku Agenda - Surat Masuk</a></li>
              <li class="<?php compared_return($selected_nav, "ekspedisi", "active"); ?>"><a href="<?= site_url('ekspedisi/clear') ?>">Buku Ekspedisi</a></li>
    		</ul>
    	</div>
    </div>
</div>

<script type="text/javascript">
  $(function(){

    $('#slide-submenu').on('click',function() {
      $(this).closest('.list-group').fadeOut('slow',function(){
        $('.mini-submenu').fadeIn();
      });
      $('#umum-sidebar').removeClass("col-sm-3");
      $('#umum-sidebar').addClass("col-sm-1");
      $('#umum-content').removeClass("col-sm-9");
      $('#umum-content').addClass("col-sm-11");
    });

    $('.mini-submenu').on('click',function(){
      $(this).next('.list-group').fadeIn('slow');
      $('.mini-submenu').hide();
      $('#umum-sidebar').removeClass("col-sm-1");
      $('#umum-sidebar').addClass("col-sm-3");
      $('#umum-content').removeClass("col-sm-11");
      $('#umum-content').addClass("col-sm-9");
    })
  })
</script>

