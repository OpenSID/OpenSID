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
        <!-- TODO: generate view laporan -->
    </tbody>
</table>