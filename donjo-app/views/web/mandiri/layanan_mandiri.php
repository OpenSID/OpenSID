<!-- Beranda Layanan Mandiri -->
<table id="mandiri" width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td class="text-center"><p style="font-size: larger; font-weight:bolder"><?= $_SESSION['nama'];?></p>
      NIK: <?= $_SESSION['nik'];?><br>
      No KK: <?= $_SESSION['no_kk']?></td>
  </tr>
  <tr>
    <td class="button text-center" scope="col" style="padding-top:10px;padding-bottom: 15px;"><a href="<?= site_url("first/cetak_kk/$penduduk[id]/1"); ?>" target="_blank"><button type="button" class="btn btn-success"><i class="fa fa-print"></i>CETAK KARTU KELUARGA</button></a></td>
  </tr>
  <tr>
    <td><h4><a href="<?= site_url();?>first/mandiri/1/1" class=""><button type="button" class="btn btn-primary btn-block"><i class="fa fa-user"></i>Profil</button></a> </h4></td>
  </tr>
  <tr>
    <td><h4><a href="<?= site_url();?>first/mandiri/1/2" class=""><button type="button" class="btn btn-primary btn-block"><i class="fa fa-history"></i>Riwayat Layanan</button></a> </h4></td>
  </tr>
  <tr>
    <td><h4><a href="<?= site_url();?>first/mandiri/1/3" class=""><button type="button" class="btn btn-primary btn-block"><i class="fa fa-bullhorn"></i>Lapor</button></a> </h4></td>
  </tr>
  <tr>
    <td><h4><a href="<?= site_url();?>first/mandiri/1/4" class=""><button type="button" class="btn btn-primary btn-block"><i class="fa fa-handshake-o"></i>Bantuan</button></a></h4></td>
  </tr>
  <tr>
    <td><h4><a href="<?= site_url();?>first/logout"  class=""><button type="button" class="btn btn-danger btn-block"><i class="fa fa-sign-out"></i>Keluar</button></a></h4></td>
  </tr>
</table>

