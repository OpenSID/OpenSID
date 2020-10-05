<?php
/*
 * File ini:
 *
 * Bagian dari menu cetak pada tampilan peta di Admin maupun Web
 *
 * donjo-app/views/gis/cetak_peta.php
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

<table class="title" leaflet-browser-print-content width="100%" style="border: solid 1px grey; text-align: center;">
  <tr>
    <td align="center"></td>
  </tr>
  <tr>
    <?php if ($wilayah == $nama_wilayah): ?>
      <td align="center"><img src="<?= gambar_desa($wil_atas['logo']);?>" alt="logo"  class="logo_mandiri"></td>
    <?php elseif (in_array($wilayah, array(ucwords($this->setting->sebutan_dusun), "RW", "RT"))): ?>
      <td align="center"><img src="<?= gambar_desa($logo['logo']);?>" alt="logo"  class="logo_mandiri"></td>
    <?php else: ?>
      <td align="center"><img src="<?= gambar_desa($desa['logo']);?>" alt="logo"  class="logo_mandiri"></td>
    <?php endif; ?>
  </tr>
  <tr>
    <td>
      <?php if ($wilayah == $nama_wilayah): ?>
        <h5 class="title text-center">PEMERINTAH <?= strtoupper($this->setting->sebutan_kabupaten)?></h5>
        <h5 class="title text-center"><?= strtoupper($wil_atas['nama_kabupaten'])?></h5>
        <h5 class="title text-center"><?= strtoupper($this->setting->sebutan_kecamatan)?></h5>
        <h5 class="title text-center"><?= strtoupper($wil_atas['nama_kecamatan'])?></h5>
        <h5 class="title text-center"><?= strtoupper($this->setting->sebutan_desa)?></h5>
        <h5 class="title text-center"><?= strtoupper($wil_atas['nama_desa'])?></h5>
      <?php else: ?>
        <h5 class="title text-center">PEMERINTAH <?= strtoupper($this->setting->sebutan_kabupaten)?></h5>
        <h5 class="title text-center"><?= strtoupper($desa['nama_kabupaten'])?></h5>
        <h5 class="title text-center"><?= strtoupper($this->setting->sebutan_kecamatan)?></h5>
        <h5 class="title text-center"><?= strtoupper($desa['nama_kecamatan'])?></h5>
        <h5 class="title text-center"><?= strtoupper($this->setting->sebutan_desa)?></h5>
        <h5 class="title text-center"><?= strtoupper($desa['nama_desa'])?></h5>
      <?php endif; ?>
    </td>
  </tr>
  <tr>
    <td align="center"></td>
  </tr>
  <tr>
    <td>
      <?php if ($wilayah == $nama_wilayah): ?>
        <h3 class="title text-center">PETA WILAYAH</h3>
        <h3 class="title text-center"><?= strtoupper($this->setting->sebutan_desa)?></h3>
        <h3 class="title text-center"><?= strtoupper($wil_atas['nama_desa'])?></h3>
      <?php elseif ($wilayah == ucwords($this->setting->sebutan_dusun)): ?>
        <h3 class="title text-center">PETA WILAYAH</h3>
        <h3 class="title text-center"><?= strtoupper($this->setting->sebutan_dusun)?></h3>
        <h3 class="title text-center"><?= strtoupper($wil_ini['dusun'])?></h3>
      <?php elseif ($wilayah == "RW"): ?>
        <h3 class="title text-center">PETA WILAYAH</h3>
        <h3 class="title text-center">RW <?= $wil_ini['rw']?></h3>
        <h3 class="title text-center"><?= strtoupper($this->setting->sebutan_dusun)?> <?= strtoupper($wil_ini['dusun'])?></h3>
      <?php elseif ($wilayah == "RT"): ?>
        <h3 class="title text-center">PETA WILAYAH</h3>
        <h3 class="title text-center">RT <?= $wil_ini['rt']?> RW <?= $wil_ini['rw']?> </h3>
        <h3 class="title text-center"><?= strtoupper($this->setting->sebutan_dusun)?> <?= strtoupper($wil_ini['dusun'])?></h3>
      <?php else: ?>
        <h3 class="title text-center">PETA WILAYAH</h3>
      <?php endif; ?>
    </td>
  </tr>
  <tr>
    <td align="center"></td>
  </tr>
  <tr>
    <td align="center"><img src="<?= base_url()?>assets/images/kompas.png" alt="OpenSID"></td>
  </tr>
</table>
