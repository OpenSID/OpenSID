<tr>
    <td align="center">
        <?php if ($aksi != 'unduh'): ?>
            <img src="<?= gambar_desa($config['logo']); ?>" alt="" style="width:100px; height:auto">
        <?php endif; ?>
        <h1>PEMERINTAH <?= strtoupper($this->setting->sebutan_kabupaten . ' ' . $config['nama_kabupaten']); ?> </h1>
        <h1><?= strtoupper($this->setting->sebutan_kecamatan . ' ' . $config['nama_kecamatan']); ?> </h1>
        <h1><?= strtoupper($this->setting->sebutan_desa . ' ' . $config['nama_desa']); ?></h1>
    </td>
</tr>
<tr>
    <td style="padding: 5px 20px;">
        <hr style="border-bottom: 2px solid #000000; height:0px;">
    </td>
</tr>