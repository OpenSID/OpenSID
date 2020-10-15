<style type="text/css">
  #mandiri i.fa {
    margin-right: 10px;
  }
</style>
<table id="mandiri" width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td class="text-center">
      <?php if ($penduduk['foto']): ?>
       <img class="penduduk profile-user-img img-responsive img-circle" src="<?= AmbilFoto($penduduk['foto'])?>" alt="Foto">
       <?php else: ?>
        <img class="penduduk profile-user-img img-responsive img-circle" src="<?= base_url()?>assets/files/user_pict/kuser.png" alt="Foto">
        <?php endif; ?></td>
      </tr>
  <tr>
    <td class="text-center"><p style="font-size: larger; "><?= $_SESSION['nama'];?></p>
      NIK: <?= $_SESSION['nik'];?><br>
      No KK: <?= $_SESSION['no_kk']?></td>
  </tr>
  <tr>
    <td class="button text-center" scope="col" style="padding-top:10px;padding-bottom: 15px;"><a href="<?= site_url("mandiri_web/cetak_kk/$penduduk[id]/1"); ?>" target="_blank"><button type="button" class="btn btn-success"><i class="fa fa-print"></i>CETAK KARTU KELUARGA</button></a></td>
  </tr>
  <tr>
    <td><h4><a href="<?= site_url();?>mandiri_web/mandiri/1/1" class=""><button type="button" class="btn btn-primary btn-block"><i class="fa fa-user"></i>Profil</button></a> </h4></td>
  </tr>
  <tr>
    <td><h4><a href="<?= site_url();?>mandiri_web/mandiri/1/3"><button type="button" class="btn btn-primary btn-block"><i class="fa fa-envelope-o"></i>Kotak Pesan</button></a></h4></td>
  </tr>
  <tr>
    <td><h4><a href="<?= site_url();?>mandiri_web/mandiri_surat" class=""><button type="button" class="btn btn-primary btn-block"><i class="fa fa-file"></i>Permohonan Surat</button></a></h4></td>
  </tr>
  <tr>
    <td><h4><a href="<?= site_url();?>mandiri_web/mandiri/1/2" class=""><button type="button" class="btn btn-primary btn-block"><i class="fa fa-history"></i>Riwayat Layanan / Status Permohonan Surat</button></a> </h4></td>
  </tr>
  <tr>
    <td><h4><a href="<?= site_url();?>mandiri_web/mandiri/1/4" class=""><button type="button" class="btn btn-primary btn-block"><i class="fa fa-handshake-o"></i>Program Bantuan</button></a></h4></td>
  </tr>
  <tr>
    <td><h4><a href="<?= site_url('logout');?>"  class=""><button type="button" class="btn btn-danger btn-block"><i class="fa fa-sign-out"></i>Keluar</button></a></h4></td>
  </tr>
</table>
