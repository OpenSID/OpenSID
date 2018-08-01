<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <title>Cetak Laporan Bulanan</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link href="<?= base_url()?>assets/css/report.css" rel="stylesheet" type="text/css">
  </head>
  <style type="text/css">
    td.judul {text-align: center; font-size: 14pt;};
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
          <tbody>
            <?php foreach ($config as $data): ?>
              <tr>
                <td colspan="12" class="judul"><strong>PEMERINTAH <?= strtoupper($this->setting->sebutan_kabupaten)?> <?= strtoupper($data['nama_kabupaten'])?> <?= strtoupper($this->setting->sebutan_kecamatan)?> <?= strtoupper($data['nama_kecamatan'])?></strong></td>
              </tr>
              <tr>
                <td colspan="12" class="judul"><strong>LAPORAN PENDUDUK <?= strtoupper($this->setting->sebutan_desa)?> <?= strtoupper($data['nama_desa'])?></strong></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
        <br>
        <table>
          <tbody>
            <tr>
              <td style="text-align: left;">Bulan : <?= $bln?> <?= $tahun?> </td>
              <td width="40%"></td>
            </tr>
          </tbody>
        </table>
        <?php include ("donjo-app/views/laporan/tabel_bulanan.php"); ?>
        <table>
          <col span="9" style="width: 8%">
          <col style="width: 28%">
          <tr><td colspan="10">&nbsp;</td>
          <tr><td colspan="10">&nbsp;</td>
          <tr>
            <td colspan="9">&nbsp;</td>
            <td><?= ucwords($this->setting->sebutan_desa)?> <?= unpenetration($data['nama_desa'])?>, <?= tgl_indo(date("Y m d"))?></td>
          </tr>
          <tr>
            <td colspan="9">&nbsp;</td>
            <td><?= unpenetration($input['jabatan'])?> <?= unpenetration($data['nama_desa'])?></td>
          </tr>
          <tr><td colspan="10">&nbsp;</td>
          <tr><td colspan="10">&nbsp;</td>
          <tr><td colspan="10">&nbsp;</td>
          <tr><td colspan="10">&nbsp;</td>
          <tr>
            <td colspan="9">&nbsp;</td>
            <td>( <?= unpenetration($input['pamong'])?> )</td>
          </tr>
        </table>

      </div>
    </div>
  </body>
</html>
