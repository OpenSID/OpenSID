<style type="text/css">
    .tengah {
        text-align: center
    }

    .atas {
        vertical-align: text-top;
    }

    .kiri {
        text-align: left
    }

    .kanan {
        text-align: right
    }

    .berdiri {
        writing-mode: vertical-lr;
    }
    .middle {
        vertical-align: middle;vertical-align: sub;
    }
    .bawah {
        vertical-align: bottom;
    }
    .kotak {
        vertical-align: middle;
        border: solid 1px #000000
    }

    .flex {
        display: flex;
    }

    .float-l {
        float: left
    }

    .float-r {
        float: right
    }

    .m-5 {
        margin: 5px
    }

    .p-5 {
        padding: 5px
    }

    .pl-5 {
        padding-left: 5px;
    }

    .pl-15 {
        padding-left: 15px;
    }

    .pl-25 {
        padding-left: 25px;
    }

    .ml-5 {
        margin-left: 5px;
    }

    .ml-15 {
        margin-left: 15px;
    }

    .ml-50 {
        margin-left: 50px;
    }

    .w-100f {
        width: 100%
    }

    .w-50f {
        width: 50%
    }

    .w-25f {
        width: 25%
    }

    .w-15 {
        width: 15px;
    }

    .w-20 {
        width: 20px;
    }

    .w-25 {
        width: 25px;
    }

    .w-30 {
        width: 30px;
    }

    .h-30 {
        height: 30px;
    }

    .h-23 {
        height: 20px;
    }

    .w-200 {
        width: 200px;
    }

    .w-500 {
        width: 500px;
    }

    .w-auto {
        width: auto;
    }

    .relative {
        position: relative;
    }

    .inline {
        display: inline;
    }

    td.inline {
        overflow: hidden;
    }

    h3 {
        margin: 0
    }

    table {
        border-collapse: collapse;
        border: solid 3px #000000;
        width: 50%;
    }

    td {
        border: solid #000000 1px;
        padding: 5px;
    }

    th {
        background: rgb(226, 226, 226);
        border: solid #000000;
        font-weight: 600;
        padding: 5px;
    }

    table table {
        border: none
    }

    table table td {
        border: none
    }

    .border-l {
        border-left: solid #000000 1px;
    }

    .border-r {
        border-right: solid #000000 1px;
    }

    .border-t {
        border-top: solid #000000 1px;
    }

    .border-b {
        border-bottom: solid #000000 1px;
    }

    .no-border {
        border: none
    }

    .border {
        border: #000000 1px solid;
    }

    .border-b-garis {
        border-bottom: #000000 1px dashed;
    }

    .clear {
        clear: both;
    }

    .lh-18 {
        line-height: 18px;
    }
</style>

<?php
    function tentukanJumlahTerpilih(array $list_kode, $value){
        $index = 0;
        $kode_terpilih = [];

        if($value == 0) {
            return $kode_terpilih;
        }

        while($value > 0 || $index >= count($list_kode)){
            if($value - $list_kode[$index] >= 0){
                $value -= $list_kode[$index];
                $kode_terpilih[] = $list_kode[$index];
            }
            $index++;
        }
        return $kode_terpilih;
    }
?>

