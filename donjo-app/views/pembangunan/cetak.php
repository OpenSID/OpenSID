<style>
    img.gambar-pembangunan {
        width: 600px;
        height: 300px;
        display: block;
        margin-left: auto;
        margin-right: auto;
    }
</style>
<table>
    <tbody>
        <tr>
            <td>
                <img class="logo" src="<?= gambar_desa($config['logo']); ?>" alt="logo-desa">
                <h1 class="judul">DOKUMENTASI BIDANG PELAKSANAAN PEMBANGUNAN</h1>
                <h1 class="judul">
                    PEMERINTAH<?= strtoupper($this->setting->sebutan_kabupaten . ' ' . $config['nama_kabupaten'] . ' <br>' . $this->setting->sebutan_kecamatan . ' ' . $config['nama_kecamatan'] . ' <br>' . $this->setting->sebutan_desa . ' ' . $config['nama_desa']); ?>
                </h1>
            </td>
        </tr>
        <tr>
            <td>
                <hr class="garis">
            </td>
        </tr>
        <tr>
            <td>
                <strong>NAMA PEMBANGUNAN :</strong> <?= $pembangunan->judul ?><br>
                <strong>SUMBER DANA :</strong> <?= $pembangunan->sumber_dana ?><br>
                <strong>LOKASI PEMBANGUNAN :</strong> <?= $pembangunan->alamat ?><br>
                <strong>KETERANGAN :</strong> <?= $pembangunan->keterangan ?>
            </td>
        </tr>
        <tr>
            <td>&nbsp;</td>
        </tr>
        <?php foreach ($dokumentasi as $value) : ?>
            <tr>
                <td class="text-center">
                    <h4><?= $value->keterangan . ' ' . $value->persentase ?></h4>
                    <img class="gambar-pembangunan" src="<?= base_url() . LOKASI_GALERI . $value->gambar ?>" alt="<?= $pembangunan->judul ?>">
                </td>
            </tr>
            <tr>
                <td>&nbsp;</td>
            </tr>
        <?php endforeach ?>
    </tbody>
</table>