
<tr>
  <th>Tempat Tanggal Lahir (Umur)</th>
  <td>
    <?php echo $individu['tempatlahir']?> <?php echo tgl_indo($individu['tanggallahir'])?> (<?php echo $individu['umur']?> Tahun)
  </td>
</tr>
<tr>
  <th>Alamat</th>
  <td>
    <?php echo $individu['alamat_wilayah']; ?>
  </td>
</tr>
<tr>
  <th>Pendidikan</th>
  <td>
    <?php echo $individu['pendidikan']?>
  </td>
</tr>
<tr>
  <th>Warganegara / Agama</th>
  <td>
    <?php echo $individu['warganegara']?> / <?php echo $individu['agama']?>
  </td>
</tr>