<page orientation="portrait" orientation="landscape" format="A4" backtop="5mm" backbottom="5mm"
    style="font-family: Arial, Helvetica, sans-serif; font-size: 8pt">
    <page_header>
        <h4 class="tengah" style="margin-top:-10px">REGISTRASI SOSIAL EKONOMI 2022  </h4>
        <table style="width: 100%;border:none;margin-top:-30px">
            <tr>
                <td style="width: 50%;border:none"><b>RAHASIA</b></td>
                <td style="width: 50%;border:none" class="kanan">REGSOS-EK2022.K</td>
            </tr>
        </table>
    </page_header>
    <page_footer>
        <div style="text-align: right;    width: 100%">
            Dokumen. <?= $dtks->id ?>, halaman [[page_cu]]/[[page_nb]]
        </div>
    </page_footer>

    <table style="width: 100%">
        <tr>
            <th class="tengah" colspan="6" style="width: 100%"><b>I. KETERANGAN TEMPAT</b></th>
        </tr>
        <tr>
            <td style="width: 9%">101. Provinsi </td>
            <td style="width: 26%"><?= $prov ?></td>
            <td style="width: 8%">
                <?php for ($i = strlen($dtks->kode_provinsi) - 2; $i < strlen($dtks->kode_provinsi); $i++) : ?>
                <div class="w-25 h-23 inline kotak tengah">
                    <?php echo $dtks->kode_provinsi[$i] ?? '&nbsp;'; ?>&nbsp;
                </div>
                <?php endfor; ?>
            </td>
            <td style="width: 19%">108. Nama Kepala Keluarga (KK)</td>
            <td colspan="2" style="width: 29%">
                <?= strtoupper(substr($dtks->kepala_keluarga->nama, 0, 35)) ?>
            </td>
        </tr>
        <tr>
            <td>102. Kabupaten/Kota <sup>*)</sup></td>
            <td><?= $kab ?></td>
            <td>
                <?php for ($i = strlen($dtks->kode_kabupaten) - 2; $i < strlen($dtks->kode_kabupaten); $i++) : ?>
                <div class="w-25 h-23 inline kotak tengah">
                    <?php echo $dtks->kode_kabupaten[$i] ?? '&nbsp;'; ?>&nbsp;
                </div>
                <?php endfor; ?>
            </td>
            <td>109. Nomor Urut Bangunan Tempat Tinggal</td>
            <td colspan="2">
                <?php for ($i = strlen($dtks->no_urut_bangunan_tinggal) - 3; $i < strlen($dtks->no_urut_bangunan_tinggal); $i++) : ?>
                <div class="w-25 h-23 inline kotak tengah">
                    <?php echo $dtks->no_urut_bangunan_tinggal[$i] ?? '&nbsp;'; ?>&nbsp;
                </div>
                <?php endfor; ?>
            </td>
        </tr>
        <tr>
            <td>103. Kecamatan</td>
            <td><?= $kec ?></td>
            <td>
                <?php for ($i = strlen($dtks->kode_kecamatan) - 3; $i < strlen($dtks->kode_kecamatan); $i++) : ?>
                <div class="w-25 h-23 inline kotak tengah">
                    <?php echo $dtks->kode_kecamatan[$i] ?? '&nbsp;'; ?>&nbsp;
                </div>
                <?php endfor; ?>
            </td>
            <td>110. No Urut Keluarga Hasil Verifikasi</td>
            <td colspan="2">
                <?php for ($i = strlen($dtks->no_urut_keluarga_verif) - 3; $i < strlen($dtks->no_urut_keluarga_verif); $i++) : ?>
                <div class="w-25 h-23 inline kotak tengah">
                    <?php echo $dtks->no_urut_keluarga_verif[$i] ?? '&nbsp;'; ?>&nbsp;
                </div>
                <?php endfor; ?>
            </td>
        </tr>
        <tr>
            <td>104. Desa/Kelurahan<sup>*)</sup></td>
            <td><?= $desa ?></td>
            <td>
                <?php for ($i = strlen($dtks->kode_desa) - 3; $i < strlen($dtks->kode_desa); $i++) : ?>
                <div class="w-25 h-23 inline kotak tengah">
                    <?php echo $dtks->kode_desa[$i] ?? '&nbsp;'; ?>&nbsp;
                </div>
                <?php endfor; ?>
            </td>
            <td>111. Status Keluarga</td>
            <td colspan="2">
                <div class="w-25 h-23 inline kotak tengah"><?= $dtks->status_keluarga ?></div>
            </td>
        </tr>
        <tr>
            <td>105. Kode SLS/Non SLS</td>
            <td colspan="2" class="middle berdiri">
                <?php for ($i = strlen($dtks->kode_sls_non_sls) - 4; $i < strlen($dtks->kode_sls_non_sls); $i++) : ?>
                <div class="w-25 h-23 inline kotak tengah">
                    <?php echo $dtks->kode_sls_non_sls[$i] ?? '&nbsp;'; ?>&nbsp;
                </div>
                <?php endfor; ?>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                Kode Sub SLS
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <?php for ($i = strlen($dtks->kode_sub_sls) - 2; $i < strlen($dtks->kode_sub_sls); $i++) : ?>
                <div class="w-25 h-23 inline kotak tengah">
                    <?php echo $dtks->kode_sub_sls[$i] ?? '&nbsp;'; ?>&nbsp;
                </div>
                <?php endfor; ?>
            </td>
            <td>112. Jumlah Anggota Keluarga</td>
            <td colspan="2">
                <?php if (strlen($dtks->jumlah_anggota_dtks) == 1) : ?>
                    <div class="w-25 h-23 inline kotak tengah">0</div>
                    <div class="w-25 h-23 inline kotak tengah"><?= $dtks->jumlah_anggota_dtks ?? '&nbsp;' ?></div>
                <?php else : ?>
                    <div class="w-25 h-23 inline kotak tengah"><?= $dtks->jumlah_anggota_dtks[0] ?? '&nbsp;' ?></div>
                    <div class="w-25 h-23 inline kotak tengah"><?= $dtks->jumlah_anggota_dtks[1] ?? '&nbsp;' ?></div>
                <?php endif; ?>
            </td>
        </tr>
        <tr>
            <td>106. Nama SLS/Non SLS</td>
            <td colspan="2">
                <?= $dtks->nama_sls_non_sls ? substr($dtks->nama_sls_non_sls, 0, 35) : '....................................' ?>
            </td>
            <td>113. ID Landmark Wilkerstat</td>
            <td colspan="2">
                <?php for ($i = strlen($dtks->kode_landmark_wilkerstat) - 6; $i < strlen($dtks->kode_landmark_wilkerstat); $i++) : ?>
                    <div class="w-25 h-23 inline kotak tengah">
                        <?php echo $dtks->kode_landmark_wilkerstat[$i] ?? '&nbsp;'; ?>&nbsp;
                    </div>
                <?php endfor; ?>
            </td>
        </tr>

        <tr>
            <td rowspan="2" style="height: 10px;">107. Alamat (Jalan/Gang, <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Nomor Rumah)</td>
            <td rowspan="2" colspan="2" style="width: 30%; height:10px"><?php
                $i = 0;
                $panjang = 80;
                $dtks->alamat = strlen($dtks->alamat) > 250 ? substr($dtks->alamat, 0, 250) . '...' : $dtks->alamat;
                do {
                    echo substr($dtks->alamat, $i, $panjang) . '<br>';
                    $i += $panjang;
                } while (strlen($dtks->alamat) >= $i);
            ?></td>
            <td>114. Nomor Kartu Keluarga</td>
            <td colspan="2"style="width: 30%;">
                <?php for ($i = strlen($dtks->kepala_keluarga->keluarga->no_kk) - 16; $i < strlen($dtks->kepala_keluarga->keluarga->no_kk); $i++) : ?>
                    <?php if ($i % 8 === 0 && $i != -16 && $i != 0) {
                        echo '<br>';
                    } ?>
                    <div class="w-25 h-23 inline kotak tengah">
                        <?php echo $dtks->kepala_keluarga->keluarga->no_kk[$i] ?? '&nbsp;'; ?>&nbsp;
                    </div>
                <?php endfor; ?>
            </td>
        </tr>
        <tr>
            <td>115. Kode Kartu Keluarga</td>
            <td>
                0. KK Sesuai&nbsp;&nbsp;
                1. Keluarga Induk&nbsp;&nbsp;
                2. Keluarga pecahan
            </td>
            <td>
                <div class="w-25 h-23 inline kotak tengah">
                    <?php echo $dtks->kd_kk ?? '&nbsp;'; ?>&nbsp;
                </div>
            </td>
        </tr>
    </table>
    <br>
    <table style="width: 100%">
        <tr>
            <th class="tengah" colspan="5" style="width: 100%"><b>II. KETERANGAN PETUGAS</b></th>
        </tr>
        <tr>
            <td rowspan="2" style="width:12%">201. Tanggal pendataan : </td>
            <td class="tengah" style="width:11%">
                Tanggal
            </td>
            <td class="tengah" style="width:11%">
                Bulan
            </td>
            <td class="tengah" style="width:12%">
                Tahun
            </td>
            <td rowspan="3" class="tengah" style="width:50%">
                Saya menyatakan telah melaksanakan pendataan sesuai dengan prosedur
                <br><br><br><br><br>


                (<?= $dtks->nama_ppl ? strtoupper(substr($dtks->nama_ppl, 0, 30)) : '.................................' ?>)
                <br>
                Tanda Tangan PPL
            </td>
        </tr>
        <tr>
            <td>
                <?php $item = $dtks->tanggal_pendataan ? $dtks->tanggal_pendataan->format('d') : null; ?>
                <?php for ($i = strlen($item) - 2; $i < strlen($item); $i++) : ?>
                <div class="w-25 h-23 inline kotak tengah">
                    <?php echo $item[$i] ?? '&nbsp;'; ?>&nbsp;
                </div>
                <?php endfor; ?>
            </td>
            <td>
                <?php $item = $dtks->tanggal_pendataan ? $dtks->tanggal_pendataan->format('m') : null; ?>
                <?php for ($i = strlen($item) - 2; $i < strlen($item); $i++) : ?>
                <div class="w-25 h-23 inline kotak tengah">
                    <?php echo $item[$i] ?? '&nbsp;'; ?>&nbsp;
                </div>
                <?php endfor; ?>
            </td>
            <td>
                <?php $item = $dtks->tanggal_pendataan ? $dtks->tanggal_pendataan->format('Y') : null; ?>
                <?php for ($i = strlen($item) - 4; $i < strlen($item); $i++) : ?>
                <div class="w-25 h-23 inline kotak tengah">
                    <?php echo $item[$i] ?? '&nbsp;'; ?>&nbsp;
                </div>
                <?php endfor; ?>
            </td>
        </tr>
        <tr>
            <td>202. Nama PPL : </td>
            <td colspan="2">
                <?= $dtks->nama_ppl ? strtoupper(substr($dtks->nama_ppl, 0, 30)) : '.................................' ?>
            </td>
            <td>Kode<br>
                <?php $item = $dtks->kode_ppl; ?>
                <?php for ($i = strlen($item) - 4; $i < strlen($item); $i++) : ?>
                <div class="w-25 h-23 inline kotak tengah">
                    <?php echo $item[$i] ?? '&nbsp;'; ?>&nbsp;
                </div>
                <?php endfor; ?>
            </td>
        </tr>
        <tr>
            <td rowspan="2">203. Tanggal Pemeriksaan : </td>
            <td class="tengah">
                Tanggal
            </td>
            <td class="tengah">
                Bulan
            </td>
            <td class="tengah">
                Tahun
            </td>
            <td rowspan="3" class="tengah" >
                Saya menyatakan telah melaksanakan pemeriksaan sesuai dengan prosedur
                <br><br><br><br><br>


                (<?= $dtks->nama_pml ? strtoupper(substr($dtks->nama_pml, 0, 30)) : '.................................' ?>)
                <br>
                Tanda Tangan PML
            </td>
        </tr>
        <tr>
            <td>
                <?php $item = $dtks->tanggal_pemeriksaan ? $dtks->tanggal_pemeriksaan->format('d') : null; ?>
                <?php for ($i = strlen($item) - 2; $i < strlen($item); $i++) : ?>
                <div class="w-25 h-23 inline kotak tengah">
                    <?php echo $item[$i] ?? '&nbsp;'; ?>&nbsp;
                </div>
                <?php endfor; ?>
            </td>
            <td>
                <?php $item = $dtks->tanggal_pemeriksaan ? $dtks->tanggal_pemeriksaan->format('m') : null; ?>
                <?php for ($i = strlen($item) - 2; $i < strlen($item); $i++) : ?>
                <div class="w-25 h-23 inline kotak tengah">
                    <?php echo $item[$i] ?? '&nbsp;'; ?>&nbsp;
                </div>
                <?php endfor; ?>
            </td>
            <td>
                <?php $item = $dtks->tanggal_pemeriksaan ? $dtks->tanggal_pemeriksaan->format('Y') : null; ?>
                <?php for ($i = strlen($item) - 4; $i < strlen($item); $i++) : ?>
                <div class="w-25 h-23 inline kotak tengah">
                    <?php echo $item[$i] ?? '&nbsp;'; ?>&nbsp;
                </div>
                <?php endfor; ?>
            </td>
        </tr>
        <tr>
            <td>204. Nama PML : </td>
            <td colspan="2">
                <?= $dtks->nama_pml ? strtoupper(substr($dtks->nama_pml, 0, 30)) : '.................................' ?>
            </td>
            <td>Kode<br>
                <?php $item = $dtks->kode_pml; ?>
                <?php for ($i = strlen($item) - 3; $i < strlen($item); $i++) : ?>
                <div class="w-25 h-23 inline kotak tengah">
                    <?php echo $item[$i] ?? '&nbsp;'; ?>&nbsp;
                </div>
                <?php endfor; ?>
            </td>

        </tr>
        <tr>
            <td colspan="3" rowspan="2" class="no-border border-b border-l">
                205. Hasil pendataan keluarga:<br><br>
                1. Terisi lengkap<br>
                2. Tidak Terisi lengkap<br>
                3. Tidak ada responden yang dapat memberi jawaban <br>
                &nbsp;&nbsp;&nbsp;&nbsp;sampai akhir masa pendataan<br>
                4. Responden menolak<br>
                5. Keluarga Pindah/bangunan sensus sudah tidak ada<br><br>
            </td>
            <td rowspan="2" class="no-border border-b border-r">
                <div class="w-25 h-23 inline kotak tengah ml-50">
                    <?php echo $dtks->kd_hasil_pendataan_keluarga ?? '&nbsp;'; ?>&nbsp;
                </div>
                <br><br><br>
                Jika berkode 2, 3, 4, atau 5 <br> isi <b>Blok VI. Catatan</b><br>
            </td>
            <td class="tengah no-border border-r" style="width: 50%">
                Saya menyatakan bahwa informasi yang saya berikan adalah benar, <br>dan boleh dipergunakan untuk
                keperluan pemerintah
                <br><br><br><br><br>
                (<?= $dtks->nama_responden ? strtoupper(substr($dtks->nama_responden, 0, 30)) : '.................................' ?>)
                <br>
                Nama Lengkap & Tanda Tangan Responden
                <br>
            </td>
        </tr>
        <tr>
            <td class="no-border border-b border-r">
                Nomor Handphone Responden :
                <?= $dtks->no_hp_responden ? substr($dtks->no_hp_responden, 0, 30) : '.................................' ?>

            </td>
        </tr>
    </table>
    <br>
    <table style="width: 100%">
        <tr>
            <th class="tengah" colspan="6" style="width: 100%"><b>III. KETERANGAN PERUMAHAN</b></th>
        </tr>
        <tr>
            <td class="no-border border-b-garis border-r border-l">
                301.a. Status penguasaan bangunan tempat tinggal <br>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                yang ditempati
            </td>
            <td class="no-border border-b-garis">1. Milik sendiri<br>2. Kontrak/sewa</td>
            <td class="no-border border-b-garis">3. Bebas sewa<br>4. Dinas</td>
            <td class="no-border border-b-garis border-r" colspan="2">
                5. Lainnya
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <b>Jika berkode 2, 3, 4, atau 5 => 302</b>
            </td>
            <td class="no-border border-b-garis border-r">
                a.
                <div class="w-25 h-23 inline kotak tengah"><?= $dtks->kd_stat_bangunan_tinggal ?? '&nbsp;' ?></div>
            </td>
        </tr>
        <tr>
            <td class="no-border border-b border-r border-l">
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                b. <b>Jika 301.a berkode 1</b><br>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                Apa jenis bukti kepemilikan tanah <br>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                bangunan tempat tinggal yang dimiliki?
            </td>
            <td class="no-border border-b" colspan="2">1. SHM atas Nama Anggota Keluarga<br>2. SHM bukan a.n Anggota Keluarga dengan
                perjanjian
                pemanfaatan
                tertulis<br>3. SHM bukan a.n Anggota Keluarga tanpa perjanjian pemanfaatan tertulis</td>
            <td class="no-border border-b border-r" colspan="2">4. Sertfikat selain SHM (SHGB, SHRS)<br>5. Surat
                bukti lainnya (Girik, Letter C, dll)<br>6. Tidak punya</td>
            <td class="no-border border-b border-r">
                b.
                <div class="w-25 h-23 inline kotak tengah"><?= $dtks->kd_sertiv_lahan_milik ?? '&nbsp;' ?></div>
            </td>
        </tr>
        <tr>
            <td>302. Luas Lantai</td>
            <td colspan="4" style="width: 60%">.... m<sup>2</sup></td>
            <td>
                <?php if (strlen($dtks->luas_lantai) == 1) : ?>
                <div class="w-25 h-23 inline kotak tengah">0</div>
                <div class="w-25 h-23 inline kotak tengah">0</div>
                <div class="w-25 h-23 inline kotak tengah"><?= $dtks->luas_lantai ?? '&nbsp;' ?></div>
                <?php elseif (strlen($dtks->luas_lantai) == 2) : ?>
                <div class="w-25 h-23 inline kotak tengah">0</div>
                <div class="w-25 h-23 inline kotak tengah"><?= $dtks->luas_lantai[0] ?? '&nbsp;' ?></div>
                <div class="w-25 h-23 inline kotak tengah"><?= $dtks->luas_lantai[1] ?? '&nbsp;' ?></div>
                <?php elseif (strlen($dtks->luas_lantai) == 3) : ?>
                <div class="w-25 h-23 inline kotak tengah"><?= $dtks->luas_lantai[0] ?? '&nbsp;' ?></div>
                <div class="w-25 h-23 inline kotak tengah"><?= $dtks->luas_lantai[1] ?? '&nbsp;' ?></div>
                <div class="w-25 h-23 inline kotak tengah"><?= $dtks->luas_lantai[2] ?? '&nbsp;' ?></div>
                <?php endif; ?>
            </td>
        </tr>
        <tr>
            <td>303. Jenis lantai terluas</td>
            <td class="no-border border-b">
                1. Marmer/granit
                <br>2. Keramik
            </td>
            <td class="no-border border-b">
                3. Parket/vinil/karpet
                <br>4. Ubin/tegel/teraso
            </td>
            <td class="no-border border-b">
                5. Kayu/papan
                <br>6. Semen/bata merah
            </td>
            <td class="no-border border-b border-r">
                7. Bambu
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                9. Lainnya
                <br>8. Tanah
            </td>
            <td>
                <div class="w-25 h-23 inline kotak tengah"><?= $dtks->kd_jenis_lantai_terluas ?? '&nbsp;' ?></div>
            </td>
        </tr>
        <tr>
            <td>304. Jenis dinding terluas</td>
            <td class="no-border border-b">1. Tembok
                <br>2. Plesteran anyaman bambu/kawat
            </td>
            <td class="no-border border-b">3. Kayu/papan/Gypsum/GRC/Calciboard
                <br>4. Anyaman bambu
            </td>
            <td class="no-border border-b">
                5. Batang kayu
                <br>6. Bambu
            </td>
            <td class="no-border border-b border-r">
                7. Lainnya
            </td>
            <td class="no-border border-b border-r">
                <div class="w-25 h-23 inline kotak tengah"><?= $dtks->kd_jenis_dinding ?? '&nbsp;' ?></div>
            </td>
        </tr>
        <tr>
            <td>305  . Jenis atap terluas</td>
            <td class="no-border border-b">1. Beton
                <br>2. Genteng
            </td>
            <td class="no-border border-b">
                3. Seng
                <br>4. Asbes
            </td>
            <td class="no-border border-b">
                5. Bambu
                <br>6. Kayu/sirap
            </td>
            <td class="no-border border-b border-r">
                7. Jerami/ijuk/daun-daunan/rumbia
                <br>8. Lainnya
            </td>
            <td class="no-border border-b border-r">
                <div class="w-25 h-23 inline kotak tengah"><?= $dtks->kd_jenis_atap ?? '&nbsp;' ?></div>
            </td>
        </tr>
        <tr>
            <td class="no-border border-b-garis border-r border-l">306.a. Sumber air minum</td>
            <td class="no-border border-b-garis">1. Air kemasan bermerk
                <br>2. Air isi ulang
                <br>3. Leding
            </td>
            <td class="no-border border-b-garis">4. Sumur bor/pompa
                <br>5. Sumur terlindung
                <br>6. Sumur tak terlindung
            </td>
            <td class="no-border border-b-garis border-r" colspan="2">
                7. Mata air terlindung
                <?=str_repeat('&nbsp;', 13)?>8. Mata air tak terlindung
                <br>9. Air permukaan (sungai/danau/waduk/kolam/irigasi)
                <br>10. Air hujan
                <?=str_repeat('&nbsp;', 27)?>11. Lainnya
            </td>
            <td>a.
                <div class="w-25 h-23 inline kotak tengah"><?= $dtks->kd_sumber_air_minum ?? '&nbsp;' ?></div>
            </td>
        </tr>
        <tr>
            <td>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                b. <b>Jika 306.a berkode 4, 5, 6, 7, atau 8, </b><br>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                Seberapa jauh jarak sumber air minum utama ke <br>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                tempat penampungan limbah/kotoran/tinja terdekat?
            </td>
            <td class="no-border border-b">1. < 10 meter</td>
            <td class="no-border border-b">2. >= 10 meter</td>
            <td colspan="2" class="no-border border-b border-r">8. Tidak tahu</td>
            <td>b.
                <div class="w-25 h-23 inline kotak tengah"><?= $dtks->kd_jarak_sumber_air_ke_tpl ?? '&nbsp;' ?></div>
            </td>
        </tr>
        <tr>
            <td class="no-border border-b-garis border-l border-r">307.a. Sumber penerangan utama</td>
            <td class="no-border border-b-garis">1. Listrik PLN dengan meteran</td>
            <td class="no-border border-b-garis">2. Listrik PLN tanpa meteran</td>
            <td class="no-border border-b-garis">3. Listrik non PLN</td>
            <td class="no-border border-b-garis border-r">4. Bukan listrik</td>
            <td class="no-border border-b-garis border-r">
                <div class="w-25 h-23 inline kotak tengah"><?= $dtks->kd_sumber_penerangan_utama ?? '&nbsp;' ?></div>
            </td>
        </tr>
        <tr>
            <td class="no-border border-b border-l border-r">
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                b. <b>Jika 307.a berkode 1 : </b>
                Daya terpasang dirumah ini:<br>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                &nbsp;&nbsp;&nbsp;
                <b>(Isikan daya untuk setiap meteran<br>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                &nbsp;&nbsp;&nbsp;
                 yang terpasang)</b>
            </td>
            <td class="no-border border-b">
                1. 450 watt<br>
                2. 900 watt<br>
                3. 1.300 watt<br>
            </td>
            <td class="no-border border-b">
                4. 2.200 watt<br>
                5. > 2.200 watt
            </td>
            <td class="no-border border-b border-r" colspan="3">
                <table style="display: inline;">
                    <tr>
                        <th>Meteran 1</th>
                        <td class="border">
                            b.1.
                            <div class="w-25 h-23 inline kotak tengah"><?= $dtks->kd_daya_terpasang ?? '&nbsp;' ?></div>
                        </td>
                        <td class="border-r"></td>
                        <th class="border">Meteran 2</th>
                        <td class="border">
                            b.2.
                            <div class="w-25 h-23 inline kotak tengah"><?= $dtks->kd_daya_terpasang2 ?? '&nbsp;' ?></div>
                        </td>
                        <td class="border-r"></td>
                        <th class="border">Meteran 3</th>
                        <td class="border">
                            b.3.
                            <div class="w-25 h-23 inline kotak tengah"><?= $dtks->kd_daya_terpasang3 ?? '&nbsp;' ?></div>
                        </td>
                    </tr>
                </table>

            </td>
        </tr>
        <tr>
            <td>308. Bahan bakar/energi utama untuk memasak</td>
            <td class="no-border border-b">01. Listrik
                <br>02. Gas elpiji 5,5kg/Blue gaz
                <br>03. Gas elpiji 12 kg
            </td>
            <td class="no-border border-b">04. Gas elpiji 3 kg
                <br>05. Gas kota/meteran PGN
                <br>06. Biogas
            </td>
            <td class="no-border border-b">07. Minyak tanah
                <br>08. Briket
                <br>09. Arang
            </td>
            <td class="no-border border-b border-r">10. Kayu bakar
                <br>11. Lainnya
                <br>00. Tidak memasak di rumah
            </td>
            <td>
                <?php if (strlen($dtks->kd_bahan_bakar_memasak) == 1) : ?>
                    <div class="w-25 h-23 inline kotak tengah">0</div>
                    <div class="w-25 h-23 inline kotak tengah"><?= $dtks->kd_bahan_bakar_memasak ?? '&nbsp;' ?></div>
                <?php else : ?>
                    <div class="w-25 h-23 inline kotak tengah"><?= $dtks->kd_bahan_bakar_memasak[0] ?? '&nbsp;' ?></div>
                    <div class="w-25 h-23 inline kotak tengah"><?= $dtks->kd_bahan_bakar_memasak[1] ?? '&nbsp;' ?></div>
                <?php endif; ?>
            </td>
        </tr>
        <tr>
            <td class="border-b-garis">
                309a. Kepemilikan dan penggunaan fasilitas tempat <br>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                &nbsp;&nbsp;
                buang air besar
            </td>
            <td class="no-border border-b-garis" colspan="2">1. Ada, digunakan hanya Anggota Keluarga sendiri
                <br>2. Ada, digunakan bersama Anggota Keluarga rumah tangga tertentu
                <br>3. Ada, di MCK komunal
            </td>
            <td class="no-border border-b-garis border-r" colspan="2">4. Ada, di MCK umum/siapapun menggunakan
                <br>5. Ada, Anggota Keluarga tidak menggunakan
                <br>6. Tidak ada
            </td>
            <td>
                <div class="w-25 h-23 inline kotak tengah"><?= $dtks->kd_fasilitas_tempat_bab ?? '&nbsp;' ?></div>
            </td>
        </tr>
        <tr>
            <td>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                b. <b>jika 309.a berkode 1, 2, atau 3.</b> Jenis kloset:
            </td>
            <td class="no-border border-b">1. Leher angsa
            </td>
            <td class="no-border border-b">2. Plengsengan dengan tutup
            </td>
            <td class="no-border border-b">3. Plengsengan tanpa tutup</td>
            <td class="no-border border-b border-r">4. Cemplung/cubluk</td>
            <td>
                <div class="w-25 h-23 inline kotak tengah"><?= $dtks->kd_jenis_kloset ?? '&nbsp;' ?></div>
            </td>
        </tr>
        <tr>
            <td>310. Tempat pembuangan akhir tinja:</td>
            <td class="no-border border-b" colspan="2">1. Tangki septik
                <br>2. IPAL
                <br>3. Kolam/sawah/sungai/danau/laut
            </td>
            <td class="no-border border-b border-r" colspan="2">4. Lubang tanah
                <br>5. Pantai/tanah lapang/kebun
                <br>6. Lainnya
            </td>
            <td>
                <div class="w-25 h-23 inline kotak tengah"><?= $dtks->kd_pembuangan_akhir_tinja ?? '&nbsp;' ?></div>
            </td>
        </tr>
    </table>
    <br>
    <table style="width: 100%">
        <tr>
            <th class="tengah" style="width: 100%"><b>VI. CATATAN</b></th>
        </tr>
        <tr>
            <td class="berdiri" style="width: 100%; height:50px">
                <?=(strlen($dtks->catatan) >= 1000) ? substr($dtks->catatan, 0, 1000) . '...' : $dtks->catatan ?>
            </td>
        </tr>
    </table>
    <br>
    <br>
    <br>
    <table style="width: 100%">
        <tr>
            <th class="tengah" style="width: 100%"><b>V. KEIKUTSERTAAN PROGRAM, KEPEMILIKAN ASET DAN LAYANAN</b></th>
        </tr>
    </table>

    <table style="width: 100%">
        <tr>
            <td colspan="4" style="width: 100%">501. Dalam satu tahun terakhir, apakah keluarga menerima program berikut?</td>
        </tr>
        <tr>
            <td class="tengah" colspan="2" >Jenis Program</td>
            <td>
                Kepesertaan (Isikan Kode)
                <?=str_repeat('&nbsp;', 6)?>
                1. Ya
                <?=str_repeat('&nbsp;', 6)?>
                2. Tidak
            </td>
            <td class="tengah">
                Periode Terakhir Mendapatkan Program
                (Bulan/Tahun)
            </td>
        </tr>
        <tr>
            <td class="tengah"colspan="2" >(1)</td>
            <td class="tengah">(2)</td>
            <td class="tengah">(3)</td>
        </tr>
        <tr>
            <td colspan="2"><?=str_repeat('&nbsp;', 6)?>a. Program Bantuan Sosial Sembako/ BPNT</td>
            <td>
                <?=str_repeat('&nbsp;', 37)?><div class="w-25 h-23 inline kotak tengah"><?= $dtks->kd_bss_bnpt ?? '&nbsp;' ?></div>
            </td>
            <td>
                <?=str_repeat('&nbsp;', 15)?>
                <?php if (strlen($dtks->bulan_bss_bnpt) == 1) : ?>
                <div class="w-25 h-23 inline kotak tengah">0</div>
                <div class="w-25 h-23 inline kotak tengah"><?= $dtks->bulan_bss_bnpt ?? '&nbsp;' ?></div>
                <?php else : ?>
                <div class="w-25 h-23 inline kotak tengah"><?= $dtks->bulan_bss_bnpt[0] ?? '&nbsp;' ?></div>
                <div class="w-25 h-23 inline kotak tengah"><?= $dtks->bulan_bss_bnpt[1] ?? '&nbsp;' ?></div>
                <?php endif; ?>
                &nbsp;/&nbsp;
                <div class="w-25 h-23 inline kotak tengah"><?= $dtks->tahun_bss_bnpt[0] ?? '&nbsp;' ?></div>
                <div class="w-25 h-23 inline kotak tengah"><?= $dtks->tahun_bss_bnpt[1] ?? '&nbsp;' ?></div>
                <div class="w-25 h-23 inline kotak tengah"><?= $dtks->tahun_bss_bnpt[2] ?? '&nbsp;' ?></div>
                <div class="w-25 h-23 inline kotak tengah"><?= $dtks->tahun_bss_bnpt[3] ?? '&nbsp;' ?></div>
            </td>
        </tr>
        <tr>
            <td colspan="2"><?=str_repeat('&nbsp;', 6)?>b. Program Keluarga Harapan (PKH)</td>
            <td>
                <?=str_repeat('&nbsp;', 37)?><div class="w-25 h-23 inline kotak tengah"><?= $dtks->kd_pkh ?? '&nbsp;' ?></div>
            </td>
            <td>
                <?=str_repeat('&nbsp;', 15)?>
                <?php if (strlen($dtks->bulan_pkh) == 1) : ?>
                <div class="w-25 h-23 inline kotak tengah">0</div>
                <div class="w-25 h-23 inline kotak tengah"><?= $dtks->bulan_pkh ?? '&nbsp;' ?></div>
                <?php else : ?>
                <div class="w-25 h-23 inline kotak tengah"><?= $dtks->bulan_pkh[0] ?? '&nbsp;' ?></div>
                <div class="w-25 h-23 inline kotak tengah"><?= $dtks->bulan_pkh[1] ?? '&nbsp;' ?></div>
                <?php endif; ?>
                &nbsp;/&nbsp;
                <div class="w-25 h-23 inline kotak tengah"><?= $dtks->tahun_pkh[0] ?? '&nbsp;' ?></div>
                <div class="w-25 h-23 inline kotak tengah"><?= $dtks->tahun_pkh[1] ?? '&nbsp;' ?></div>
                <div class="w-25 h-23 inline kotak tengah"><?= $dtks->tahun_pkh[2] ?? '&nbsp;' ?></div>
                <div class="w-25 h-23 inline kotak tengah"><?= $dtks->tahun_pkh[3] ?? '&nbsp;' ?></div>
            </td>
        </tr>
        <tr>
            <td colspan="2"><?=str_repeat('&nbsp;', 6)?>c. Program Bantuan Langsung Tunai (BLT) Dana Desa</td>
            <td>
                <?=str_repeat('&nbsp;', 37)?><div class="w-25 h-23 inline kotak tengah"><?= $dtks->kd_blt_dana_desa ?? '&nbsp;' ?></div>
            </td>
            <td>
                <?=str_repeat('&nbsp;', 15)?>
                <?php if (strlen($dtks->bulan_blt_dana_desa) == 1) : ?>
                <div class="w-25 h-23 inline kotak tengah">0</div>
                <div class="w-25 h-23 inline kotak tengah"><?= $dtks->bulan_blt_dana_desa ?? '&nbsp;' ?></div>
                <?php else : ?>
                <div class="w-25 h-23 inline kotak tengah"><?= $dtks->bulan_blt_dana_desa[0] ?? '&nbsp;' ?></div>
                <div class="w-25 h-23 inline kotak tengah"><?= $dtks->bulan_blt_dana_desa[1] ?? '&nbsp;' ?></div>
                <?php endif; ?>
                &nbsp;/&nbsp;
                <div class="w-25 h-23 inline kotak tengah"><?= $dtks->tahun_blt_dana_desa[0] ?? '&nbsp;' ?></div>
                <div class="w-25 h-23 inline kotak tengah"><?= $dtks->tahun_blt_dana_desa[1] ?? '&nbsp;' ?></div>
                <div class="w-25 h-23 inline kotak tengah"><?= $dtks->tahun_blt_dana_desa[2] ?? '&nbsp;' ?></div>
                <div class="w-25 h-23 inline kotak tengah"><?= $dtks->tahun_blt_dana_desa[3] ?? '&nbsp;' ?></div>
            </td>
        </tr>
        <tr>
            <td colspan="2"><?=str_repeat('&nbsp;', 6)?>d. Program Subsidi Listrik (gratis/pemotongan biaya)</td>
            <td>
                <?=str_repeat('&nbsp;', 37)?><div class="w-25 h-23 inline kotak tengah"><?= $dtks->kd_subsidi_listrik ?? '&nbsp;' ?></div>
            </td>
            <td>
                <?=str_repeat('&nbsp;', 15)?>
                <?php if (strlen($dtks->bulan_subsidi_listrik) == 1) : ?>
                <div class="w-25 h-23 inline kotak tengah">0</div>
                <div class="w-25 h-23 inline kotak tengah"><?= $dtks->bulan_subsidi_listrik ?? '&nbsp;' ?></div>
                <?php else : ?>
                <div class="w-25 h-23 inline kotak tengah"><?= $dtks->bulan_subsidi_listrik[0] ?? '&nbsp;' ?></div>
                <div class="w-25 h-23 inline kotak tengah"><?= $dtks->bulan_subsidi_listrik[1] ?? '&nbsp;' ?></div>
                <?php endif; ?>
                &nbsp;/&nbsp;
                <div class="w-25 h-23 inline kotak tengah"><?= $dtks->tahun_subsidi_listrik[0] ?? '&nbsp;' ?></div>
                <div class="w-25 h-23 inline kotak tengah"><?= $dtks->tahun_subsidi_listrik[1] ?? '&nbsp;' ?></div>
                <div class="w-25 h-23 inline kotak tengah"><?= $dtks->tahun_subsidi_listrik[2] ?? '&nbsp;' ?></div>
                <div class="w-25 h-23 inline kotak tengah"><?= $dtks->tahun_subsidi_listrik[3] ?? '&nbsp;' ?></div>
            </td>
        </tr>
        <tr>
            <td colspan="2"><?=str_repeat('&nbsp;', 6)?>e. Program Bantuan Pemerintah Daerah</td>
            <td>
                <?=str_repeat('&nbsp;', 37)?><div class="w-25 h-23 inline kotak tengah"><?= $dtks->kd_bantuan_pemda ?? '&nbsp;' ?></div>
            </td>
            <td>
                <?=str_repeat('&nbsp;', 15)?>
                <?php if (strlen($dtks->bulan_bantuan_pemda) == 1) : ?>
                <div class="w-25 h-23 inline kotak tengah">0</div>
                <div class="w-25 h-23 inline kotak tengah"><?= $dtks->bulan_bantuan_pemda ?? '&nbsp;' ?></div>
                <?php else : ?>
                <div class="w-25 h-23 inline kotak tengah"><?= $dtks->bulan_bantuan_pemda[0] ?? '&nbsp;' ?></div>
                <div class="w-25 h-23 inline kotak tengah"><?= $dtks->bulan_bantuan_pemda[1] ?? '&nbsp;' ?></div>
                <?php endif; ?>
                &nbsp;/&nbsp;
                <div class="w-25 h-23 inline kotak tengah"><?= $dtks->tahun_bantuan_pemda[0] ?? '&nbsp;' ?></div>
                <div class="w-25 h-23 inline kotak tengah"><?= $dtks->tahun_bantuan_pemda[1] ?? '&nbsp;' ?></div>
                <div class="w-25 h-23 inline kotak tengah"><?= $dtks->tahun_bantuan_pemda[2] ?? '&nbsp;' ?></div>
                <div class="w-25 h-23 inline kotak tengah"><?= $dtks->tahun_bantuan_pemda[3] ?? '&nbsp;' ?></div>
            </td>
        </tr>
        <tr>
            <td colspan="2"><?=str_repeat('&nbsp;', 6)?>f. Program Bantuan Supsidi Pupuk</td>
            <td>
                <?=str_repeat('&nbsp;', 37)?><div class="w-25 h-23 inline kotak tengah"><?= $dtks->kd_subsidi_pupuk ?? '&nbsp;' ?></div>
            </td>
            <td>
                <?=str_repeat('&nbsp;', 15)?>
                <?php if (strlen($dtks->bulan_subsidi_pupuk) == 1) : ?>
                <div class="w-25 h-23 inline kotak tengah">0</div>
                <div class="w-25 h-23 inline kotak tengah"><?= $dtks->bulan_subsidi_pupuk ?? '&nbsp;' ?></div>
                <?php else : ?>
                <div class="w-25 h-23 inline kotak tengah"><?= $dtks->bulan_subsidi_pupuk[0] ?? '&nbsp;' ?></div>
                <div class="w-25 h-23 inline kotak tengah"><?= $dtks->bulan_subsidi_pupuk[1] ?? '&nbsp;' ?></div>
                <?php endif; ?>
                &nbsp;/&nbsp;
                <div class="w-25 h-23 inline kotak tengah"><?= $dtks->tahun_subsidi_pupuk[0] ?? '&nbsp;' ?></div>
                <div class="w-25 h-23 inline kotak tengah"><?= $dtks->tahun_subsidi_pupuk[1] ?? '&nbsp;' ?></div>
                <div class="w-25 h-23 inline kotak tengah"><?= $dtks->tahun_subsidi_pupuk[2] ?? '&nbsp;' ?></div>
                <div class="w-25 h-23 inline kotak tengah"><?= $dtks->tahun_subsidi_pupuk[3] ?? '&nbsp;' ?></div>
            </td>
        </tr>
        <tr>
            <td colspan="2"><?=str_repeat('&nbsp;', 6)?>g. Program Bantuan LPG</td>
            <td>
                <?=str_repeat('&nbsp;', 37)?><div class="w-25 h-23 inline kotak tengah"><?= $dtks->kd_subsidi_lpg ?? '&nbsp;' ?></div>
            </td>
            <td>
                <?=str_repeat('&nbsp;', 15)?>
                <?php if (strlen($dtks->bulan_subsidi_lpg) == 1) : ?>
                <div class="w-25 h-23 inline kotak tengah">0</div>
                <div class="w-25 h-23 inline kotak tengah"><?= $dtks->bulan_subsidi_lpg ?? '&nbsp;' ?></div>
                <?php else : ?>
                <div class="w-25 h-23 inline kotak tengah"><?= $dtks->bulan_subsidi_lpg[0] ?? '&nbsp;' ?></div>
                <div class="w-25 h-23 inline kotak tengah"><?= $dtks->bulan_subsidi_lpg[1] ?? '&nbsp;' ?></div>
                <?php endif; ?>
                &nbsp;/&nbsp;
                <div class="w-25 h-23 inline kotak tengah"><?= $dtks->tahun_subsidi_lpg[0] ?? '&nbsp;' ?></div>
                <div class="w-25 h-23 inline kotak tengah"><?= $dtks->tahun_subsidi_lpg[1] ?? '&nbsp;' ?></div>
                <div class="w-25 h-23 inline kotak tengah"><?= $dtks->tahun_subsidi_lpg[2] ?? '&nbsp;' ?></div>
                <div class="w-25 h-23 inline kotak tengah"><?= $dtks->tahun_subsidi_lpg[3] ?? '&nbsp;' ?></div>
            </td>
        </tr>
    </table>
    <table style="width: 100%">
        <tr>
            <td colspan="6" style="width: 100%" class="no-border border-t border-l border-r">
                502. Keluarga memiliki sendiri aset bergerak sebagai berikut: (isikan kode)
                <?=str_repeat('&nbsp;', 25)?><b>Kode 502.a - 502.n</b>
                <?=str_repeat('&nbsp;', 6)?>1. Ya
                <?=str_repeat('&nbsp;', 6)?>2. Tidak
            </td>
        </tr>
        <tr>
            <td class="lh-18 no-border border-l border-b">
                a. Tabung gas 5,5 kg atau lebih
                <br>b. Lemari es/kulkas
                <br>c. AC
                <br>d. Pemanas air (water heater)
                <br>e. Telepon rumah (PSTN)
            </td>
            <td class="lh-18 no-border border-b">
                    a. <div class="w-25 h-23 inline kotak tengah"><?= $dtks->kd_tabung_gas_5_5_kg ?? '&nbsp;' ?></div>
                <br>b. <div class="w-25 h-23 inline kotak tengah"><?= $dtks->kd_lemari_es ?? '&nbsp;' ?></div>
                <br>c. <div class="w-25 h-23 inline kotak tengah"><?= $dtks->kd_ac ?? '&nbsp;' ?></div>
                <br>d. <div class="w-25 h-23 inline kotak tengah"><?= $dtks->kd_pemanas_air ?? '&nbsp;' ?></div>
                <br>e. <div class="w-25 h-23 inline kotak tengah"><?= $dtks->kd_telepon_rumah ?? '&nbsp;' ?></div>
            </td>
            <td class="lh-18 no-border border-l border-b">
                f. Televisi layar datar min. 30 inchi
                <br>g. Emas/perhiasan (senilai 10 gram emas)
                <br>h. Komputer/laptop
                <br>i. Sepeda Motor
                <br>j. Sepeda
            </td>
            <td class="lh-18 no-border border-b ">
                f.<?=str_repeat('&nbsp;', 2)?><div class="w-25 h-23 inline kotak tengah"><?= $dtks->kd_televisi ?? '&nbsp;' ?></div>
                <br>
                g.<?=str_repeat('&nbsp;', 1)?><div class="w-25 h-23 inline kotak tengah"><?= $dtks->kd_perhiasan_10_gr_emas ?? '&nbsp;' ?></div>
                <br>
                h.<?=str_repeat('&nbsp;', 1)?><div class="w-25 h-23 inline kotak tengah"><?= $dtks->kd_komputer_laptop ?? '&nbsp;' ?></div>
                <br>
                i.<?=str_repeat('&nbsp;', 2)?><div class="w-25 h-23 inline kotak tengah"><?= $dtks->kd_sepeda_motor ?? '&nbsp;' ?></div>
                <br>
                j.<?=str_repeat('&nbsp;', 2)?><div class="w-25 h-23 inline kotak tengah"><?= $dtks->kd_sepeda ?? '&nbsp;' ?></div>
            </td>
            <td class="lh-18 no-border border-l border-b">
                k. Mobil
                <br>l. Perahu
                <br>m. Kapal/ Perahu Motor
                <br>n. Smartphone
            </td>
            <td class="lh-18 no-border border-b border-r">
                k.<?=str_repeat('&nbsp;', 1)?><div class="w-25 h-23 inline kotak tengah"><?= $dtks->kd_mobil ?? '&nbsp;' ?></div>
                <br>
                l.<?=str_repeat('&nbsp;', 2)?><div class="w-25 h-23 inline kotak tengah"><?= $dtks->kd_perahu ?? '&nbsp;' ?></div>
                <br>
                m.<?=str_repeat('&nbsp;', 0)?><div class="w-25 h-23 inline kotak tengah"><?= $dtks->kd_kapal_perahu_motor ?? '&nbsp;' ?></div>
                <br>
                n.<?=str_repeat('&nbsp;', 1)?><div class="w-25 h-23 inline kotak tengah"><?= $dtks->kd_smartphone ?? '&nbsp;' ?></div>
                <br>
            </td>
        </tr>
    </table>
    <table style="width: 100%">
        <tr>
            <td class="no-border border-l border-r" style="width: 100%">
            503. Keluarga memiliki aset tidak bergerak sebagai berikut: (isikan kode)
            <?=str_repeat('&nbsp;', 25)?>
            <b>Kode</b>
            <?=str_repeat('&nbsp;', 6)?>
            1. Ya
            <?=str_repeat('&nbsp;', 6)?>
            2. Tidak
            </td>
        </tr>
        <tr>
            <td class="no-border border-r border-l lh-18">
                a.Lahan (selain yang ditempati)
                <?=str_repeat('&nbsp;', 6)?>
                <div class="w-25 h-23 inline kotak tengah"><?= $dtks->kd_lahan ?? '&nbsp;' ?></div>
                <?=str_repeat('&nbsp;', 25)?>
                b. Rumah/bangunan di tempat lain
                <?=str_repeat('&nbsp;', 6)?>
                <div class="w-25 h-23 inline kotak tengah"><?= $dtks->kd_rumah_ditempat_lain ?? '&nbsp;' ?></div>
            </td>
        </tr>
    </table>
    <table style="width: 100%">
        <tr>
            <td colspan="10" class="no-border border-r border-l border-t" style="width: 100%">504. Jumlah ternak yang dimiliki (ekor):</td>
        </tr>
        <tr>
            <td class="no-border border-b border-l">
                a.Sapi
            </td>
            <td class="no-border border-b border-r">
                <?php if (strlen($dtks->jumlah_sapi) == 1) : ?>
                <div class="w-25 h-23 inline kotak tengah">0</div>
                <div class="w-25 h-23 inline kotak tengah">0</div>
                <div class="w-25 h-23 inline kotak tengah"><?= $dtks->jumlah_sapi ?? '&nbsp;' ?></div>
                <?php elseif (strlen($dtks->jumlah_sapi) == 2) : ?>
                <div class="w-25 h-23 inline kotak tengah">0</div>
                <div class="w-25 h-23 inline kotak tengah"><?= $dtks->jumlah_sapi[0] ?? '&nbsp;' ?></div>
                <div class="w-25 h-23 inline kotak tengah"><?= $dtks->jumlah_sapi[1] ?? '&nbsp;' ?></div>
                <?php else : ?>
                <div class="w-25 h-23 inline kotak tengah"><?= $dtks->jumlah_sapi[0] ?? '&nbsp;' ?></div>
                <div class="w-25 h-23 inline kotak tengah"><?= $dtks->jumlah_sapi[1] ?? '&nbsp;' ?></div>
                <div class="w-25 h-23 inline kotak tengah"><?= $dtks->jumlah_sapi[2] ?? '&nbsp;' ?></div>
                <?php endif; ?>
            </td>

            <td class="no-border border-b">
                b.Kerbau
            </td>
            <td class="no-border border-b border-r">
                <?php if (strlen($dtks->jumlah_kerbau) == 1) : ?>
                <div class="w-25 h-23 inline kotak tengah">0</div>
                <div class="w-25 h-23 inline kotak tengah">0</div>
                <div class="w-25 h-23 inline kotak tengah"><?= $dtks->jumlah_kerbau ?? '&nbsp;' ?></div>
                <?php elseif (strlen($dtks->jumlah_kerbau) == 2) : ?>
                <div class="w-25 h-23 inline kotak tengah">0</div>
                <div class="w-25 h-23 inline kotak tengah"><?= $dtks->jumlah_kerbau[0] ?? '&nbsp;' ?></div>
                <div class="w-25 h-23 inline kotak tengah"><?= $dtks->jumlah_kerbau[1] ?? '&nbsp;' ?></div>
                <?php else : ?>
                <div class="w-25 h-23 inline kotak tengah"><?= $dtks->jumlah_kerbau[0] ?? '&nbsp;' ?></div>
                <div class="w-25 h-23 inline kotak tengah"><?= $dtks->jumlah_kerbau[1] ?? '&nbsp;' ?></div>
                <div class="w-25 h-23 inline kotak tengah"><?= $dtks->jumlah_kerbau[2] ?? '&nbsp;' ?></div>
                <?php endif; ?>
            </td>

            <td class="no-border border-b">
                c.Kuda
            </td>
            <td class="no-border border-b border-r">
                <?php if (strlen($dtks->jumlah_kuda) == 1) : ?>
                <div class="w-25 h-23 inline kotak tengah">0</div>
                <div class="w-25 h-23 inline kotak tengah">0</div>
                <div class="w-25 h-23 inline kotak tengah"><?= $dtks->jumlah_kuda ?? '&nbsp;' ?></div>
                <?php elseif (strlen($dtks->jumlah_kuda) == 2) : ?>
                <div class="w-25 h-23 inline kotak tengah">0</div>
                <div class="w-25 h-23 inline kotak tengah"><?= $dtks->jumlah_kuda[0] ?? '&nbsp;' ?></div>
                <div class="w-25 h-23 inline kotak tengah"><?= $dtks->jumlah_kuda[1] ?? '&nbsp;' ?></div>
                <?php else : ?>
                <div class="w-25 h-23 inline kotak tengah"><?= $dtks->jumlah_kuda[0] ?? '&nbsp;' ?></div>
                <div class="w-25 h-23 inline kotak tengah"><?= $dtks->jumlah_kuda[1] ?? '&nbsp;' ?></div>
                <div class="w-25 h-23 inline kotak tengah"><?= $dtks->jumlah_kuda[2] ?? '&nbsp;' ?></div>
                <?php endif; ?>
            </td>

            <td class="no-border border-b">
                d.Babi
            </td>
            <td class="no-border border-b border-r">
                <?php if (strlen($dtks->jumlah_babi) == 1) : ?>
                <div class="w-25 h-23 inline kotak tengah">0</div>
                <div class="w-25 h-23 inline kotak tengah">0</div>
                <div class="w-25 h-23 inline kotak tengah"><?= $dtks->jumlah_babi ?? '&nbsp;' ?></div>
                <?php elseif (strlen($dtks->jumlah_babi) == 2) : ?>
                <div class="w-25 h-23 inline kotak tengah">0</div>
                <div class="w-25 h-23 inline kotak tengah"><?= $dtks->jumlah_babi[0] ?? '&nbsp;' ?></div>
                <div class="w-25 h-23 inline kotak tengah"><?= $dtks->jumlah_babi[1] ?? '&nbsp;' ?></div>
                <?php else : ?>
                <div class="w-25 h-23 inline kotak tengah"><?= $dtks->jumlah_babi[0] ?? '&nbsp;' ?></div>
                <div class="w-25 h-23 inline kotak tengah"><?= $dtks->jumlah_babi[1] ?? '&nbsp;' ?></div>
                <div class="w-25 h-23 inline kotak tengah"><?= $dtks->jumlah_babi[2] ?? '&nbsp;' ?></div>
                <?php endif; ?>
            </td>

            <td class="no-border border-b">
                e.Kambing/Domba
            </td>
            <td class="no-border border-b border-r">
                <?php if (strlen($dtks->jumlah_kambing_domba) == 1) : ?>
                <div class="w-25 h-23 inline kotak tengah">0</div>
                <div class="w-25 h-23 inline kotak tengah">0</div>
                <div class="w-25 h-23 inline kotak tengah"><?= $dtks->jumlah_kambing_domba ?? '&nbsp;' ?></div>
                <?php elseif (strlen($dtks->jumlah_kambing_domba) == 2) : ?>
                <div class="w-25 h-23 inline kotak tengah">0</div>
                <div class="w-25 h-23 inline kotak tengah"><?= $dtks->jumlah_kambing_domba[0] ?? '&nbsp;' ?></div>
                <div class="w-25 h-23 inline kotak tengah"><?= $dtks->jumlah_kambing_domba[1] ?? '&nbsp;' ?></div>
                <?php else : ?>
                <div class="w-25 h-23 inline kotak tengah"><?= $dtks->jumlah_kambing_domba[0] ?? '&nbsp;' ?></div>
                <div class="w-25 h-23 inline kotak tengah"><?= $dtks->jumlah_kambing_domba[1] ?? '&nbsp;' ?></div>
                <div class="w-25 h-23 inline kotak tengah"><?= $dtks->jumlah_kambing_domba[2] ?? '&nbsp;' ?></div>
                <?php endif; ?>
            </td>

        </tr>
        <tr>
            <td colspan="10">
                505. Jenis akses internet utama yang digunakan keluarga selama sebulan terakhir? (isikan kode)
                <br>
                <?=str_repeat('&nbsp;', 8)?>
                0. Tidak menggunakan internet
                <?=str_repeat('&nbsp;', 16)?>
                1. Internet dan TV digital berlangganan
                <?=str_repeat('&nbsp;', 16)?>
                2. Wifi
                <?=str_repeat('&nbsp;', 16)?>
                3. Internet Handphone
                <?=str_repeat('&nbsp;', 16)?>
                <div class="w-25 h-23 inline kotak tengah"><?=$dtks->kd_internet_sebulan?></div>
            </td>
        </tr>
        <tr>
            <td colspan="10">
                506. Apakah keluarga ini memiliki rekening aktif atau dompet digital? (isikan kode)
                <br>
                <?=str_repeat('&nbsp;', 8)?>
                1. Ya, untuk usaha
                <?=str_repeat('&nbsp;', 16)?>
                2. Ya, untuk pribadi
                <?=str_repeat('&nbsp;', 16)?>
                3. Ya, untuk usaha dan pribadi
                <?=str_repeat('&nbsp;', 16)?>
                4. Tidak
                <?=str_repeat('&nbsp;', 16)?>
                <div class="w-25 h-23 inline kotak tengah"><?=$dtks->kd_rek_aktif?></div>
            </td>
        </tr>
    </table>
