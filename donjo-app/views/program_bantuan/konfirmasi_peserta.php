
<?php if($detail["sasaran"] == 1): ?>
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
<?php elseif($detail["sasaran"] == 2): ?>
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
<?php elseif($detail["sasaran"] == 3): ?>
  <tr>
    <th>Alamat Kepala Rumah Tangga</th>
    <td>
      <?php echo $individu['alamat_wilayah']; ?>
    </td>
  </tr>
  <tr>
    <th>Tempat Tanggal Lahir (Umur) Kepala RTM</th>
    <td>
      <?php echo $individu['tempatlahir']?> <?php echo tgl_indo($individu['tanggallahir'])?> (<?php echo $individu['umur']?> Tahun)
    </td>
  </tr>
  <tr>
    <th>Pendidikan Kepala RTM</th>
    <td>
      <?php echo $individu['pendidikan']?>
    </td>
  </tr>
  <tr>
    <th>Warganegara / Agama Kepala RTM</th>
    <td>
      <?php echo $individu['warganegara']?> / <?php echo $individu['agama']?>
    </td>
  </tr>
<?php elseif($detail["sasaran"] == 4): ?>
  <tr>
    <th>Alamat Ketua Kelompok</th>
    <td>
      <?php echo $individu['alamat_wilayah']; ?>
    </td>
  </tr>
  <tr>
    <th>Tempat Tanggal Lahir (Umur) Ketua Kelompok</th>
    <td>
      <?php echo $individu['tempatlahir']?> <?php echo tgl_indo($individu['tanggallahir'])?> (<?php echo $individu['umur']?> Tahun)
    </td>
  </tr>
  <tr>
    <th>Pendidikan Ketua Kelompok</th>
    <td>
      <?php echo $individu['pendidikan']?>
    </td>
  </tr>
  <tr>
    <th>Warganegara / Agama Ketua Kelompok</th>
    <td>
      <?php echo $individu['warganegara']?> / <?php echo $individu['agama']?>
    </td>
  </tr>

<?php endif; ?>