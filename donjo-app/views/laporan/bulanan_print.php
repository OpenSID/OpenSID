<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <title>Cetak Laporan Bulanan</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link href="<?= base_url()?>assets/css/report.css" rel="stylesheet" type="text/css">
    <!-- Untuk ubahan style desa -->
    <?php if (is_file("desa/css/siteman.css")): ?>
      <link type='text/css' href="<?= base_url()?>desa/css/siteman.css" rel='Stylesheet' />
    <?php endif; ?>
  </head>
  <style type="text/css">
    .underline { text-decoration: underline; }
    td.judul {font-size: 14pt; font-weight: bold;}
    td.judul2 {font-size: 12pt; font-weight: bold;}
    td.text-bold {font-weight: bold;}
    table.tftable td.no-border {
      border: 0px;
      border-style: hidden;
    }
    table.tftable td.no-border-kecuali-kiri {
      border-top-style: hidden;
      border-bottom-style: hidden;
      border-right-style: hidden;
    }
    table.tftable td.no-border-kecuali-atas {
      border-left-style: hidden;
      border-bottom-style: hidden;
      border-right-style: hidden;
    }
    table.tftable td.no-border-kecuali-bawah {
      border-left-style: hidden;
      border-top-style: hidden;
      border-right-style: hidden;
    }
    table.tftable
    {
      margin-top: 5px;
      font-size:12px;
      color:<?= (isset($warna_font) ? $warna_font : "");?>;
      width:100%;
      border-width: 1px;
      border-style: solid;
      border-color: <?= (isset($warna_border) ? $warna_border : "");?>;
      border-collapse: collapse;
    }
    table.tftable.lap-bulanan
    {
      border-width: 3px;
    }
    table.tftable tr.thick
    {
      border-width: 3px; border-style: solid;
    }
    table.tftable th.thick
    {
      border-width: 3px;
    }
    table.tftable th.thick-kiri
    {
      border-left: 3px solid <?= (isset($warna_border) ? $warna_border : "");?>;
    }
    table.tftable td.thick-kanan
    {
      border-right: 3px solid <?= (isset($warna_border) ? $warna_border : "");?>;
    }
    table.tftable td.angka
    {
      text-align: right;
      }
    table.tftable th
    {
      background-color:<?= (isset($warna_background) ? $warna_background : "");?>;padding: 3px;border: 1px solid <?= (isset($warna_border) ? $warna_border : "");?>;text-align:center;
    }
    /*table.tftable tr {background-color:#ffffff;}*/
    table.tftable td
    {
      padding: 8px;border: 1px solid <?= (isset($warna_border) ? $warna_border : "");?>;
    }
  </style>

  <body>
    <div id="container">
      <!-- Print Body -->
      <div id="body">
        <table>
          <tr>
            <td colspan="11" class='text-bold'>PEMERINTAH KABUPATEN/KOTA</td>
            <td colspan="2" class="text-bold"><span style="float: right; border: solid 1px black; font-size: 12pt; text-align: center; padding: 5px 20px;">LAMPIRAN A-9</span></td>
          </tr>
          <tr>
            <td colspan="2" class="text"><span style="border-bottom: 2px solid;"><?= strtoupper($config['nama_kabupaten'])?></span></td>
            <td colspan="11">&nbsp;</td>
          </tr>
          <tr>
            <td colspan="3">&nbsp;</td>
            <td colspan="10" class="judul" style="padding-bottom: 10px;"><span style="border-bottom: 2px solid;">LAPORAN BULANAN DESA/KELURAHAN</span></td>
          <tr>
            <tr>
              <td colspan="3" width="32%">&nbsp;</td>
              <td colspan="3" width="15%" class="text-bold">Desa/Kelurahan</td>
              <td colspan="7" width="53%">: <?= strtoupper($config['nama_desa'])?></td>
            </tr>
            <tr>
              <td colspan="3">&nbsp;</td>
              <td colspan="3" class="text-bold">Kecamatan</td>
              <td colspan="7">: <?= strtoupper($config['nama_kecamatan'])?></td>
            </tr>
          <tr>
            <td colspan="3">&nbsp;</td>
            <td colspan="3" class="text-bold">Laporan Bulan</td>
            <td colspan="7">: <?= $bln?> <?= $tahun?></td>
          </tr>
        </table>
        <br>
        <?php include ("donjo-app/views/laporan/tabel_bulanan.php"); ?>
        <table class="tftable">
          <tr><td colspan="13" class="no-border">&nbsp;</td></tr>
          <tr>
            <td colspan="8" class="judul2 no-border-kecuali-bawah" style="padding-bottom: 10px;">
              <span style="border-bottom: 2px solid;">PERINCIAN PINDAH</span>
            </td>
            <td colspan="5" class="no-border">&nbsp;</td>
          </tr>
          <tr>
            <th rowspan="2" width='2%' class="text-center">NO</th>
            <th rowspan="2" width='20%' class="text-center">KETERANGAN</th>
            <th colspan="3" class="text-center">PENDUDUK</th>
            <th colspan="3" class="text-center">KELUARGA (KK)</th>
            <td rowspan="7" colspan="2" width="30%" class="no-border-kecuali-kiri">&nbsp;</td>
            <td rowspan="2" colspan="3" class="no-border" style="vertical-align: top;">
              <?= ucwords($this->setting->sebutan_desa)?> <?= $config['nama_desa']?>, <?= tgl_indo(date("Y m d"))?><br>
              <?= str_ireplace($this->setting->sebutan_desa, '', $pamong_ttd['jabatan']).' '.ucwords($this->setting->sebutan_desa).' '.$config['nama_desa']?>
            </td>
          </tr>
          <tr>
            <th class="text-center">L</th>
            <th class="text-center">P</th>
            <th class="text-center">L+P</th>
            <th class="text-center">L</th>
            <th class="text-center">P</th>
            <th class="text-center">L+P</th>
          </tr>
          <tr>
            <td class="no_urut">1</td>
            <td>Pindah keluar Desa/Kelurahan</td>
            <td class="bilangan"><?= show_zero_as($rincian_pindah['DESA_L'],'-')?></td>
            <td class="bilangan"><?= show_zero_as($rincian_pindah['DESA_P'],'-')?></td>
            <td class="bilangan"><?= show_zero_as(($rincian_pindah['DESA_L']+$rincian_pindah['DESA_P']),'-')?></td>
            <td class="bilangan"><?= show_zero_as($rincian_pindah['DESA_KK_L'],'-')?></td>
            <td class="bilangan"><?= show_zero_as($rincian_pindah['DESA_KK_P'],'-')?></td>
            <td class="bilangan"><?= show_zero_as(($rincian_pindah['DESA_KK_L']+$rincian_pindah['DESA_KK_P']),'-')?></td>
            <td colspan="3" class="no-border">&nbsp;</td>
          </tr>
          <tr>
            <td class="no_urut">2</td>
            <td>Pindah keluar Kecamatan</td>
            <td class="bilangan"><?= show_zero_as($rincian_pindah['KEC_L'],'-')?></td>
            <td class="bilangan"><?= show_zero_as($rincian_pindah['KEC_P'],'-')?></td>
            <td class="bilangan"><?= show_zero_as(($rincian_pindah['KEC_L']+$rincian_pindah['KEC_P']),'-')?></td>
            <td class="bilangan"><?= show_zero_as($rincian_pindah['KEC_KK_L'],'-')?></td>
            <td class="bilangan"><?= show_zero_as($rincian_pindah['KEC_KK_P'],'-')?></td>
            <td class="bilangan"><?= show_zero_as(($rincian_pindah['KEC_KK_L']+$rincian_pindah['KEC_KK_P']),'-')?></td>
            <td colspan="3" class="no-border">&nbsp;</td>
          </tr>
          <tr>
            <td class="no_urut">3</td>
            <td>Pindah keluar Kabupaten/Kota</td>
            <td class="bilangan"><?= show_zero_as($rincian_pindah['KAB_L'],'-')?></td>
            <td class="bilangan"><?= show_zero_as($rincian_pindah['KAB_P'],'-')?></td>
            <td class="bilangan"><?= show_zero_as(($rincian_pindah['KAB_L']+$rincian_pindah['KAB_P']),'-')?></td>
            <td class="bilangan"><?= show_zero_as($rincian_pindah['KAB_KK_L'],'-')?></td>
            <td class="bilangan"><?= show_zero_as($rincian_pindah['KAB_KK_P'],'-')?></td>
            <td class="bilangan"><?= show_zero_as(($rincian_pindah['KAB_KK_L']+$rincian_pindah['KAB_KK_P']),'-')?></td>
            <td colspan="3" class="no-border">&nbsp;</td>
          </tr>
          <tr>
            <td class="no_urut">4</td>
            <td>Pindah keluar Provinsi</td>
            <td class="bilangan"><?= show_zero_as($rincian_pindah['PROV_L'],'-')?></td>
            <td class="bilangan"><?= show_zero_as($rincian_pindah['PROV_P'],'-')?></td>
            <td class="bilangan"><?= show_zero_as(($rincian_pindah['PROV_L']+$rincian_pindah['PROV_P']),'-')?></td>
            <td class="bilangan"><?= show_zero_as($rincian_pindah['PROV_KK_L'],'-')?></td>
            <td class="bilangan"><?= show_zero_as($rincian_pindah['PROV_KK_P'],'-')?></td>
            <td class="bilangan"><?= show_zero_as(($rincian_pindah['PROV_KK_L']+$rincian_pindah['PROV_KK_P']),'-')?></td>
            <td rowspan="2" colspan="3" class="no-border" style="vertical-align: top;">
              ( <?= $pamong_ttd['pamong_nama']?> )<br>
              NIP/<?= $this->setting->sebutan_nip_desa  ?> <?= $pamong_ttd['pamong_niap_nip']?>
            </td>
          </tr>
          <tr>
            <td colspan="2" class="text-center text-bold">JUMLAH:</td>
            <td class="bilangan"><?= show_zero_as($rincian_pindah['TOTAL_L'],'-')?></td>
            <td class="bilangan"><?= show_zero_as($rincian_pindah['TOTAL_P'],'-')?></td>
            <td class="bilangan"><?= show_zero_as(($rincian_pindah['TOTAL_L']+$rincian_pindah['TOTAL_P']),'-')?></td>
            <td class="bilangan"><?= show_zero_as($rincian_pindah['TOTAL_KK_L'],'-')?></td>
            <td class="bilangan"><?= show_zero_as($rincian_pindah['TOTAL_KK_P'],'-')?></td>
            <td class="bilangan"><?= show_zero_as(($rincian_pindah['TOTAL_KK_L']+$rincian_pindah['TOTAL_KK_P']),'-')?></td>
          </tr>
        </table>
      </div>
    </div>
  </body>
</html>