</page>
<?php
    $total_anggota = $dtks->jumlah_anggota_dtks;
    $agt_tiap_baris = 5;
    $agt_offset = 1;
    $ulang_sebanyak = ceil($total_anggota / $agt_tiap_baris);
?>
<?php for($ulang = 1; $ulang <= $ulang_sebanyak; $ulang++): ?>
<?php
    $dtksAnggota = $dtks->dtksAnggota->forPage($agt_offset, $agt_tiap_baris);
?>
<page orientation="portrait" orientation="landscape" format="A4" backtop="5mm" backbottom="5mm"
style="font-family: Arial, Helvetica, sans-serif; font-size: 8pt">
    <page_header>
        <h4 class="tengah" style="margin-top:-10px">REGISTRASI SOSIAL EKONOMI 2022  </h4>
        <table style="width: 100%;border:none;margin-top:-30px">
            <tr>
                <td style="width: 50%;border:none"><b>RAHASIA</b></td>
                <td style="width: 50%;border:none" class="kanan">REGSOS-EK2022.K</td>
            </tr>
        </table>
    </page_header>
    <page_footer>
        <div style="text-align: right;    width: 100%">
            Dokumen. <?= $dtks->id ?>, halaman [[page_cu]]/[[page_nb]]
        </div>
    </page_footer>
    <table style="width: 100%">
        <tr>
            <th class="tengah" style="width: 100%"><b>IV. KETERANGAN SOSIAL EKONOMI ANGGOTA KELUARGA</b></th>
        </tr>
    </table>
    <table style="width: 100%">
        <tr>
            <th class="tengah" style="width: 100%"><b>A. KETERANGAN DEMOGRAFI</b></th>
        </tr>
    </table>
    <table style="width: 100%">
        <tr style="font-size: 8px">
            <?php for ($i = 1; $i <= $agt_tiap_baris + 1; $i++) : ?>
                <th class="tengah">( <?=$i?> )</th>
            <?php endfor; ?>
        </tr>
        <tr>
            <td style="width: 25%;">401. Nomor urut anggota keluarga</td>
            <?php foreach($dtksAnggota as $key => $agt) : ?>
                <td style="width: 15%;"><?=str_repeat('&nbsp;', 16)?>
                    <?php if (strlen($key + 1) == 1) : ?>
                        <div class="w-25 h-23 inline kotak tengah">0</div>
                        <div class="w-25 h-23 inline kotak tengah"><?= $key + 1 ?? '&nbsp;' ?></div>
                    <?php else : ?>
                        <div class="w-25 h-23 inline kotak tengah"><?= ($key + 1)[0] ?? '&nbsp;' ?></div>
                        <div class="w-25 h-23 inline kotak tengah"><?= ($key + 1)[1] ?? '&nbsp;' ?></div>
                    <?php endif; ?>
                </td>
            <?php endforeach;?>
            <?php for ($i = $dtksAnggota->count(); $i <=  $agt_tiap_baris - 1; $i++) : ?>
                <td style="width: 15%;"><?=str_repeat('&nbsp;', 16)?>
                    <div class="w-25 h-23 inline kotak tengah"></div>
                    <div class="w-25 h-23 inline kotak tengah"></div>
                </td>
            <?php endfor; ?>
        </tr>
        <tr>
            <td style="width: 25%;">
                402. Nama anggota keluarga
                <i style="font-size: 9px">
                <br><?=str_repeat('&nbsp;', 4)?>
                (Tulis semua yang tercantum dalam Kartu Keluarga
                <br><?=str_repeat('&nbsp;', 4)?>
                (KK) dan siapa saja yang biasanya tinggal bersama
                <br><?=str_repeat('&nbsp;', 4)?>
                keluarga ini BAIK DEWASA, ANAK-ANAK, MAUPUN
                <br><?=str_repeat('&nbsp;', 4)?>
                BAYI. Tuliskan nama sesuai dengan identitas)</i>
            </td>
            <?php foreach($dtksAnggota as $key => $agt) : ?>
                <td class="middle berdiri" style="width: 15%;">
                    <?= $agt->nama ? strtoupper(substr($agt->nama, 0, 40)) : '...........................................' ?>
                </td>
            <?php endforeach;?>
            <?php for ($i = $dtksAnggota->count(); $i <=  $agt_tiap_baris - 1; $i++) : ?>
                <td style="width: 15%;"></td>
            <?php endfor; ?>
        </tr>
        <tr>
            <td style="width: 25%;">
                403. Nomor Induk Kependudukn (NIK)
            </td>
            <?php foreach($dtksAnggota as $key => $agt) : ?>
                <td style="width: 15%;">
                    <?php
                        $item = $agt->nik;
                        $len = $item == '' ? 16 : strlen($item);
                    ?>
                    <?php for ($i = $len - 16; $i < $len; $i++) :
                    if ($i % 6 == 0 && $i != 0) {
                        echo '&nbsp;&nbsp;' . $item[$i] ?? '' ;
                    }else{
                        echo  $item[$i]?? '';
                    }
                     endfor; ?>
                </td>
            <?php endforeach;?>
            <?php for ($i = $dtksAnggota->count(); $i <=  $agt_tiap_baris - 1; $i++) : ?>
                <td style="width: 15%;"></td>
            <?php endfor; ?>
        </tr>
        <tr>
            <td rowspan="2" style="width: 25%;">
                404. Keterangan keberadaan anggota keluarga
                <br><?=str_repeat('&nbsp;', 6)?>(isikan kode)
                <br><br>
                <?=str_repeat('&nbsp;', 6)?>(Jika berkode 2, 3, 4 atau 6. lanjut ke
                <br><?=str_repeat('&nbsp;', 6)?>anggota keluarga berikutnya)
            </td>
            <td style="width: 75%;" colspan="<?=$agt_tiap_baris + 1?>">
                1. Tinggal bersama keluarga<?=str_repeat('&nbsp;', 6)?>
                2. Meninggal <?=str_repeat('&nbsp;', 6)?>
                3. Tidak tinggal bersama keluarga/pindah ke wilayah (daerah) lain di Indonesia
                <br>4. Tidak tinggal bersama keluarga/pindah ke luar negeri<?=str_repeat('&nbsp;', 6)?>
                5. Anggota Keluarga baru<?=str_repeat('&nbsp;', 6)?>
                6. Tidak ditemukan
            </td>
        </tr>
        <tr>
            <?php foreach($dtksAnggota as $key => $agt) : ?>
                <td style="width: 15%;">
                    <?=str_repeat('&nbsp;', 20)?>
                    <div class="w-25 h-23 inline kotak tengah"><?=$agt->kd_ket_keberadaan_art?></div>
                </td>
            <?php endforeach;?>
            <?php for ($i = $dtksAnggota->count(); $i <=  $agt_tiap_baris - 1; $i++) : ?>
                <td style="width: 15%;">
                    <?=str_repeat('&nbsp;', 20)?>
                    <div class="w-25 h-23 inline kotak tengah">&nbsp;</div>
                </td>
            <?php endfor; ?>
        </tr>
        <tr>
            <td style="width: 25%;">
                405. Jenis Kelamin (isikan kode)
                <br>
                <?=str_repeat('&nbsp;', 6)?>1. Laki-laki
                <?=str_repeat('&nbsp;', 6)?>2. Perempuan
            </td>
            <?php foreach($dtksAnggota as $key => $agt) : ?>
                <td style="width: 15%;">
                    <?=str_repeat('&nbsp;', 20)?>
                    <div class="w-25 h-23 inline kotak tengah"><?=$agt->kd_jenis_kelamin?></div>
                </td>
            <?php endforeach;?>
            <?php for ($i = $dtksAnggota->count(); $i <=  $agt_tiap_baris - 1; $i++) : ?>
                <td style="width: 15%;">
                    <?=str_repeat('&nbsp;', 20)?>
                    <div class="w-25 h-23 inline kotak tengah">&nbsp;</div>
                </td>
            <?php endfor; ?>
        </tr>
        <tr>
            <td style="width: 25%;">
                406. Tanggal Lahir

                (Tanggal/Bulan/Tahun)
            </td>
            <?php foreach($dtksAnggota as $key => $agt) : ?>
                <td class="tengah" style="width: 15%;">
                    <?php $item = $agt->tgl_lahir ? $agt->tgl_lahir->format('d') : null; ?>
                    <?php for ($i = strlen($item) - 2; $i < strlen($item); $i++) : ?>
                    <?= $item[$i] ?? '&nbsp;' ?>
                    <?php endfor; ?>

                    &nbsp;/&nbsp;

                    <?php $item = $agt->tgl_lahir ? $agt->tgl_lahir->format('m') : null; ?>
                    <?php for ($i = strlen($item) - 2; $i < strlen($item); $i++) : ?>
                    <?= $item[$i] ?? '&nbsp;' ?>
                    <?php endfor; ?>

                    &nbsp;/&nbsp;

                    <?php $item = $agt->tgl_lahir ? $agt->tgl_lahir->format('Y') : null; ?>
                    <?php for ($i = strlen($item) - 4; $i < strlen($item); $i++) : ?>
                    <?= $item[$i] ?? '&nbsp;' ?>
                    <?php endfor; ?>
                </td>
            <?php endforeach;?>
            <?php for ($i = $dtksAnggota->count(); $i <=  $agt_tiap_baris - 1; $i++) : ?>
                <td class="tengah" style="width: 15%;">
                &nbsp;/
                <?=str_repeat('&nbsp;', 8)?>
                /&nbsp;
                </td>
            <?php endfor; ?>
        </tr>
        <tr>
            <td style="width: 25%;">
                407. Umur (Tahun)
            </td>
            <?php foreach($dtksAnggota as $key => $agt) : ?>
                <td class="tengah" style="width: 15%;">
                    <?=$agt->umur?>
                </td>
            <?php endforeach;?>
            <?php for ($i = $dtksAnggota->count(); $i <=  $agt_tiap_baris - 1; $i++) : ?>
                <td style="width: 15%;">
                </td>
            <?php endfor; ?>
        </tr>
        <tr>
            <td style="width: 25%;">
                408. Status Perkawinan (isikan kode)
                <br>
                <?=str_repeat('&nbsp;', 6)?>1. Belum kawin
                <?=str_repeat('&nbsp;', 6)?>2. Kawin/nikah
                <br><?=str_repeat('&nbsp;', 6)?>3. Cerai hidup
                <?=str_repeat('&nbsp;', 6)?>4. Cerai mati
            </td>
            <?php foreach($dtksAnggota as $key => $agt) : ?>
                <td style="width: 15%;">
                    <?=str_repeat('&nbsp;', 20)?>
                    <div class="w-25 h-23 inline kotak tengah"><?=$agt->kd_stat_perkawinan?></div>
                </td>
            <?php endforeach;?>
            <?php for ($i = $dtksAnggota->count(); $i <=  $agt_tiap_baris - 1; $i++) : ?>
                <td style="width: 15%;">
                    <?=str_repeat('&nbsp;', 20)?>
                    <div class="w-25 h-23 inline kotak tengah">&nbsp;</div>
                </td>
            <?php endfor; ?>
        </tr>
        <tr>
            <td rowspan="2" style="width: 25%;">
                409. Status Hubungan dengan Kepala Keluarga
                <br>
                <?=str_repeat('&nbsp;', 6)?>
                (isikan kode)
            </td>
            <td colspan="<?=$agt_tiap_baris + 1?>">
                1. Kepala keluarga
                <?=str_repeat('&nbsp;', 6)?>2. Istri/suami
                <?=str_repeat('&nbsp;', 6)?>3. Anak
                <?=str_repeat('&nbsp;', 6)?>4. Menantu
                <?=str_repeat('&nbsp;', 6)?>5. Cucu
                <?=str_repeat('&nbsp;', 6)?>6. Orang tua/mertua
                <?=str_repeat('&nbsp;', 6)?>7. Pembantu/sopir
                <?=str_repeat('&nbsp;', 6)?>8. Lainnya
            </td>
        </tr>
        <tr>
            <?php foreach($dtksAnggota as $key => $agt) : ?>
                <td style="width: 15%;">
                    <?=str_repeat('&nbsp;', 20)?>
                    <div class="w-25 h-23 inline kotak tengah"><?=$agt->kd_hubungan_dg_kk?></div>
                </td>
            <?php endforeach;?>
            <?php for ($i = $dtksAnggota->count(); $i <=  $agt_tiap_baris - 1; $i++) : ?>
                <td style="width: 15%;">
                    <?=str_repeat('&nbsp;', 20)?>
                    <div class="w-25 h-23 inline kotak tengah">&nbsp;</div>
                </td>
            <?php endfor; ?>
        </tr>
        <tr>
            <td style="width: 25%;">
                410. Jika (nama) merupakan wanita berusia 10-54
                <br><?=str_repeat('&nbsp;', 6)?>tahun dan 408 berkode 2, 3, atau 4.
                <br><?=str_repeat('&nbsp;', 6)?>Apakah saat ini (nama) sedang hamil
                <br><?=str_repeat('&nbsp;', 6)?>(isikan kode)
                <?=str_repeat('&nbsp;', 6)?>1. Ya
                <?=str_repeat('&nbsp;', 6)?>2. Tidak
            </td>
            <?php foreach($dtksAnggota as $key => $agt) : ?>
                <td style="width: 15%;">
                    <?=str_repeat('&nbsp;', 20)?>
                    <div class="w-25 h-23 inline kotak tengah"><?=$agt->kd_status_kehamilan?></div>
                </td>
            <?php endforeach;?>
            <?php for ($i = $dtksAnggota->count(); $i <=  $agt_tiap_baris - 1; $i++) : ?>
                <td style="width: 15%;">
                    <?=str_repeat('&nbsp;', 20)?>
                    <div class="w-25 h-23 inline kotak tengah">&nbsp;</div>
                </td>
            <?php endfor; ?>
        </tr>
        <tr>
            <td style="width: 25%;">
                411. Apakah (nama) memiliki kartu Identitas
                <br><?=str_repeat('&nbsp;', 6)?>(jumlahkan kode)
                <?=str_repeat('&nbsp;', 6)?>0. Tidak memiliki
                <br><?=str_repeat('&nbsp;', 6)?>1. Akta kelahiran
                <?=str_repeat('&nbsp;', 6)?>2. KIA
                <?=str_repeat('&nbsp;', 6)?>4. KTP
            </td>
            <?php foreach($dtksAnggota as $key => $agt) : ?>
                <td style="width: 15%;">
                    <?=str_repeat('&nbsp;', 20)?>
                    <div class="w-25 h-23 inline kotak tengah"><?=$agt->kd_punya_kartuid?></div>
                    &nbsp;<?=implode(',', tentukanJumlahTerpilih([4,2,1,0],$agt->kd_punya_kartuid))?>
                </td>
            <?php endforeach;?>
            <?php for ($i = $dtksAnggota->count(); $i <=  $agt_tiap_baris - 1; $i++) : ?>
                <td style="width: 15%;">
                    <?=str_repeat('&nbsp;', 20)?>
                    <div class="w-25 h-23 inline kotak tengah">&nbsp;</div>
                </td>
            <?php endfor; ?>
        </tr>
    </table>
    <table style="width: 100%">
        <tr>
            <th class="tengah" style="width: 100%"><b>B. PENDIDIKAN (ANGGOTA KELUARGA USIA 5 TAHUN KE ATAS)</b></th>
        </tr>
    </table>
    <table style="width: 100%">
        <tr>
            <td style="width: 25%;">
                412. Partisipasi sekolah (isikan kode)
                <br><?=str_repeat('&nbsp;', 6)?>
                1. Tidak/belum pernah sekolah
                <br><?=str_repeat('&nbsp;', 6)?>
                2. Masih sekolah
                <br><?=str_repeat('&nbsp;', 6)?>
                3. Tidak bersekolah lagi
            </td>
            <?php foreach($dtksAnggota as $key => $agt) : ?>
                <td style="width: 15%;"><?=str_repeat('&nbsp;', 20)?>
                    <div class="w-25 h-23 inline kotak tengah"><?= $agt->kd_partisipasi_sekolah ?? '&nbsp;' ?></div>
                <br><br><?=str_repeat('&nbsp;', 11)?>
                    <b>Kode = 1 ---> 416</b>
                </td>
            <?php endforeach;?>
            <?php for ($i = $dtksAnggota->count(); $i <=  $agt_tiap_baris - 1; $i++) : ?>
                <td style="width: 15%;">
                    <?=str_repeat('&nbsp;', 20)?>
                    <div class="w-25 h-23 inline kotak tengah">&nbsp;</div>
                    <br><br><?=str_repeat('&nbsp;', 11)?>
                    <b>Kode = 1 ---> 416</b>
                </td>
            <?php endfor; ?>
        </tr>
    </table>
