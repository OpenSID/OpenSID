<table class="title" leaflet-browser-print-content width="100%" style="border: solid 1px grey; text-align: center;">
  <tr>
    <td align="center"></td>
  </tr>
  <tr>
    <?php if ($wilayah == $nama_wilayah): ?>
      <td align="center"><img src="<?= gambar_desa($wil_atas['logo']); ?>" alt="logo"  class="logo_mandiri"></td>
    <?php elseif (in_array($wilayah, [ucwords($this->setting->sebutan_dusun), 'RW', 'RT'])): ?>
      <td align="center"><img src="<?= gambar_desa($logo['logo']); ?>" alt="logo"  class="logo_mandiri"></td>
    <?php else: ?>
      <td align="center"><img src="<?= gambar_desa($desa['logo']); ?>" alt="logo"  class="logo_mandiri"></td>
    <?php endif; ?>
  </tr>
  <tr>
    <td>
      <?php if ($wilayah == $nama_wilayah): ?>
        <h5 class="title text-center">PEMERINTAH <?= strtoupper($this->setting->sebutan_kabupaten)?></h5>
        <h5 class="title text-center"><?= strtoupper($wil_atas['nama_kabupaten'])?></h5>
        <h5 class="title text-center"><?= strtoupper($this->setting->sebutan_kecamatan)?></h5>
        <h5 class="title text-center"><?= strtoupper($wil_atas['nama_kecamatan'])?></h5>
        <h5 class="title text-center"><?= strtoupper($this->setting->sebutan_desa)?></h5>
        <h5 class="title text-center"><?= strtoupper($wil_atas['nama_desa'])?></h5>
      <?php else: ?>
        <h5 class="title text-center">PEMERINTAH <?= strtoupper($this->setting->sebutan_kabupaten)?></h5>
        <h5 class="title text-center"><?= strtoupper($desa['nama_kabupaten'])?></h5>
        <h5 class="title text-center"><?= strtoupper($this->setting->sebutan_kecamatan)?></h5>
        <h5 class="title text-center"><?= strtoupper($desa['nama_kecamatan'])?></h5>
        <h5 class="title text-center"><?= strtoupper($this->setting->sebutan_desa)?></h5>
        <h5 class="title text-center"><?= strtoupper($desa['nama_desa'])?></h5>
      <?php endif; ?>
    </td>
  </tr>
  <tr>
    <td align="center"></td>
  </tr>
  <tr>
    <td>
      <?php if ($wilayah == $nama_wilayah): ?>
        <h3 class="title text-center">PETA WILAYAH</h3>
        <h3 class="title text-center"><?= strtoupper($this->setting->sebutan_desa)?></h3>
        <h3 class="title text-center"><?= strtoupper($wil_atas['nama_desa'])?></h3>
      <?php elseif ($wilayah == ucwords($this->setting->sebutan_dusun)): ?>
        <h3 class="title text-center">PETA WILAYAH</h3>
        <h3 class="title text-center"><?= strtoupper($this->setting->sebutan_dusun)?></h3>
        <h3 class="title text-center"><?= strtoupper($wil_ini['dusun'])?></h3>
      <?php elseif ($wilayah == 'RW'): ?>
        <h3 class="title text-center">PETA WILAYAH</h3>
        <h3 class="title text-center">RW <?= $wil_ini['rw']?></h3>
        <h3 class="title text-center"><?= strtoupper($this->setting->sebutan_dusun)?> <?= strtoupper($wil_ini['dusun'])?></h3>
      <?php elseif ($wilayah == 'RT'): ?>
        <h3 class="title text-center">PETA WILAYAH</h3>
        <h3 class="title text-center">RT <?= $wil_ini['rt']?> RW <?= $wil_ini['rw']?> </h3>
        <h3 class="title text-center"><?= strtoupper($this->setting->sebutan_dusun)?> <?= strtoupper($wil_ini['dusun'])?></h3>
      <?php else: ?>
        <h3 class="title text-center">PETA WILAYAH</h3>
      <?php endif; ?>
    </td>
  </tr>
  <tr>
    <td align="center"></td>
  </tr>
  <tr>
    <td align="center"><img src="<?= asset('images/kompas.png')?>" alt="OpenSID"></td>
  </tr>
</table>
