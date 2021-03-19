<!DOCTYPE html
  PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <title>Cetak Laporan Bulanan</title>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <link href="<?php echo base_url() ?>assets/css/report.css" rel="stylesheet" type="text/css">
  <!-- Untuk ubahan style desa -->
  <?php if ( is_file( "desa/css/siteman.css" ) ): ?>
  <link type='text/css' href="<?php echo base_url() ?>desa/css/siteman.css" rel='Stylesheet' />
  <?php endif;?>
</head>
<style type="text/css">
  .underline {
    text-decoration: underline;
  }

  td.judul {
    font-size: 14pt;
    font-weight: bold;
  }

  td.judul2 {
    font-size: 12pt;
    font-weight: bold;
  }

  td.text-bold {
    font-weight: bold;
  }

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

  table.tftable {
    margin-top: 5px;
    font-size: 12px;
    color: <?php echo (isset($warna_font) ? $warna_font : "");
    ?>;
    width: 100%;
    border-width: 1px;
    border-style: solid;
    border-color: <?php echo (isset($warna_border) ? $warna_border : "");
    ?>;
    border-collapse: collapse;
  }

  table.tftable.lap-bulanan {
    border-width: 3px;
  }

  table.tftable tr.thick {
    border-width: 3px;
    border-style: solid;
  }

  table.tftable th.thick {
    border-width: 3px;
  }

  table.tftable th.thick-kiri {
    border-left: 3px solid <?php echo (isset($warna_border) ? $warna_border : "");
    ?>;
  }

  table.tftable td.thick-kanan {
    border-right: 3px solid <?php echo (isset($warna_border) ? $warna_border : "");
    ?>;
  }

  table.tftable td.angka {
    text-align: right;
  }

  table.tftable th {
    background-color: <?php echo (isset($warna_background) ? $warna_background : "");
    ?>;
    padding: 3px;
    border: 1px solid <?php echo (isset($warna_border) ? $warna_border : "");
    ?>;
    text-align: center;
  }

  /*table.tftable tr {background-color:#ffffff;}*/
  table.tftable td {
    padding: 8px;
    border: 1px solid <?php echo (isset($warna_border) ? $warna_border : "");
    ?>;
  }
</style>

<body>
  <div id="container">
    <!-- Print Body -->
    <div id="body">
      <table>
        <tr>
          <td colspan="3" style="text-align: center;" class='text-bold'>PEMERINTAH KABUPATEN/KOTA</td>
        </tr>
        <tr>
          <td colspan="3" style="text-align: center;" class="text"><span
              style="border-bottom: 2px solid;"><?php echo strtoupper( $config['nama_kabupaten'] ) ?></span></td>
        </tr>
        <tr>
          <td colspan="3" class="judul" style="padding: 15px 0px;"><span style="border-bottom: 2px solid;">LAPORAN
              PEMBUATAN SURAT</span></td>
        <tr>
        <tr>
          <td width="32%">&nbsp;</td>
          <td width="15%" class="text-bold">Desa/Kelurahan</td>
          <td width="53%">: <?php echo strtoupper( $config['nama_desa'] ) ?></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td class="text-bold">Kecamatan</td>
          <td>: <?php echo strtoupper( $config['nama_kecamatan'] ) ?></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td class="text-bold">Laporan Bulan</td>
          <td>: <?php echo opensid_nama_bulan( $bulan ) ?> <?php echo $tahun ?></td>
        </tr>
      </table>
      <br />
      <table class="tftable">
        <thead class="bg-gray">
          <tr>
            <th width='20px' class="text-center">No</th>
            <th class="text-center">Jenis
              Surat
            </th>
            <th width='50' class="text-center">Jumlah</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ( $result as $i => $row ): ?>
          <tr>
            <td><?php echo $i + 1?></td>
            <td><?php echo htmlentities( $row['jenis'] )?></td>
            <td style="text-align: right; font-weight: bold;"><?php echo $row['jumlah']?></td>
          </tr>
          <?php endforeach?>
        </tbody>
      </table>
      <br />
      <table class="tftable">
        <tr>
          <td class="no-border" style="width:auto">&nbsp;</td>
          <td class="no-border" style="width: 250px; vertical-align: top; text-align: center">
            <?php echo ucwords( $this->setting->sebutan_desa ) ?> <?php echo $config['nama_desa'] ?>,
            <?php echo tgl_indo( date( "Y m d" ) ) ?><br>
            <?php echo str_ireplace( $this->setting->sebutan_desa, '', $pamong['jabatan'] ) . ' ' . ucwords( $this->setting->sebutan_desa ) . ' ' . $config['nama_desa'] ?>
          </td>
        </tr>
        <tr>
          <td colspan="2" class="no-border" style="width:auto; height: 42px">&nbsp;</td>
        </tr>
        <tr>
          <td class="no-border" style="width:auto">&nbsp;</td>
          <td class="no-border" style="vertical-align: top; text-align: center">
            ( <?php echo $pamong['pamong_nama'] ?> )<br>
            <?php if(!empty($pamong['pamong_niap_nip'])){
              echo "NIP/" . $this->setting->sebutan_nip_desa . " " . $pamong['pamong_niap_nip'];
            } ?>
          </td>
        </tr>
      </table>
    </div>
  </div>
</body>

</html>