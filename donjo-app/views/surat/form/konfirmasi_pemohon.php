
<tr>
  <th class="konfirmasi">Tempat Tanggal Lahir (Umur)</th>
  <td>
    <?php echo $individu['tempatlahir']?> <?php echo tgl_indo($individu['tanggallahir'])?> (<?php echo $individu['umur']?> Tahun)
  </td>
</tr>
<tr>
  <th class="konfirmasi">Alamat</th>
  <td>
    <?php echo $individu['alamat_wilayah']; ?>
  </td>
</tr>
<tr>
  <th class="konfirmasi">Pendidikan</th>
  <td>
    <?php echo $individu['pendidikan']?>
  </td>
</tr>
<tr>
  <th class="konfirmasi">Warganegara / Agama</th>
  <td>
    <?php echo $individu['warganegara']?> / <?php echo $individu['agama']?>
  </td>
</tr>
<tr>
  <th class="konfirmasi">Status</th>
  <td>
    <?php echo $individu['status']?>
  </td>
</tr>
<tr>
  <th class="konfirmasi">Dokumen Kelengkapan / Syarat</th>
  <td>
    <a header="Dokumen" target="ajax-modal" rel="dokumen" href="<?php echo site_url("penduduk/dokumen_list/$individu[id]")?>" class="uibutton special">Daftar Dokumen</a><a target="_blank" href="<?php echo site_url("penduduk/dokumen/$individu[id]")?>" class="uibutton confirm">Manajemen Dokumen</a> )* Atas Nama <?php echo $individu['nama']?> [<?php echo $individu['nik']?>]
  </td>
</tr>
