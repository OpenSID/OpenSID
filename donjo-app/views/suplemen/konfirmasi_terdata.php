
<?php if($suplemen["sasaran"] == 1): ?>
  <tr>
    <th>Alamat</th>
    <td>
      <?php echo $individu['alamat_wilayah']; ?>
    </td>
  </tr>
  <tr>
    <th>Tempat Tanggal Lahir (Umur)</th>
    <td>
      <?php echo $individu['tempatlahir']?> <?php echo tgl_indo($individu['tanggallahir'])?> (<?php echo $individu['umur']?> Tahun)
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
<?php elseif($suplemen["sasaran"] == 2): ?>
  <tr>
    <th>Alamat Keluarga</th>
    <td>
      <?php echo $individu['alamat_wilayah']; ?>
    </td>
  </tr>
  <tr>
    <th>Tempat Tanggal Lahir (Umur) KK</th>
    <td>
      <?php echo $individu['tempatlahir']?> <?php echo tgl_indo($individu['tanggallahir'])?> (<?php echo $individu['umur']?> Tahun)
    </td>
  </tr>
  <tr>
    <th>Pendidikan KK</th>
    <td>
      <?php echo $individu['pendidikan']?>
    </td>
  </tr>
  <tr>
    <th>Warganegara / Agama KK</th>
    <td>
      <?php echo $individu['warganegara']?> / <?php echo $individu['agama']?>
    </td>
  </tr>
<?php endif; ?>