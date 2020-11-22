<style type="text/css">
  #mandiri i.fa {
    margin-right: 10px;
  }
  #mandiri button.nowrap {
    white-space: nowrap;
  }
  #mandiri .badge {
    background-color: red;
    color: white;
    margin-left: 0px;
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
    <td>
      <h4>
        <a href="<?= site_url();?>mandiri_web/mandiri/1/3">
          <button type="button" class="btn btn-primary btn-block nowrap">
            <i class="fa fa-envelope-o"></i><span>Kotak Pesan</span>
          <span class="badge" id="b_pesan" title="Pesan baru" style="display: none;"></span>
          </button>
        </a>
      </h4>
    </td>
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
    <td><h4><a href="<?= site_url();?>mandiri_web/ganti_pin" class=""><button type="button" class="btn btn-warning btn-block"><i class="fa fa-key"></i>Ganti PIN</button></a></h4></td>
  </tr>
  <tr>
    <td><h4><a href="<?= site_url();?>mandiri_web/logout"  class=""><button type="button" class="btn btn-danger btn-block"><i class="fa fa-sign-out"></i>Keluar</button></a></h4></td>
  </tr>
</table>

<script type="text/javascript">

  $('document').ready(function()
  {
    setTimeout(function()
    {
      refresh_badge($("#b_pesan"), "<?= site_url('notif_web/inbox'); ?>");
    }, 500);
  });

</script>