</page>

<page orientation="portrait" orientation="landscape" format="A4" backtop="7mm" backbottom="5mm"
    style="font-family: Arial, Helvetica, sans-serif; font-size: 8pt">
    <page_header>
        <table style="width: 100%;border:none;margin-top:-20px">
            <tr>
                <td style="width: 42%;border:none;vertical-align: bottom;"><b>RAHASIA</b></td>
                <td style="width: 63%;border:none">
                    <table>
                        <tr>
                            <td style="padding: 0;" class="tengah">101</td>
                            <td style="padding: 0;" class="tengah">102</td>
                            <td style="padding: 0;" class="tengah">103</td>
                            <td style="padding: 0;" class="tengah">104</td>
                            <td style="padding: 0;" class="tengah">105</td>
                            <td style="padding: 0;" class="tengah">109</td>
                            <td style="padding: 0;" class="tengah">110</td>
                        </tr>
                        <tr>
                            <td style="padding: 0; padding-right: 5px">
                                <?php for ($i = strlen($dtks->kode_provinsi) - 2; $i < strlen($dtks->kode_provinsi); $i++) : ?>
                                <div class="w-25 h-23 inline kotak tengah">
                                    <?= $dtks->kode_provinsi[$i] ?? '&nbsp;'; ?>
                                </div>
                                <?php endfor; ?>
                            </td>
                            <td style="padding: 0; padding-right: 5px">
                                <?php for ($i = strlen($dtks->kode_kabupaten) - 2; $i < strlen($dtks->kode_kabupaten); $i++) : ?>
                                <div class="w-25 h-23 inline kotak tengah">
                                    <?php echo $dtks->kode_kabupaten[$i] ?? '&nbsp;'; ?>&nbsp;
                                </div>
                                <?php endfor; ?>
                            </td>
                            <td style="padding: 0; padding-right: 5px">
                                <?php for ($i = strlen($dtks->kode_kecamatan) - 3; $i < strlen($dtks->kode_kecamatan); $i++) : ?>
                                <div class="w-25 h-23 inline kotak tengah">
                                    <?php echo $dtks->kode_kecamatan[$i] ?? '&nbsp;'; ?>&nbsp;
                                </div>
                                <?php endfor; ?>
                            </td>
                            <td style="padding: 0; padding-right: 5px">
                                <?php for ($i = strlen($dtks->kode_desa) - 3; $i < strlen($dtks->kode_desa); $i++) : ?>
                                <div class="w-25 h-23 inline kotak tengah">
                                    <?php echo $dtks->kode_desa[$i] ?? '&nbsp;'; ?>&nbsp;
                                </div>
                                <?php endfor; ?>
                            </td>
                            <td style="padding: 0; padding-right: 5px">
                                <?php for ($i = strlen($dtks->kode_sls_non_sls) - 4; $i < strlen($dtks->kode_sls_non_sls); $i++) : ?>
                                <div class="w-25 h-23 inline kotak tengah">
                                    <?php echo $dtks->kode_sls_non_sls[$i] ?? '&nbsp;'; ?>&nbsp;
                                </div>
                                <?php endfor; ?>
                                <?php for ($i = strlen($dtks->kode_sub_sls) - 2; $i < strlen($dtks->kode_sub_sls); $i++) : ?>
                                <div class="w-25 h-23 inline kotak tengah">
                                    <?php echo $dtks->kode_sub_sls[$i] ?? '&nbsp;'; ?>&nbsp;
                                </div>
                                <?php endfor; ?>
                            </td>
                            <td style="padding: 0; padding-right: 5px">
                                <?php for ($i = strlen($dtks->no_urut_bangunan_tinggal) - 3; $i < strlen($dtks->no_urut_bangunan_tinggal); $i++) : ?>
                                <div class="w-25 h-23 inline kotak tengah">
                                    <?php echo $dtks->no_urut_bangunan_tinggal[$i] ?? '&nbsp;'; ?>&nbsp;
                                </div>
                                <?php endfor; ?>
                            </td>
                            <td style="padding: 0; padding-right: 5px">
                                <?php for ($i = strlen($dtks->no_urut_keluarga_verif) - 3; $i < strlen($dtks->no_urut_keluarga_verif); $i++) : ?>
                                <div class="w-25 h-23 inline kotak tengah">
                                    <?php echo $dtks->no_urut_keluarga_verif[$i] ?? '&nbsp;'; ?>&nbsp;
                                </div>
                                <?php endfor; ?>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </page_header>
    <page_footer>
        <div style="text-align: right;    width: 100%">
            Dokumen. <?= $dtks->id ?>, halaman [[page_cu]]/[[page_nb]]
        </div>
    </page_footer>

    <table style="width: 100%">
        <tr style="font-size: 8px">
            <?php for ($i = 1; $i <= $agt_tiap_baris + 1; $i++) : ?>
                <th class="tengah">( <?=$i?> )</th>
            <?php endfor; ?>
        </tr>
        <tr>
            <td style="width: 25%;">401. Nomor urut anggota keluarga</td>
            <?php foreach($dtksAnggota as $key => $agt) : ?>
                <td style="width: 15%;"><?=str_repeat('&nbsp;', 16)?>
                    <?php if (strlen($key + 1) == 1) : ?>
                        <div class="w-25 h-23 inline kotak tengah">0</div>
                        <div class="w-25 h-23 inline kotak tengah"><?= $key + 1 ?? '&nbsp;' ?></div>
                    <?php else : ?>
                        <div class="w-25 h-23 inline kotak tengah"><?= ($key + 1)[0] ?? '&nbsp;' ?></div>
                        <div class="w-25 h-23 inline kotak tengah"><?= ($key + 1)[1] ?? '&nbsp;' ?></div>
                    <?php endif; ?>
                </td>
            <?php endforeach;?>
            <?php for ($i = $dtksAnggota->count(); $i <=  $agt_tiap_baris - 1; $i++) : ?>
                <td style="width: 15%;"><?=str_repeat('&nbsp;', 16)?>
                    <div class="w-25 h-23 inline kotak tengah"></div>
                    <div class="w-25 h-23 inline kotak tengah"></div>
                </td>
            <?php endfor; ?>
        </tr>
        <tr>
            <?php for ($i = 1; $i <= $agt_tiap_baris + 1; $i++) : ?>
            <td></td>
            <?php endfor; ?>
        </tr>
        <tr>
            <td style="width: 25%;">
                413. Jenjang dan jenis pendidikan tertinggi
                <br><?=str_repeat('&nbsp;', 6)?>yang pernah/sedang diduduki (isikan kode)
            </td>
            <?php foreach($dtksAnggota as $key => $agt) : ?>
                <td>
                    <?=str_repeat('&nbsp;', 20)?>
                    <div class="w-25 h-23 inline kotak tengah"><?=$agt->kd_pendidikan_tertinggi?></div>
                </td>
            <?php endforeach;?>
            <?php for ($i = $dtksAnggota->count(); $i <=  $agt_tiap_baris - 1; $i++) : ?>
                <td style="width: 15%;">
                    <?=str_repeat('&nbsp;', 20)?>
                    <div class="w-25 h-23 inline kotak tengah"></div>
                </td>
            <?php endfor; ?>
        </tr>
        <tr>
            <td style="width: 25%;">
                414. Kelas tertinggi yang pernah/sedang diduduki <?=str_repeat('&nbsp;', 3)?>(isikan kode)  1, 2, 3, 4, 5, 6, 7, 8 (Tamat & Lulus)
            </td>
            <?php foreach($dtksAnggota as $key => $agt) : ?>
                <td style="width: 15%;">
                    <?=str_repeat('&nbsp;', 20)?>
                    <div class="w-25 h-23 inline kotak tengah"><?=$agt->kd_kelas_tertinggi?></div>
                </td>
            <?php endforeach;?>
            <?php for ($i = $dtksAnggota->count(); $i <=  $agt_tiap_baris - 1; $i++) : ?>
                <td style="width: 15%;">
                    <?=str_repeat('&nbsp;', 20)?>
                    <div class="w-25 h-23 inline kotak tengah"></div>
                </td>
            <?php endfor; ?>
        </tr>
        <tr>
            <td style="width: 25%;">
                415. Ijazah tertinggi yang dimiliki (isikan kode)
            </td>
            <?php foreach($dtksAnggota as $key => $agt) : ?>
                <td>
                    <?=str_repeat('&nbsp;', 20)?>
                    <div class="w-25 h-23 inline kotak tengah"><?=$agt->kd_ijazah_tertinggi?></div>
                </td>
            <?php endforeach;?>
            <?php for ($i = $dtksAnggota->count(); $i <=  $agt_tiap_baris - 1; $i++) : ?>
                <td style="width: 15%;">
                    <?=str_repeat('&nbsp;', 20)?>
                    <div class="w-25 h-23 inline kotak tengah"></div>
                </td>
            <?php endfor; ?>
        </tr>
        <tr>
            <td colspan="<?=$agt_tiap_baris + 2?>">
                <table style="width: 100%;">
                    <tr>
                        <td colspan="12" style="width: 98%;padding-bottom:0;padding-top:0;">
                            <b>Kode 413: Jenjang dan jenis pendidikan dan Kode 415: ljazah/STTB</b>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding-bottom:0">01. Paket A</td>
                        <td style="padding-bottom:0">03. SD</td>
                        <td style="padding-bottom:0">05. SPM/PDF Ula</td>
                        <td style="padding-bottom:0">07. SMP LB</td>
                        <td style="padding-bottom:0">09. MTs</td>
                        <td style="padding-bottom:0">11. Paket C</td>
                        <td style="padding-bottom:0">13. SMA</td>
                        <td style="padding-bottom:0">15. SMK</td>
                        <td style="padding-bottom:0">17. SPM/PDF Ulya</td>
                        <td style="padding-bottom:0">19. D4/S1</td>
                        <td style="padding-bottom:0">21. S2</td>
                        <td style="padding-bottom:0"><b>23. Tidak punya ijazah (khusus 415)</b></td>
                    </tr>
                    <tr>
                        <td style="padding-bottom:0">02. SDLB</td>
                        <td style="padding-bottom:0">04. MI</td>
                        <td style="padding-bottom:0">06. Paket B</td>
                        <td style="padding-bottom:0">08. SMP</td>
                        <td style="padding-bottom:0">10. SPM/PDF Wustha</td>
                        <td style="padding-bottom:0">12. SMLB</td>
                        <td style="padding-bottom:0">14. MA</td>
                        <td style="padding-bottom:0">16. MAK</td>
                        <td style="padding-bottom:0">18. D1/D2/D3</td>
                        <td style="padding-bottom:0">20. Profesi</td>
                        <td style="padding-bottom:0">22. S3</td>

                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <table style="width: 100%">
        <tr>
            <th class="tengah" style="width: 100%"><b>C. KETENAGAKERJAAN (ANGGOTA KELUARGA USIA 5 TAHUN KE ATAS)</b></th>
        </tr>
    </table>

    <table style="width: 100%">
        <tr>
            <td style="width: 25%;">
                416.a. Apakah (nama) bekerja/membantu bekerja
                <br><?=str_repeat('&nbsp;', 8)?>
                selama seminggu yang lalu?  (isikan kode)
                <br><?=str_repeat('&nbsp;', 8)?>
                1. Ya<?=str_repeat('&nbsp;', 8)?>2. Tidak
            </td>
            <?php foreach($dtksAnggota as $key => $agt) : ?>
                <td style="width: 15%;">
                    <?=str_repeat('&nbsp;', 20)?>
                    <div class="w-25 h-23 inline kotak tengah"><?=$agt->kd_bekerja_seminggu_lalu?></div>
                </td>
            <?php endforeach;?>
            <?php for ($i = $dtksAnggota->count(); $i <=  $agt_tiap_baris - 1; $i++) : ?>
                <td style="width: 15%;">
                    <?=str_repeat('&nbsp;', 20)?>
                    <div class="w-25 h-23 inline kotak tengah"></div>
                </td>
            <?php endfor; ?>
        </tr>
        <tr>
            <td style="width: 25%;">
                <?=str_repeat('&nbsp;', 8)?>b. Berapa jam (nama) bekerja?
            </td>
            <?php foreach($dtksAnggota as $key => $agt) : ?>
                <td style="width: 15%;">
                    <p style="display:inline" class="tengah">............. , ........ Jam</p>
                    <?=str_repeat('&nbsp;', 16)?>
                    <?php if (strlen($agt->kd_bekerja_seminggu_lalu) == 1) : ?>
                        <div class="w-25 h-23 inline kotak tengah">0</div>
                        <div class="w-25 h-23 inline kotak tengah"><?= $agt->kd_bekerja_seminggu_lalu ?? '&nbsp;' ?></div>
                    <?php else : ?>
                        <div class="w-25 h-23 inline kotak tengah"><?= $agt->kd_bekerja_seminggu_lalu[0] ?? '&nbsp;' ?></div>
                        <div class="w-25 h-23 inline kotak tengah"><?= $agt->kd_bekerja_seminggu_lalu[1] ?? '&nbsp;' ?></div>
                    <?php endif; ?>
                </td>
            <?php endforeach;?>
            <?php for ($i = $dtksAnggota->count(); $i <=  $agt_tiap_baris - 1; $i++) : ?>
                <td style="width: 15%;">
                    <?=str_repeat('&nbsp;', 20)?>
                    <div class="w-25 h-23 inline kotak tengah"></div>
                </td>
            <?php endfor; ?>
        </tr>
        <tr>
            <td style="width: 25%;;height:55px;">
                417. Lapangan usaha dari pekerjaan utama
                <br><?=str_repeat('&nbsp;', 8)?>(Tulis Selengkap-lengkapnya)
                <br><br><?=str_repeat('&nbsp;', 8)?>(Kode diisi oleh PML)
            </td>
            <?php foreach($dtksAnggota as $key => $agt) : ?>
                <td style="width: 15%">
                    <?php if($agt->tulis_lapangan_usaha_pekerjaan):?>
                        <?php
                             $i = 0;
                             $panjang = 20;
                             $tls_lp_u_p = substr($agt->tulis_lapangan_usaha_pekerjaan, 0, 100);
                             do {
                                 echo substr($tls_lp_u_p, $i, $panjang) . '&nbsp;';
                                 $i += $panjang;
                             } while (strlen($tls_lp_u_p) >= $i);
                        ?>

                        <?php if (strlen($agt->kd_lapangan_usaha_pekerjaan) == 1) : ?>
                                <div class="w-25 h-23 inline kotak tengah" style="font-size:8pt;">0</div>
                                <div class="w-25 h-23 inline kotak tengah" style="font-size:8pt;"><?= $agt->kd_lapangan_usaha_pekerjaan ?? '&nbsp;' ?></div>
                            <?php else : ?>
                                <div class="w-25 h-23 inline kotak tengah" style="font-size:8pt;"><?= $agt->kd_lapangan_usaha_pekerjaan[0] ?? '&nbsp;' ?></div>
                                <div class="w-25 h-23 inline kotak tengah" style="font-size:8pt;"><?= $agt->kd_lapangan_usaha_pekerjaan[1] ?? '&nbsp;' ?></div>
                            <?php endif; ?>
                    <?php else:?>
                        <p style="font-size:20px; padding:0;margin:0;display:inline">
                                ............................
                                ............................
                                ...............
                            <?php if (strlen($agt->kd_lapangan_usaha_pekerjaan) == 1) : ?>
                                <div class="w-25 h-23 inline kotak tengah" style="font-size:8pt;">0</div>
                                <div class="w-25 h-23 inline kotak tengah" style="font-size:8pt;"><?= $agt->kd_lapangan_usaha_pekerjaan ?? '&nbsp;' ?></div>
                            <?php else : ?>
                                <div class="w-25 h-23 inline kotak tengah" style="font-size:8pt;"><?= $agt->kd_lapangan_usaha_pekerjaan[0] ?? '&nbsp;' ?></div>
                                <div class="w-25 h-23 inline kotak tengah" style="font-size:8pt;"><?= $agt->kd_lapangan_usaha_pekerjaan[1] ?? '&nbsp;' ?></div>
                            <?php endif; ?>
                        </p>
                    <?php endif;?>
                </td>
            <?php endforeach;?>
            <?php for ($i = $dtksAnggota->count(); $i <=  $agt_tiap_baris - 1; $i++) : ?>
                <td style="width: 15%;">
                    <p style="font-size:20px; padding:0;margin:0;display:inline">
                        ............................
                        ............................
                        ...............
                        <div class="w-25 h-23 inline kotak tengah" style="font-size:8pt;">&nbsp;</div>
                        <div class="w-25 h-23 inline kotak tengah" style="font-size:8pt;">&nbsp;</div>
                    </p>
                </td>
            <?php endfor; ?>
        </tr>
        <tr>
            <td style="width: 25%;" rowspan="2">
                418. Status kedudukan dalam pekerjaan utama
                <br><?=str_repeat('&nbsp;', 8)?>(isikan kode)
            </td>
            <td style="width: 75%;" colspan="<?=$agt_tiap_baris ?>">
                <b>1.</b> Berusaha sendiri<?=str_repeat('&nbsp;', 6)?>
                <b>2.</b> Berusaha dibantu buruh tidak tetap/tidak dibayar<?=str_repeat('&nbsp;', 6)?>
                <b>3.</b> Berusaha dibantu buruh tetap/dibayar<?=str_repeat('&nbsp;', 6)?>
                <b>4.</b> Buruh/karyawan/pegawai swasta<?=str_repeat('&nbsp;', 6)?>
                <br><b>5.</b> PNS/TNI/ Polri/BUMN/BUMD/pejabat negara<?=str_repeat('&nbsp;', 6)?>
                <b>6.</b> Pekerja bebas pertanian<?=str_repeat('&nbsp;', 6)?>
                <b>7.</b> Pekerja bebas non-pertanian<?=str_repeat('&nbsp;', 6)?>
                <b>8.</b> Pekerja keluarga/tidak dibayar
            </td>
        </tr>
        <tr>
            <?php foreach($dtksAnggota as $key => $agt) : ?>
                <td style="width: 15%;">
                    <?=str_repeat('&nbsp;', 20)?>
                    <div class="w-25 h-23 inline kotak tengah"><?=$agt->kd_kedudukan_di_pekerjaan?></div>
                </td>
            <?php endforeach;?>
            <?php for ($i = $dtksAnggota->count(); $i <=  $agt_tiap_baris - 1; $i++) : ?>
                <td style="width: 15%;">
                    <?=str_repeat('&nbsp;', 20)?>
                    <div class="w-25 h-23 inline kotak tengah"></div>
                </td>
            <?php endfor; ?>
        </tr>
        <tr>
            <td style="width: 25%;">
                419. Apakah (nama) memiliki NPWP? (isikan kode)
                <br><?=str_repeat('&nbsp;', 8)?>1. Ada, Dapat menunjukkan
                <br><?=str_repeat('&nbsp;', 8)?>2. Ada, Tidak dapat  menunjukkan
                <br><?=str_repeat('&nbsp;', 8)?>3. Tidak ada
            </td>
            <?php foreach($dtksAnggota as $key => $agt) : ?>
                <td style="width: 15%;">
                    <?=str_repeat('&nbsp;', 20)?>
                    <div class="w-25 h-23 inline kotak tengah"><?=$agt->kd_kedudukan_di_pekerjaan?></div>
                </td>
            <?php endforeach;?>
            <?php for ($i = $dtksAnggota->count(); $i <=  $agt_tiap_baris - 1; $i++) : ?>
                <td style="width: 15%;">
                    <?=str_repeat('&nbsp;', 20)?>
                    <div class="w-25 h-23 inline kotak tengah"></div>
                </td>
            <?php endfor; ?>
        </tr>
        <tr>
            <td style="line-height: 15px;" colspan="<?=$agt_tiap_baris + 1?>">
                <b>Kode 417 dan Kode 421: Lapangan usaha (Diisi oleh PML) </b>
                    <br><b>(01)</b> Pertanian tanaman padi & palawija
                    <?=str_repeat('&nbsp;', 4)?><b>( 02 )</b> Hortikultura
                    <?=str_repeat('&nbsp;', 4)?><b>( 03 )</b> Perkebunan
                    <?=str_repeat('&nbsp;', 4)?><b>( 04 )</b> Perikanan
                    <?=str_repeat('&nbsp;', 4)?><b>( 05 )</b> Peternakan
                    <?=str_repeat('&nbsp;', 4)?><b>( 06 )</b> Kehutanan & pertanian lainnya
                    <?=str_repeat('&nbsp;', 4)?><b>( 07 )</b> Pertambangan/penggalian
                    <?=str_repeat('&nbsp;', 4)?><b>( 08 )</b> Industri pengolahan
                    <br><b>(09)</b> Pengadaan listrik, gas, uap/air panas, & udara dingin
                    <?=str_repeat('&nbsp;', 8)?><b>( 10 )</b> Pengelolaan air, pengelolaan air limbah, pengelolaan dan daur ulang sampah, dan aktivitas remediasi
                    <?=str_repeat('&nbsp;', 8)?><b>( 11 )</b> Konstruksi
                    <br><b>(12)</b> Perdagangan besar dan eceran, reparasi dan perawatan mobil dan sepeda motor
                    <?=str_repeat('&nbsp;', 8)?><b>( 13 )</b> Pengangkutan dan pergudangan
                    <?=str_repeat('&nbsp;', 8)?><b>( 14 )</b> Penyediaan akomodasi & makan minum
                    <?=str_repeat('&nbsp;', 8)?><b>( 15 )</b> Informasi & komunikasi
                    <br><b>(16)</b> Keuangan & asuransi
                    <?=str_repeat('&nbsp;', 4)?><b>( 17 )</b> Real estate
                    <?=str_repeat('&nbsp;', 4)?><b>( 18 )</b> Aktivitas profesional, ilmiah, dan teknis
                    <?=str_repeat('&nbsp;', 8)?><b>( 19 )</b> Aktivitas penyewaan dan sewa guna tanpa hak opsi, ketenagakerjaan, agen perjalanan, dan penunjang usaha lainnya
                    <br><b>(20)</b> Administrasi pemerintahan, pertahanan, dan jaminan sosial wajib
                    <?=str_repeat('&nbsp;', 6)?><b>( 21 )</b> Pendidikan
                    <?=str_repeat('&nbsp;', 6)?><b>( 22 )</b> Aktivitas kesehatan manusia dan aktivitas sosial
                    <?=str_repeat('&nbsp;', 6)?><b>( 23 )</b> Kesenian, hiburan, dan rekreasi
                    <?=str_repeat('&nbsp;', 6)?><b>( 24 )</b> Aktivitas jasa lainnya
                    <br><b>(25)</b> Aktivitas keluarga sebagai pemberi kerja
                    <?=str_repeat('&nbsp;', 8)?><b>( 26 )</b> Aktivitas badan internasional dan badan ekstra internasional lainnya
            </td>
        </tr>
    </table>

    <table style="width: 100%">
        <tr style="font-size: 8px">
            <?php for ($i = 1; $i <= $agt_tiap_baris + 1; $i++) : ?>
                <th class="tengah">( <?=$i?> )</th>
            <?php endfor; ?>
        </tr>
        <tr>
            <td style="width: 25%;">401. Nomor urut anggota keluarga</td>
            <?php foreach($dtksAnggota as $key => $agt) : ?>
                <td style="width: 15%;"><?=str_repeat('&nbsp;', 16)?>
                    <?php if (strlen($key + 1) == 1) : ?>
                        <div class="w-25 h-23 inline kotak tengah">0</div>
                        <div class="w-25 h-23 inline kotak tengah"><?= $key + 1 ?? '&nbsp;' ?></div>
                    <?php else : ?>
                        <div class="w-25 h-23 inline kotak tengah"><?= ($key + 1)[0] ?? '&nbsp;' ?></div>
                        <div class="w-25 h-23 inline kotak tengah"><?= ($key + 1)[1] ?? '&nbsp;' ?></div>
                    <?php endif; ?>
                </td>
            <?php $no_urut++; endforeach;?>
            <?php for ($i = $dtksAnggota->count(); $i <=  $agt_tiap_baris - 1; $i++) : ?>
                <td style="width: 15%;"><?=str_repeat('&nbsp;', 16)?>
                    <div class="w-25 h-23 inline kotak tengah"></div>
                    <div class="w-25 h-23 inline kotak tengah"></div>
                </td>
            <?php endfor; ?>
        </tr>
        <tr>
            <?php for ($i = 1; $i <= $agt_tiap_baris + 1; $i++) : ?>
            <td></td>
            <?php endfor; ?>
        </tr>
    </table>
    <table style="width: 100%">
        <tr>
            <th class="tengah" style="width: 100%"><b>D. KEPEMILIKAN USAHA (ANGGOTA KELUARGA USIA 5 TAHUN KE ATAS)</b></th>
        </tr>
    </table>
    <table style="width: 100%">
        <tr>
            <td style="width: 25%;">
                420.a. Apakah (nama) memiliki usaha <?=str_repeat('&nbsp;', 8)?>1. Ya <br>
                <?=str_repeat('&nbsp;', 12)?>sendiri/bersama? (isikan kode)
                <?=str_repeat('&nbsp;', 6)?> 2. Tidak
            </td>
            <?php foreach($dtksAnggota as $key => $agt) : ?>
                <td style="width: 15%;">
                    <?=str_repeat('&nbsp;', 20)?>
                    <div class="w-25 h-23 inline kotak tengah"><?=$agt->kd_punya_usaha_sendiri_bersama?></div>
                    <br><br><?=str_repeat('&nbsp;', 11)?>
                    <b>Kode = 2 ---> 427</b>
                </td>
            <?php endforeach;?>
            <?php for ($i = $dtksAnggota->count(); $i <=  $agt_tiap_baris - 1; $i++) : ?>
                <td style="width: 15%;">
                    <?=str_repeat('&nbsp;', 20)?>
                    <div class="w-25 h-23 inline kotak tengah"></div>
                    <br><br><?=str_repeat('&nbsp;', 11)?>
                    <b>Kode = 2 ---> 427</b>
                </td>
            <?php endfor; ?>
        </tr>
        <tr>
            <td style="width: 25%;">
                <?=str_repeat('&nbsp;', 7)?>b. Berapa jumlah usaha sendiri/bersama yang
                <br><?=str_repeat('&nbsp;', 12)?>dimiliki?

            </td>
            <?php foreach($dtksAnggota as $key => $agt) : ?>
                <td style="width: 15%;">
                    <?=str_repeat('&nbsp;', 15)?>
                    <?php if (strlen($agt->jumlah_usaha_sendiri_bersama) == 1) : ?>
                        <div class="w-25 h-23 inline kotak tengah">0</div>
                        <div class="w-25 h-23 inline kotak tengah"><?= $agt->jumlah_usaha_sendiri_bersama ?? '&nbsp;' ?></div>
                    <?php else : ?>
                        <div class="w-25 h-23 inline kotak tengah"><?= $agt->jumlah_usaha_sendiri_bersama[0] ?? '&nbsp;' ?></div>
                        <div class="w-25 h-23 inline kotak tengah"><?= $agt->jumlah_usaha_sendiri_bersama[1] ?? '&nbsp;' ?></div>
                    <?php endif; ?>
                </td>
            <?php endforeach;?>
            <?php for ($i = $dtksAnggota->count(); $i <=  $agt_tiap_baris - 1; $i++) : ?>
                <td style="width: 15%;">
                    <?=str_repeat('&nbsp;', 15)?>
                    <div class="w-25 h-23 inline kotak tengah"></div>
                    <div class="w-25 h-23 inline kotak tengah"></div>
                </td>
            <?php endfor; ?>
        </tr>
        <tr>
            <td style="width: 25%;;height:55px;">
                421. Apakah lapangan usaha dari usaha utama
                <br><?=str_repeat('&nbsp;', 8)?>tersebut?
                <br><?=str_repeat('&nbsp;', 8)?>(Tulis Selengkap-lengkapnya)
                <br><br><?=str_repeat('&nbsp;', 8)?>(Kode diisi oleh PML)
            </td>
            <?php foreach($dtksAnggota as $key => $agt) : ?>
                <td style="width: 15%">
                    <?php if($agt->tulis_lapangan_usaha_dr_usaha):?>
                        <?php
                             $i = 0;
                             $panjang = 20;
                             $tls_lp_u_p = substr($agt->tulis_lapangan_usaha_dr_usaha, 0, 100);
                             do {
                                 echo substr($tls_lp_u_p, $i, $panjang) . '&nbsp;';
                                 $i += $panjang;
                             } while (strlen($tls_lp_u_p) >= $i);
                        ?>

                        <?php if (strlen($agt->kd_lapangan_usaha_dr_usaha) == 1) : ?>
                                <div class="w-25 h-23 inline kotak tengah" style="font-size:8pt;">0</div>
                                <div class="w-25 h-23 inline kotak tengah" style="font-size:8pt;"><?= $agt->kd_lapangan_usaha_dr_usaha ?? '&nbsp;' ?></div>
                            <?php else : ?>
                                <div class="w-25 h-23 inline kotak tengah" style="font-size:8pt;"><?= $agt->kd_lapangan_usaha_dr_usaha[0] ?? '&nbsp;' ?></div>
                                <div class="w-25 h-23 inline kotak tengah" style="font-size:8pt;"><?= $agt->kd_lapangan_usaha_dr_usaha[1] ?? '&nbsp;' ?></div>
                            <?php endif; ?>
                    <?php else:?>
                        <p style="font-size:20px; padding:0;margin:0;display:inline">
                                ............................
                                ............................
                                ...............
                            <?php if (strlen($agt->kd_lapangan_usaha_dr_usaha) == 1) : ?>
                                <div class="w-25 h-23 inline kotak tengah" style="font-size:8pt;">0</div>
                                <div class="w-25 h-23 inline kotak tengah" style="font-size:8pt;"><?= $agt->kd_lapangan_usaha_dr_usaha ?? '&nbsp;' ?></div>
                            <?php else : ?>
                                <div class="w-25 h-23 inline kotak tengah" style="font-size:8pt;"><?= $agt->kd_lapangan_usaha_dr_usaha[0] ?? '&nbsp;' ?></div>
                                <div class="w-25 h-23 inline kotak tengah" style="font-size:8pt;"><?= $agt->kd_lapangan_usaha_dr_usaha[1] ?? '&nbsp;' ?></div>
                            <?php endif; ?>
                        </p>
                    <?php endif;?>
                </td>
            <?php endforeach;?>
            <?php for ($i = $dtksAnggota->count(); $i <=  $agt_tiap_baris - 1; $i++) : ?>
                <td style="width: 15%;">
                    <p style="font-size:20px; padding:0;margin:0;display:inline">
                        ............................
                        ............................
                        ...............
                        <div class="w-25 h-23 inline kotak tengah" style="font-size:8pt;">&nbsp;</div>
                        <div class="w-25 h-23 inline kotak tengah" style="font-size:8pt;">&nbsp;</div>
                    </p>
                </td>
            <?php endfor; ?>
        </tr>
        <tr>
            <td style="width: 25%;">
                422. Jumlah pekerja yang dibayar pada
                <br><?=str_repeat('&nbsp;', 8)?>usaha utama

            </td>
            <?php foreach($dtksAnggota as $key => $agt) : ?>
                <td style="width: 15%;">
                    <?=str_repeat('&nbsp;', 10)?>
                    <?php if (strlen($agt->jumlah_pekerja_dibayar) == 1) : ?>
                        <div class="w-25 h-23 inline kotak tengah">0</div>
                        <div class="w-25 h-23 inline kotak tengah">0</div>
                        <div class="w-25 h-23 inline kotak tengah"><?= $agt->jumlah_pekerja_dibayar ?? '&nbsp;' ?></div>
                    <?php elseif (strlen($agt->jumlah_pekerja_dibayar) == 2) : ?>
                        <div class="w-25 h-23 inline kotak tengah">0</div>
                        <div class="w-25 h-23 inline kotak tengah"><?= $agt->jumlah_pekerja_dibayar[0] ?? '&nbsp;' ?></div>
                        <div class="w-25 h-23 inline kotak tengah"><?= $agt->jumlah_pekerja_dibayar[1] ?? '&nbsp;' ?></div>
                    <?php else : ?>
                        <div class="w-25 h-23 inline kotak tengah"><?= $agt->jumlah_pekerja_dibayar[0] ?? '&nbsp;' ?></div>
                        <div class="w-25 h-23 inline kotak tengah"><?= $agt->jumlah_pekerja_dibayar[1] ?? '&nbsp;' ?></div>
                        <div class="w-25 h-23 inline kotak tengah"><?= $agt->jumlah_pekerja_dibayar[2] ?? '&nbsp;' ?></div>
                    <?php endif; ?>
                </td>
            <?php endforeach;?>
            <?php for ($i = $dtksAnggota->count(); $i <=  $agt_tiap_baris - 1; $i++) : ?>
                <td style="width: 15%;">
                    <?=str_repeat('&nbsp;', 10)?>
                    <div class="w-25 h-23 inline kotak tengah"></div>
                    <div class="w-25 h-23 inline kotak tengah"></div>
                    <div class="w-25 h-23 inline kotak tengah"></div>
                </td>
            <?php endfor; ?>
        </tr>
        <tr>
            <td style="width: 25%;">
                423. Jumlah pekerja yang tidak dibayar pada
                <br><?=str_repeat('&nbsp;', 8)?>usaha utama
            </td>
            <?php foreach($dtksAnggota as $key => $agt) : ?>
                <td style="width: 15%;">
                    <?=str_repeat('&nbsp;', 10)?>
                    <?php if (strlen($agt->jumlah_pekerja_tidak_dibayar) == 1) : ?>
                        <div class="w-25 h-23 inline kotak tengah">0</div>
                        <div class="w-25 h-23 inline kotak tengah">0</div>
                        <div class="w-25 h-23 inline kotak tengah"><?= $agt->jumlah_pekerja_tidak_dibayar ?? '&nbsp;' ?></div>
                    <?php elseif (strlen($agt->jumlah_pekerja_tidak_dibayar) == 2) : ?>
                        <div class="w-25 h-23 inline kotak tengah">0</div>
                        <div class="w-25 h-23 inline kotak tengah"><?= $agt->jumlah_pekerja_tidak_dibayar[0] ?? '&nbsp;' ?></div>
                        <div class="w-25 h-23 inline kotak tengah"><?= $agt->jumlah_pekerja_tidak_dibayar[1] ?? '&nbsp;' ?></div>
                    <?php else : ?>
                        <div class="w-25 h-23 inline kotak tengah"><?= $agt->jumlah_pekerja_tidak_dibayar[0] ?? '&nbsp;' ?></div>
                        <div class="w-25 h-23 inline kotak tengah"><?= $agt->jumlah_pekerja_tidak_dibayar[1] ?? '&nbsp;' ?></div>
                        <div class="w-25 h-23 inline kotak tengah"><?= $agt->jumlah_pekerja_tidak_dibayar[2] ?? '&nbsp;' ?></div>
                    <?php endif; ?>
                </td>
            <?php endforeach;?>
            <?php for ($i = $dtksAnggota->count(); $i <=  $agt_tiap_baris - 1; $i++) : ?>
                <td style="width: 15%;">
                    <?=str_repeat('&nbsp;', 10)?>
                    <div class="w-25 h-23 inline kotak tengah"></div>
                    <div class="w-25 h-23 inline kotak tengah"></div>
                    <div class="w-25 h-23 inline kotak tengah"></div>
                </td>
            <?php endfor; ?>
        </tr>
        <tr>
            <td style="width: 25%;">
                424. Kepemilikan perizinan usaha utama
                <br><?=str_repeat('&nbsp;', 8)?>(isikan kode)
            </td>
            <?php foreach($dtksAnggota as $key => $agt) : ?>
                <td style="width: 15%;">
                    <?=str_repeat('&nbsp;', 15)?>
                    <?php if (strlen($agt->kd_kepemilikan_ijin_usaha) == 1) : ?>
                        <div class="w-25 h-23 inline kotak tengah">0</div>
                        <div class="w-25 h-23 inline kotak tengah"><?= $agt->kd_kepemilikan_ijin_usaha ?? '&nbsp;' ?></div>
                    <?php else : ?>
                        <div class="w-25 h-23 inline kotak tengah"><?= $agt->kd_kepemilikan_ijin_usaha[0] ?? '&nbsp;' ?></div>
                        <div class="w-25 h-23 inline kotak tengah"><?= $agt->kd_kepemilikan_ijin_usaha[1] ?? '&nbsp;' ?></div>
                    <?php endif; ?>
                </td>
            <?php endforeach;?>
            <?php for ($i = $dtksAnggota->count(); $i <=  $agt_tiap_baris - 1; $i++) : ?>
                <td style="width: 15%;">
                    <?=str_repeat('&nbsp;', 15)?>
                    <div class="w-25 h-23 inline kotak tengah"></div>
                    <div class="w-25 h-23 inline kotak tengah"></div>
                </td>
            <?php endfor; ?>
        </tr>
        <tr>
            <td rowspan="2" style="width: 25%;">
                425. Omzet usaha utama perbulan (Rupiah)
                <br><?=str_repeat('&nbsp;', 8)?>(isikan kode)
            </td>
            <td style="width: 75%;" colspan="<?= $agt_tiap_baris?>">
                <b>( 1 )</b> < 5 Juta (ultra mikro)
                <?=str_repeat('&nbsp;', 8)?><b>( 2 )</b> 5 -< 15 Juta (ultra mikro)
                <?=str_repeat('&nbsp;', 8)?><b>( 3 )</b> 15 -< 25 Juta (ultra mikro)
                <?=str_repeat('&nbsp;', 8)?><b>( 4 )</b> 25 -< 167 Juta (mikro)
                <?=str_repeat('&nbsp;', 8)?><b>( 5 )</b> 167 -< 1.250 Juta (kecil)
                <br><b>( 6 )</b> 1.250 -< 4.167 Juta (menengah)
                <?=str_repeat('&nbsp;', 8)?><b>( 7 )</b> lebih besar sama dengan 4.167 Juta (besar)
            </td>
        </tr>
        <tr>
            <?php foreach($dtksAnggota as $key => $agt) : ?>
                <td style="width: 15%;">
                    <?=str_repeat('&nbsp;', 20)?>
                    <div class="w-25 h-23 inline kotak tengah"><?= $agt->kd_omset_usaha_perbulan ?? '&nbsp;' ?></div>
                </td>
            <?php endforeach;?>
            <?php for ($i = $dtksAnggota->count(); $i <=  $agt_tiap_baris - 1; $i++) : ?>
                <td style="width: 15%;">
                    <?=str_repeat('&nbsp;', 15)?>
                    <div class="w-25 h-23 inline kotak tengah"></div>
                </td>
            <?php endfor; ?>
        </tr>
        <tr>
            <td rowspan="2" style="width: 25%;">
                426. Penggunaan internet dalam kegiatan
                <br><?=str_repeat('&nbsp;', 8)?>usaha utama (jumlahkan kode)
            </td>
            <td style="width: 75%" colspan="<?=$agt_tiap_baris?>">
                <b>( 00 )</b> Tidak menggunakan internet
                <?=str_repeat('&nbsp;', 8)?><b>( 01 )</b> Sebagai sarana komunikasi
                <?=str_repeat('&nbsp;', 8)?><b>( 02 )</b> Untuk mencari informasi
                <?=str_repeat('&nbsp;', 8)?><b>( 04 )</b> Sebagai Pemasaran/Iklan
                <br><b>( 08 )</b> Sebagai Sarana Penjualan Produk/Output
                <?=str_repeat('&nbsp;', 8)?><b>( 16 )</b> Sebagai Pembelian dan/atau Produksi
                <?=str_repeat('&nbsp;', 8)?><b>( 32 )</b> Lainnya
            </td>
        </tr>
        <tr>
            <?php foreach($dtksAnggota as $key => $agt) : ?>
                <td style="width: 15%;">
                    <?=str_repeat('&nbsp;', 15)?>
                    <?php if (strlen($agt->kd_guna_internet_usaha) == 1) : ?>
                        <div class="w-25 h-23 inline kotak tengah">0</div>
                        <div class="w-25 h-23 inline kotak tengah"><?= $agt->kd_guna_internet_usaha ?? '&nbsp;' ?></div>
                    <?php else : ?>
                        <div class="w-25 h-23 inline kotak tengah"><?= $agt->kd_guna_internet_usaha[0] ?? '&nbsp;' ?></div>
                        <div class="w-25 h-23 inline kotak tengah"><?= $agt->kd_guna_internet_usaha[1] ?? '&nbsp;' ?></div>
                    <?php endif; ?>
                    &nbsp;<?=implode(',', tentukanJumlahTerpilih([32,16,8,4,2,1,0],$agt->kd_guna_internet_usaha))?>
                </td>
            <?php endforeach;?>
            <?php for ($i = $dtksAnggota->count(); $i <=  $agt_tiap_baris - 1; $i++) : ?>
                <td style="width: 15%;">
                    <?=str_repeat('&nbsp;', 15)?>
                    <div class="w-25 h-23 inline kotak tengah"></div>
                    <div class="w-25 h-23 inline kotak tengah"></div>
                </td>
            <?php endfor; ?>
        </tr>
        <tr>
            <td style="line-height: 15px;" colspan="<?=$agt_tiap_baris + 1?>">
                <b>Kode 424: Kepemilikan perizinan usaha utama</b>
                <br><b>(01)</b> Surat Izin Tempat Usaha (SITU)
                <?=str_repeat('&nbsp;', 4)?><b>( 02 )</b> Surat Izin Usaha Perdagangan (SIUP)
                <?=str_repeat('&nbsp;', 4)?><b>( 03 )</b> Nomor Registrasi Perusahaan (NRP)
                <?=str_repeat('&nbsp;', 4)?><b>( 04 )</b> Nomor Induk Berusaha (NIB)
                <?=str_repeat('&nbsp;', 4)?><b>( 05 )</b> Surat Keterangan Domisili Perusahaan (SKDP)
                <br><b>(06)</b> Analisis Mengenai Dampak Lingkungan (Amdal)
                <?=str_repeat('&nbsp;', 4)?><b>( 07 )</b> Surat Izin Mendirikan Bangunan (SIMB)
                <?=str_repeat('&nbsp;', 4)?><b>( 08 )</b> Surat Keputusan Badan Hukum (SKBH)
                <?=str_repeat('&nbsp;', 4)?><b>( 09 )</b> Akta Pendirian Perseroan Terbatas (APPT)
                <br><b>(10)</b> Surat izin lainnya
                <?=str_repeat('&nbsp;', 4)?><b>( 11 )</b> Belum memiliki izin usaha
                <?=str_repeat('&nbsp;', 4)?><b>( 12 )</b> Surat Izin Gangguan
            </td>
        </tr>
        <tr>
            <td style="line-height: 15px;" colspan="<?=$agt_tiap_baris + 1?>">
                <b>Kode 417 dan Kode 421: Lapangan usaha (Diisi oleh PML) </b>
                <br><b>(01)</b> Pertanian tanaman padi & palawija
                <?=str_repeat('&nbsp;', 4)?><b>( 02 )</b> Hortikultura
                <?=str_repeat('&nbsp;', 4)?><b>( 03 )</b> Perkebunan
                <?=str_repeat('&nbsp;', 4)?><b>( 04 )</b> Perikanan
                <?=str_repeat('&nbsp;', 4)?><b>( 05 )</b> Peternakan
                <?=str_repeat('&nbsp;', 4)?><b>( 06 )</b> Kehutanan & pertanian lainnya
                <?=str_repeat('&nbsp;', 4)?><b>( 07 )</b> Pertambangan/penggalian
                <?=str_repeat('&nbsp;', 4)?><b>( 08 )</b> Industri pengolahan
                <br><b>(09)</b> Pengadaan listrik, gas, uap/air panas, & udara dingin
                <?=str_repeat('&nbsp;', 8)?><b>( 10 )</b> Pengelolaan air, pengelolaan air limbah, pengelolaan dan daur ulang sampah, dan aktivitas remediasi
                <?=str_repeat('&nbsp;', 8)?><b>( 11 )</b> Konstruksi
                <br><b>(12)</b> Perdagangan besar dan eceran, reparasi dan perawatan mobil dan sepeda motor
                <?=str_repeat('&nbsp;', 8)?><b>( 13 )</b> Pengangkutan dan pergudangan
                <?=str_repeat('&nbsp;', 8)?><b>( 14 )</b> Penyediaan akomodasi & makan minum
                <?=str_repeat('&nbsp;', 8)?><b>( 15 )</b> Informasi & komunikasi
                <br><b>(16)</b> Keuangan & asuransi
                <?=str_repeat('&nbsp;', 4)?><b>( 17 )</b> Real estate
                <?=str_repeat('&nbsp;', 4)?><b>( 18 )</b> Aktivitas profesional, ilmiah, dan teknis
                <?=str_repeat('&nbsp;', 8)?><b>( 19 )</b> Aktivitas penyewaan dan sewa guna tanpa hak opsi, ketenagakerjaan, agen perjalanan, dan penunjang usaha lainnya
                <br><b>(20)</b> Administrasi pemerintahan, pertahanan, dan jaminan sosial wajib
                <?=str_repeat('&nbsp;', 6)?><b>( 21 )</b> Pendidikan
                <?=str_repeat('&nbsp;', 6)?><b>( 22 )</b> Aktivitas kesehatan manusia dan aktivitas sosial
                <?=str_repeat('&nbsp;', 6)?><b>( 23 )</b> Kesenian, hiburan, dan rekreasi
                <?=str_repeat('&nbsp;', 6)?><b>( 24 )</b> Aktivitas jasa lainnya
                <br><b>(25)</b> Aktivitas keluarga sebagai pemberi kerja
                <?=str_repeat('&nbsp;', 8)?><b>( 26 )</b> Aktivitas badan internasional dan badan ekstra internasional lainnya
            </td>
        </tr>
    </table>
    <table style="width: 100%">
        <tr style="font-size: 8px">
            <?php for ($i = 1; $i <= $agt_tiap_baris + 1; $i++) : ?>
                <th class="tengah">( <?=$i?> )</th>
            <?php endfor; ?>
        </tr>
        <tr>
            <td style="width: 25%;">401. Nomor urut anggota keluarga</td>
            <?php foreach($dtksAnggota as $key => $agt) : ?>
                <td style="width: 15%;"><?=str_repeat('&nbsp;', 16)?>
                    <?php if (strlen($key + 1) == 1) : ?>
                        <div class="w-25 h-23 inline kotak tengah">0</div>
                        <div class="w-25 h-23 inline kotak tengah"><?= $key + 1 ?? '&nbsp;' ?></div>
                    <?php else : ?>
                        <div class="w-25 h-23 inline kotak tengah"><?= ($key + 1)[0] ?? '&nbsp;' ?></div>
                        <div class="w-25 h-23 inline kotak tengah"><?= ($key + 1)[1] ?? '&nbsp;' ?></div>
                    <?php endif; ?>
                </td>
            <?php $no_urut++; endforeach;?>
            <?php for ($i = $dtksAnggota->count(); $i <=  $agt_tiap_baris - 1; $i++) : ?>
                <td style="width: 15%;"><?=str_repeat('&nbsp;', 16)?>
                    <div class="w-25 h-23 inline kotak tengah"></div>
                    <div class="w-25 h-23 inline kotak tengah"></div>
                </td>
            <?php endfor; ?>
        </tr>
        <tr>
            <?php for ($i = 1; $i <= $agt_tiap_baris + 1; $i++) : ?>
            <td></td>
            <?php endfor; ?>
        </tr>
    </table>
    <table style="width: 100%">
        <tr>
            <th class="tengah" style="width: 100%"><b>E. KESEHATAN</b></th>
        </tr>
    </table>

    <table style="width: 100%;">
        <tr>
            <td style="width: 25%;" rowspan="2">
                <b>PERTANYAAN 427 UNTUK USIA 0-4 TAHUN</b>
                <br>427. Bagaimana kondisi gizi anak dari pemeriksaan
                <br><?=str_repeat('&nbsp;', 8)?>3 bulan terakhir di posyandu/puskesmas/
                <br><?=str_repeat('&nbsp;', 8)?>rumah sakit dengan mengacu pada
                <br><?=str_repeat('&nbsp;', 8)?>catatan/buku kontrol? (isikan kode)
            </td>
            <td style="width: 75%;" colspan="<?=$agt_tiap_baris ?>">
                <b>( 1 )</b> Kurang Gizi (Wasting)
                <?=str_repeat('&nbsp;', 4)?><b>( 2 )</b> Kerdil (Stunting)
                <?=str_repeat('&nbsp;', 4)?><b>( 3 )</b> Tidak ada catatan
                <?=str_repeat('&nbsp;', 4)?><b>( 8 )</b> Tidak tahu
            </td>
        </tr>
        <tr>
            <?php foreach($dtksAnggota as $key => $agt) : ?>
                <td style="width: 15%;">
                    <?=str_repeat('&nbsp;', 20)?>
                    <div class="w-25 h-23 inline kotak tengah"><?=$agt->kd_gizi_seimbang?></div>
                </td>
            <?php endforeach;?>
            <?php for ($i = $dtksAnggota->count(); $i <=  $agt_tiap_baris - 1; $i++) : ?>
                <td style="width: 15%;">
                    <?=str_repeat('&nbsp;', 20)?>
                    <div class="w-25 h-23 inline kotak tengah"></div>
                </td>
            <?php endfor; ?>
        </tr>
        <tr>
            <td colspan="<?=$agt_tiap_baris + 1?>">
                <b>( Kode 428.a - 428.i )</b>
                <?=str_repeat('&nbsp;', 4)?>1. Ya, sama sekali tidak bisa
                <?=str_repeat('&nbsp;', 10)?>2. Ya, banyak kesulitan dan membutuhkan bantuan
                <?=str_repeat('&nbsp;', 10)?>3. Ya, sedikit kesulitan, tapi tidak membutuhkan bantuan
                <?=str_repeat('&nbsp;', 10)?>4. Tidak mengalami kesulitan
            </td>
        </tr>
    </table>
    <table style="width: 100%;">
        <tr>
            <td style="width: 100%;" colspan="<?=$agt_tiap_baris + 1?>">
                <b>PERTANYAAN 428.A - 428.F UNTUK USIA 2 TAHUN KEATAS</b>
            </td>
        </tr>
        <tr>
            <td style="width: 25%;">
                428.a.Apakah (nama) mengalami Kesulitan/
                <?=str_repeat('&nbsp;', 6)?>Gangguan Penglihatan meskipun menggu-
                <?=str_repeat('&nbsp;', 6)?>nakan alat bantu melihat? (isikan kode)
            </td>
            <?php foreach($dtksAnggota as $key => $agt) : ?>
                <td style="width: 15%;">
                    <?=str_repeat('&nbsp;', 20)?>
                    <div class="w-25 h-23 inline kotak tengah"><?=$agt->kd_sulit_penglihatan?></div>
                </td>
            <?php endforeach;?>
            <?php for ($i = $dtksAnggota->count(); $i <=  $agt_tiap_baris - 1; $i++) : ?>
                <td style="width: 15%;">
                    <?=str_repeat('&nbsp;', 20)?>
                    <div class="w-25 h-23 inline kotak tengah"></div>
                </td>
            <?php endfor; ?>
        </tr>
        <tr>
            <td style="width: 25%;">
                428.b.Apakah (nama) mengalami Kesulitan/
                <?=str_repeat('&nbsp;', 5)?>Gangguan Pendengaran meskipun menggu-
                <?=str_repeat('&nbsp;', 5)?>nakan alat bantu mendengar? (isikan kode)
            </td>
            <?php foreach($dtksAnggota as $key => $agt) : ?>
                <td style="width: 15%;">
                    <?=str_repeat('&nbsp;', 20)?>
                    <div class="w-25 h-23 inline kotak tengah"><?=$agt->kd_sulit_pendengaran?></div>
                </td>
            <?php endforeach;?>
            <?php for ($i = $dtksAnggota->count(); $i <=  $agt_tiap_baris - 1; $i++) : ?>
                <td style="width: 15%;">
                    <?=str_repeat('&nbsp;', 20)?>
                    <div class="w-25 h-23 inline kotak tengah"></div>
                </td>
            <?php endfor; ?>
        </tr>
        <tr>
            <td style="width: 25%;">
                428.c.Apakah (nama) mengalami Kesulitan/
                <?=str_repeat('&nbsp;', 6)?>Gangguan Berjalan atau Naik Tangga?
                <br><?=str_repeat('&nbsp;', 6)?>(isikan kode)
            </td>
            <?php foreach($dtksAnggota as $key => $agt) : ?>
                <td style="width: 15%;">
                    <?=str_repeat('&nbsp;', 20)?>
                    <div class="w-25 h-23 inline kotak tengah"><?=$agt->kd_sulit_jalan_naiktangga?></div>
                </td>
            <?php endforeach;?>
            <?php for ($i = $dtksAnggota->count(); $i <=  $agt_tiap_baris - 1; $i++) : ?>
                <td style="width: 15%;">
                    <?=str_repeat('&nbsp;', 20)?>
                    <div class="w-25 h-23 inline kotak tengah"></div>
                </td>
            <?php endfor; ?>
        </tr>
        <tr>
            <td style="width: 25%;">
                428.d.Apakah (nama) mengalami Kesulitan/
                <?=str_repeat('&nbsp;', 6)?>Gangguan menggerakan/menggunakan
                <br><?=str_repeat('&nbsp;', 6)?> Tangan/Jari? (isikan kode)
            </td>
            <?php foreach($dtksAnggota as $key => $agt) : ?>
                <td style="width: 15%;">
                    <?=str_repeat('&nbsp;', 20)?>
                    <div class="w-25 h-23 inline kotak tengah"><?=$agt->kd_sulit_gerak_tangan_jari?></div>
                </td>
            <?php endforeach;?>
            <?php for ($i = $dtksAnggota->count(); $i <=  $agt_tiap_baris - 1; $i++) : ?>
                <td style="width: 15%;">
                    <?=str_repeat('&nbsp;', 20)?>
                    <div class="w-25 h-23 inline kotak tengah"></div>
                </td>
            <?php endfor; ?>
        </tr>
        <tr>
            <td style="width: 25%;">
                428.e.Dibandingkan dengan penduduk yang
                <?=str_repeat('&nbsp;', 6)?>sebaya, Apakah (nama) mengalami
                <?=str_repeat('&nbsp;', 6)?>Kesulitan/Gangguan Belajar atau
                <br><?=str_repeat('&nbsp;', 6)?>Kemampuan Intelektual? (isikan kode)
            </td>
            <?php foreach($dtksAnggota as $key => $agt) : ?>
                <td style="width: 15%;">
                    <?=str_repeat('&nbsp;', 20)?>
                    <div class="w-25 h-23 inline kotak tengah"><?=$agt->kd_sulit_belajar_intelektual?></div>
                </td>
            <?php endforeach;?>
            <?php for ($i = $dtksAnggota->count(); $i <=  $agt_tiap_baris - 1; $i++) : ?>
                <td style="width: 15%;">
                    <?=str_repeat('&nbsp;', 20)?>
                    <div class="w-25 h-23 inline kotak tengah"></div>
                </td>
            <?php endfor; ?>
        </tr>
        <tr>
            <td style="width: 25%;">
                428.f.Dibandingkan dengan penduduk yang
                <?=str_repeat('&nbsp;', 6)?>sebaya, Apakah (nama) mengalami
                <?=str_repeat('&nbsp;', 6)?>Kesulitan/Gangguan mengendalikan
                <br><?=str_repeat('&nbsp;', 6)?>Perilaku? (isikan kode)
            </td>
            <?php foreach($dtksAnggota as $key => $agt) : ?>
                <td style="width: 15%;">
                    <?=str_repeat('&nbsp;', 20)?>
                    <div class="w-25 h-23 inline kotak tengah"><?=$agt->kd_sulit_perilaku_emosi?></div>
                </td>
            <?php endforeach;?>
            <?php for ($i = $dtksAnggota->count(); $i <=  $agt_tiap_baris - 1; $i++) : ?>
                <td style="width: 15%;">
                    <?=str_repeat('&nbsp;', 20)?>
                    <div class="w-25 h-23 inline kotak tengah"></div>
                </td>
            <?php endfor; ?>
        </tr>
        <tr>
            <td style="width: 100%;" colspan="<?=$agt_tiap_baris + 1?>">
                <b>PERTANYAAN 428.G - 428.J UNTUK USIA 5 TAHUN KEATAS</b>
            </td>
        </tr>
        <tr>
            <td style="width: 25%;">
                428.g.Apakah (nama) mengalami Kesulitan/
                <?=str_repeat('&nbsp;', 6)?>Gangguan Berbicara/Berkomuni-
                <br><?=str_repeat('&nbsp;', 6)?>kasi? (isikan kode)
            </td>
            <?php foreach($dtksAnggota as $key => $agt) : ?>
                <td style="width: 15%;">
                    <?=str_repeat('&nbsp;', 20)?>
                    <div class="w-25 h-23 inline kotak tengah"><?=$agt->kd_sulit_paham_bicara_kom?></div>
                </td>
            <?php endforeach;?>
            <?php for ($i = $dtksAnggota->count(); $i <=  $agt_tiap_baris - 1; $i++) : ?>
                <td style="width: 15%;">
                    <?=str_repeat('&nbsp;', 20)?>
                    <div class="w-25 h-23 inline kotak tengah"></div>
                </td>
            <?php endfor; ?>
        </tr>
        <tr>
            <td style="width: 25%;">
                428.h.Apakah (nama) mengalami Kesulitan/
                <?=str_repeat('&nbsp;', 6)?>Gangguan untuk Mengurus Diri Sendiri?
                <br><?=str_repeat('&nbsp;', 6)?>(seperti mandi, makan, berpakaian,
                <br><?=str_repeat('&nbsp;', 6)?>BAK, BAB) (isikan kode)
            </td>
            <?php foreach($dtksAnggota as $key => $agt) : ?>
                <td style="width: 15%;">
                    <?=str_repeat('&nbsp;', 20)?>
                    <div class="w-25 h-23 inline kotak tengah"><?=$agt->kd_sulit_mandiri?></div>
                </td>
            <?php endforeach;?>
            <?php for ($i = $dtksAnggota->count(); $i <=  $agt_tiap_baris - 1; $i++) : ?>
                <td style="width: 15%;">
                    <?=str_repeat('&nbsp;', 20)?>
                    <div class="w-25 h-23 inline kotak tengah"></div>
                </td>
            <?php endfor; ?>
        </tr>
        <tr>
            <td style="width: 25%;">
                428.i.Apakah (nama) mengalami Kesulitan/
                <?=str_repeat('&nbsp;', 6)?>Gangguan Mengingat/Berkonsentrasi?
                <br><?=str_repeat('&nbsp;', 6)?>(isikan kode)
            </td>
            <?php foreach($dtksAnggota as $key => $agt) : ?>
                <td style="width: 15%;">
                    <?=str_repeat('&nbsp;', 20)?>
                    <div class="w-25 h-23 inline kotak tengah"><?=$agt->kd_sulit_ingat_konsentrasi?></div>
                </td>
            <?php endforeach;?>
            <?php for ($i = $dtksAnggota->count(); $i <=  $agt_tiap_baris - 1; $i++) : ?>
                <td style="width: 15%;">
                    <?=str_repeat('&nbsp;', 20)?>
                    <div class="w-25 h-23 inline kotak tengah"></div>
                </td>
            <?php endfor; ?>
        </tr>
    </table>
    <table style="width: 100%;">
        <tr style="font-size: 8px">
            <?php for ($i = 1; $i <= $agt_tiap_baris + 1; $i++) : ?>
                <th class="tengah">( <?=$i?> )</th>
            <?php endfor; ?>
        </tr>
        <tr>
            <td style="width: 25%;">401. Nomor urut anggota keluarga</td>
            <?php foreach($dtksAnggota as $key => $agt) : ?>
                <td style="width: 15%;"><?=str_repeat('&nbsp;', 16)?>
                    <?php if (strlen($key + 1) == 1) : ?>
                        <div class="w-25 h-23 inline kotak tengah">0</div>
                        <div class="w-25 h-23 inline kotak tengah"><?= $key + 1 ?? '&nbsp;' ?></div>
                    <?php else : ?>
                        <div class="w-25 h-23 inline kotak tengah"><?= ($key + 1)[0] ?? '&nbsp;' ?></div>
                        <div class="w-25 h-23 inline kotak tengah"><?= ($key + 1)[1] ?? '&nbsp;' ?></div>
                    <?php endif; ?>
                </td>
            <?php endforeach;?>
            <?php for ($i = $dtksAnggota->count(); $i <=  $agt_tiap_baris - 1; $i++) : ?>
                <td style="width: 15%;"><?=str_repeat('&nbsp;', 16)?>
                    <div class="w-25 h-23 inline kotak tengah"></div>
                    <div class="w-25 h-23 inline kotak tengah"></div>
                </td>
            <?php endfor; ?>
        </tr>
        <tr>
            <?php for ($i = 1; $i <= $agt_tiap_baris + 1; $i++) : ?>
            <td></td>
            <?php endfor; ?>
        </tr>
        <tr>
            <td style="width: 100%;" colspan="<?=$agt_tiap_baris + 1?>">
                <b>PERTANYAAN 428.G - 428.J UNTUK USIA 5 TAHUN KEATAS</b>
            </td>
        </tr>
        <tr>
            <td style="width: 25%;" rowspan="2">
                428.j.Seberapa sering (nama) mengalami
                <br><?=str_repeat('&nbsp;', 5)?>gangguan kesedihan depresi? (isikan kode)
            </td>
            <td style="width:75%;" colspan="<?=$agt_tiap_baris?>">
                1. Sangat sering
                <?=str_repeat('&nbsp;', 8)?>2. Sering
                <?=str_repeat('&nbsp;', 8)?>3. Jarang
                <?=str_repeat('&nbsp;', 8)?>4. Tidak pernah
            </td>
        </tr>
        <tr>
            <?php foreach($dtksAnggota as $key => $agt) : ?>
                <td style="width: 15%;">
                    <?=str_repeat('&nbsp;', 20)?>
                    <div class="w-25 h-23 inline kotak tengah"><?=$agt->kd_sering_sedih_depresi?></div>
                </td>
            <?php endforeach;?>
            <?php for ($i = $dtksAnggota->count(); $i <=  $agt_tiap_baris - 1; $i++) : ?>
                <td style="width: 15%;">
                    <?=str_repeat('&nbsp;', 20)?>
                    <div class="w-25 h-23 inline kotak tengah"></div>
                </td>
            <?php endfor; ?>
        </tr>
        <tr>
            <td colspan="<?=$agt_tiap_baris + 1?>">
                <b>Untuk 429.</b> Jika (nama) berusia 60 tahun ke atas atau 428.a - 428.j ada yang berkode 1 atau 2
                <?=str_repeat('&nbsp;', 20)?><b>Kode 429.</b>
                <?=str_repeat('&nbsp;', 6)?>1. Ya, Anggota Keluarga
                <?=str_repeat('&nbsp;', 6)?>2. Ya, Bukan Anggota Keluarga
                <?=str_repeat('&nbsp;', 6)?>3. Ya, Tinggal Sendiri
            </td>
        </tr>
        <tr>
            <td style="width: 23%;" >
                429. Apakah (nama) memiliki caregiver/
                <br><?=str_repeat('&nbsp;', 4)?>pemberi rawat/pengasuh/wali? (isikan kode)
            </td>
            <?php foreach($dtksAnggota as $key => $agt) : ?>
                <td style="width: 15%;">
                    <?=str_repeat('&nbsp;', 20)?>
                    <div class="w-25 h-23 inline kotak tengah"><?=$agt->kd_sering_sedih_depresi?></div>
                </td>
            <?php endforeach;?>
            <?php for ($i = $dtksAnggota->count(); $i <=  $agt_tiap_baris - 1; $i++) : ?>
                <td style="width: 15%;">
                    <?=str_repeat('&nbsp;', 20)?>
                    <div class="w-25 h-23 inline kotak tengah"></div>
                </td>
            <?php endfor; ?>
        </tr>
        <tr>
            <td style="width: 23%;" >
                430.Apakah (nama) memiliki keluhan kesehat-
                <br><?=str_repeat('&nbsp;', 8)?>an kronis/menahun? (isikan kode)
            </td>
            <?php foreach($dtksAnggota as $key => $agt) : ?>
                <td style="width: 15%;">
                    <?=str_repeat('&nbsp;', 20)?>
                    <div class="w-25 h-23 inline kotak tengah"><?=$agt->kd_penyakit_kronis_menahun?></div>
                </td>
            <?php endforeach;?>
            <?php for ($i = $dtksAnggota->count(); $i <=  $agt_tiap_baris - 1; $i++) : ?>
                <td style="width: 15%;">
                    <?=str_repeat('&nbsp;', 20)?>
                    <div class="w-25 h-23 inline kotak tengah"></div>
                </td>
            <?php endfor; ?>
        </tr>
        <tr>
            <td colspan="<?=$agt_tiap_baris + 1?>">
                <b>Kode 430. <?=str_repeat('&nbsp;', 4)?> ( 01 )</b> Tidak Ada
                <?=str_repeat('&nbsp;', 8)?><b>( 02 )</b> Hipertensi (darah tinggi)
                <?=str_repeat('&nbsp;', 8)?><b>( 03 )</b> Rematik
                <?=str_repeat('&nbsp;', 8)?><b>( 04 )</b> Asma
                <?=str_repeat('&nbsp;', 8)?><b>( 05 )</b> Masalah jantung
                <?=str_repeat('&nbsp;', 8)?><b>( 06 )</b> Diabetes (kencing manis)
                <?=str_repeat('&nbsp;', 8)?><b>( 07 )</b> Tuberculosis (TBC)
                <?=str_repeat('&nbsp;', 8)?><b>( 08 )</b> Stroke
                <br><b>( 09 )</b> Kanker atau tumor ganas
                <?=str_repeat('&nbsp;', 6)?><b>( 10 )</b> Gagal ginjal
                <?=str_repeat('&nbsp;', 6)?><b>( 11 )</b> Haemophilia
                <?=str_repeat('&nbsp;', 6)?><b>( 12 )</b> HIV/AIDS
                <?=str_repeat('&nbsp;', 6)?><b>( 13 )</b> Kolesterol
                <?=str_repeat('&nbsp;', 6)?><b>( 14 )</b> Sirosis Hati
                <?=str_repeat('&nbsp;', 6)?><b>( 15 )</b> Thalasemia
                <?=str_repeat('&nbsp;', 6)?><b>( 16 )</b> Leukimia
                <?=str_repeat('&nbsp;', 6)?><b>( 17 )</b> Alzheimer
                <?=str_repeat('&nbsp;', 4)?><b>( 18 )</b> Lainnya
            </td>
        </tr>
    </table>

    <table style="width: 100%">
        <tr>
            <th class="tengah" style="width: 100%"><b>F. PROGRAM PERLINDUNGAN SOSIAL</b></th>
        </tr>
    </table>
    <table style="width: 100%;">
        <tr>
            <td style="width: 25%;" rowspan="2">
                431.a. Dalam satu tahun terakhir, Apakah (nama)
                <br><?=str_repeat('&nbsp;', 8)?>memiliki jaminan kesehatan?
                <br><?=str_repeat('&nbsp;', 8)?>(jumlahkan kode)
            </td>
            <td style="width:75%;" colspan="<?=$agt_tiap_baris?>">
                0. Tidak memiliki
                <?=str_repeat('&nbsp;', 8)?>1. PBI/JKN
                <?=str_repeat('&nbsp;', 8)?>2. JKN Mandiri
                <?=str_repeat('&nbsp;', 8)?>4. JKN Pemberi Kerja
                <?=str_repeat('&nbsp;', 8)?>8. Jamkes lainnya
                <?=str_repeat('&nbsp;', 8)?>99. Lainnya
            </td>
        </tr>
        <tr>
            <?php foreach($dtksAnggota as $key => $agt) : ?>
                <td style="width: 15%;">
                    <?=str_repeat('&nbsp;', 15)?>
                    <?php if (strlen($agt->kd_jamkes_setahun) == 1) : ?>
                        <div class="w-25 h-23 inline kotak tengah">0</div>
                        <div class="w-25 h-23 inline kotak tengah"><?= $agt->kd_jamkes_setahun ?? '&nbsp;' ?></div>
                    <?php else : ?>
                        <div class="w-25 h-23 inline kotak tengah"><?= ($agt->kd_jamkes_setahun)[0] ?? '&nbsp;' ?></div>
                        <div class="w-25 h-23 inline kotak tengah"><?= ($agt->kd_jamkes_setahun)[1] ?? '&nbsp;' ?></div>
                    <?php endif; ?>
                    &nbsp;<?=implode(',', tentukanJumlahTerpilih([99,8,4,2,1,0],$agt->kd_jamkes_setahun))?>
                </td>
            <?php endforeach;?>
            <?php for ($i = $dtksAnggota->count(); $i <=  $agt_tiap_baris - 1; $i++) : ?>
                <td style="width: 15%;">
                    <?=str_repeat('&nbsp;', 15)?>
                    <div class="w-25 h-23 inline kotak tengah"></div>
                    <div class="w-25 h-23 inline kotak tengah"></div>
                </td>
            <?php endfor; ?>
        </tr>
        <tr>
            <td style="width: 100%;" colspan="<?=$agt_tiap_baris + 1?>">
                <b>( PERTANYAAN 431.B - 431.D UNTUK USIA 5 TAHUN KEATAS )</b>
                <?=str_repeat('&nbsp;', 20)?>
                <b>Kode 431.B - 431.E</b>
                <?=str_repeat('&nbsp;', 8)?>1. Ya
                <?=str_repeat('&nbsp;', 8)?>2. Tidak
                <?=str_repeat('&nbsp;', 8)?>8. Tidak tahu
            </td>
        </tr>
        <tr>
            <td style="width: 25%;" >
                431.b Dalam satu tahun terakhir, Apakah
                <br><?=str_repeat('&nbsp;', 8)?>(nama) ikut serta dalam Program
                <br><?=str_repeat('&nbsp;', 8)?>Pra-Kerja? (isikan kode)
            </td>
            <?php foreach($dtksAnggota as $agt) : ?>
                <td style="width: 15%;">
                    <?=str_repeat('&nbsp;', 20)?>
                    <div class="w-25 h-23 inline kotak tengah"><?=$agt->kd_ikut_prakerja?></div>
                </td>
            <?php endforeach;?>
            <?php for ($i = $dtksAnggota->count(); $i <=  $agt_tiap_baris - 1; $i++) : ?>
                <td style="width: 15%;">
                    <?=str_repeat('&nbsp;', 20)?>
                    <div class="w-25 h-23 inline kotak tengah"></div>
                </td>
            <?php endfor; ?>
        </tr>
        <tr>
            <td style="width: 25%;" >
                431.c Dalam satu tahun terakhir, Apakah
                <br><?=str_repeat('&nbsp;', 8)?>(nama) ikut serta dalam Program Kredit
                <br><?=str_repeat('&nbsp;', 8)?>Usaha Rakyat (KUR)? (isikan kode)
            </td>
            <?php foreach($dtksAnggota as $agt) : ?>
                <td style="width: 15%;">
                    <?=str_repeat('&nbsp;', 20)?>
                    <div class="w-25 h-23 inline kotak tengah"><?=$agt->kd_ikut_kur?></div>
                </td>
            <?php endforeach;?>
            <?php for ($i = $dtksAnggota->count(); $i <=  $agt_tiap_baris - 1; $i++) : ?>
                <td style="width: 15%;">
                    <?=str_repeat('&nbsp;', 20)?>
                    <div class="w-25 h-23 inline kotak tengah"></div>
                </td>
            <?php endfor; ?>
        </tr>
        <tr>
            <td style="width: 25%;" >
                431.d Dalam satu tahun terakhir, Apakah (nama)
                <br><?=str_repeat('&nbsp;', 8)?>ikut serta dalam Program Pembiayaan
                <br><?=str_repeat('&nbsp;', 8)?>Ultra Mikro (UMI)? (isikan kode)
            </td>
            <?php foreach($dtksAnggota as $agt) : ?>
                <td style="width: 15%;">
                    <?=str_repeat('&nbsp;', 20)?>
                    <div class="w-25 h-23 inline kotak tengah"><?=$agt->kd_ikut_umi?></div>
                </td>
            <?php endforeach;?>
            <?php for ($i = $dtksAnggota->count(); $i <=  $agt_tiap_baris - 1; $i++) : ?>
                <td style="width: 15%;">
                    <?=str_repeat('&nbsp;', 20)?>
                    <div class="w-25 h-23 inline kotak tengah"></div>
                </td>
            <?php endfor; ?>
        </tr>
        <tr>
            <td style="width: 100%;" colspan="<?=$agt_tiap_baris + 1?>">
                <b>( PERTANYAAN 431.E UNTUK USIA 5 - 30 TAHUN )</b>
            </td>
        </tr>
        <tr>
            <td style="width: 25%;" >
                431.e Dalam satu tahun terakhir, Apakah (nama)
                <br><?=str_repeat('&nbsp;', 8)?>ikut serta dalam Program Indonesia
                <br><?=str_repeat('&nbsp;', 8)?>Pintar (PIP)? (isikan kode)
            </td>
            <?php foreach($dtksAnggota as $agt) : ?>
                <td style="width: 15%;">
                    <?=str_repeat('&nbsp;', 20)?>
                    <div class="w-25 h-23 inline kotak tengah"><?=$agt->kd_ikut_pip?></div>
                </td>
            <?php endforeach;?>
            <?php for ($i = $dtksAnggota->count(); $i <=  $agt_tiap_baris - 1; $i++) : ?>
                <td style="width: 15%;">
                    <?=str_repeat('&nbsp;', 20)?>
                    <div class="w-25 h-23 inline kotak tengah"></div>
                </td>
            <?php endfor; ?>
        </tr>
        <tr>
            <td style="width: 100%;" colspan="<?=$agt_tiap_baris + 1?>">
                <b>( PERTANYAAN 431.F UNTUK USIA 15 TAHUN KEATAS )</b>
                <?=str_repeat('&nbsp;', 20)?><b>Kode 431.f</b>
                <?=str_repeat('&nbsp;', 3)?><b>( 00 ) </b> Tidak memiliki
                <?=str_repeat('&nbsp;', 6)?><b>( 01 ) </b> BPJS Jaminan Kecelakaan Kerja
                <?=str_repeat('&nbsp;', 6)?><b>( 02 ) </b> BPJS Jaminan Kematian
                <?=str_repeat('&nbsp;', 6)?><b>( 04 ) </b> BPJS Jaminan Hari Tua
                <br><?=str_repeat('&nbsp;', 140)?><b>( 08 ) </b> BPJS Jaminan Pensiun
                <?=str_repeat('&nbsp;', 6)?><b>( 16 ) </b> Pensiunan/Jaminan hari tua lainnya (Taspen/Program Pensiun Swasta)
                <?=str_repeat('&nbsp;', 6)?><b>( 99 ) </b> Tidak tahu
            </td>
        </tr>
        <tr>
            <td style="width: 25%;" >
                431.f Dalam satu tahun terakhir, Apakah (nama)
                <br><?=str_repeat('&nbsp;', 8)?>memiliki Jaminan Ketenagakerjaan?
                <br><?=str_repeat('&nbsp;', 8)?>(jumlahkan kode)
            </td>
            <?php foreach($dtksAnggota as $agt) : ?>
                <td style="width: 15%;">
                <?=str_repeat('&nbsp;', 15)?>
                    <?php if (strlen($agt->jumlah_jamket_kerja) == 1) : ?>
                        <div class="w-25 h-23 inline kotak tengah">0</div>
                        <div class="w-25 h-23 inline kotak tengah"><?= $agt->jumlah_jamket_kerja ?? '&nbsp;' ?></div>
                    <?php else : ?>
                        <div class="w-25 h-23 inline kotak tengah"><?= ($agt->jumlah_jamket_kerja)[0] ?? '&nbsp;' ?></div>
                        <div class="w-25 h-23 inline kotak tengah"><?= ($agt->jumlah_jamket_kerja)[1] ?? '&nbsp;' ?></div>
                    <?php endif; ?>
                    &nbsp;<?=implode(',', tentukanJumlahTerpilih([99,16,8,4,2,1,0],$agt->jumlah_jamket_kerja))?>
                </td>
            <?php endforeach;?>
            <?php for ($i = $dtksAnggota->count(); $i <=  $agt_tiap_baris - 1; $i++) : ?>
                <td style="width: 15%;">
                    <?=str_repeat('&nbsp;', 15)?>
                    <div class="w-25 h-23 inline kotak tengah"></div>
                    <div class="w-25 h-23 inline kotak tengah"></div>
                </td>
            <?php endfor; ?>
        </tr>
    </table>
</page>
<?php $agt_offset++; endfor;?>