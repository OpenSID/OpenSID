<style type="text/css">
    td.judul {text-align: center; font-size: 14pt;};
    table.tftable
    {
      margin-top: 5px;
      font-size:12px;
      color:<?= $warna_font ?? ''; ?>;
      width:100%;
      border-width: 1px;
      border-style: solid;
      border-color: <?= $warna_border ?? ''; ?>;
      border-collapse: collapse;
    }
    table.tftable.lap-bulanan {border-width: 3px;}
    table.tftable tr.thick {border-width: 3px; border-style: solid;}
    table.tftable th.thick {border-width: 3px;}
    table.tftable th.thick-kiri {border-left: 3px solid <?= $warna_border ?? ''; ?>;}
    table.tftable td.thick-kanan {border-right: 3px solid <?= $warna_border ?? ''; ?>;}
    table.tftable td.angka {text-align: right;}
    table.tftable th {background-color:<?= $warna_background ?? ''; ?>;padding: 3px;border: 1px solid <?= $warna_border ?? ''; ?>;text-align:center;}
    /*table.tftable tr {background-color:#ffffff;}*/
    table.tftable td {padding: 8px;border: 1px solid <?= $warna_border ?? ''; ?>;}
  </style>
<?php
  $tgl = date('d_m_Y');
      header('Content-type: application/octet-stream');
      header("Content-Disposition: attachment; filename=Laporan_bulanan_{$tgl}.xls");
      header('Pragma: no-cache');
      header('Expires: 0');
      ?>

<?php include 'donjo-app/views/laporan/bulanan_print.php'; ?>
