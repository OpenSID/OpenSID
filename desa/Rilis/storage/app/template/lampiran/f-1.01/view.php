<?php if (! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<!-- Cara pengisian bisa dilihat di : https://sipenduduk.pekanbaru.go.id/Formulir-F1-01.pdf -->

<style type="text/css">
    <?php include FCPATH . '/assets/css/dukcapil.css'; ?>
</style>

<page orientation="landscape" format="A3" style="font-size: 8pt">
    <table align="right" style="padding: 5px 20px; border: solid 1px black;">
        <tr><td><strong style="font-size: 16pt;">F-1.01</strong></td></tr>
    </table>
    <p style="text-align: center; margin-top: -10px; margin-bottom: 0px; padding-bottom: 0px;">
        <strong style="font-size: 12pt;">FORMULIR BIODATA KELUARGA</strong>
    </p>
    <table class="disdukcapil" style="margin-top: 0px; border: 0px;">
        <col span="48" style="width: 2.0833%;">

        <tr><td colspan="48">&nbsp;</td></tr>
        <tr>
            <td colspan="48" class="abu kotak left">PERHATIAN: Isilah Formulir ini dengan huruf cetak dan jelas serta mengikuti "TATA CARA PENGISIAN FORMULIR"</td>
        </tr>

        <tr><td colspan="48">&nbsp;</td></tr>
        <tr>
            <td colspan="48">Pilih salah satu:</td>
        </tr>
        <tr>
            <td class="tengah kotak">x</td>
            <td colspan="46">&nbsp;&nbsp;&nbsp;Input Data Kepala Keluarga dan Anggota Keluarga WNI</td>
        </tr>
        <tr>
            <td class="kotak">&nbsp;</td>
            <td colspan="46">&nbsp;&nbsp;&nbsp;Input Data Kepala Keluarga dan Anggota Keluarga Orang Asing</td>
        </tr>
        <tr>
            <td class="kotak">&nbsp;</td>
            <td colspan="46">&nbsp;&nbsp;&nbsp;Input Data Kepala Keluarga dan Anggota Keluarga WNI di luar Negeri</td>
        </tr>
        <tr><td colspan="48">&nbsp;</td></tr>

        <tr>
            <td colspan="48"><strong>DATA KEPALA KELUARGA</strong></td>
        </tr>
        <tr>
            <td>1.</td>
            <td colspan="16">Nama Kepala Keluarga/<i>Name of Head of Family</i></td>
            <td class="kanan"> : </td>
            <td colspan="28" class="kotak"><?= $kepala_keluarga['nama'] ?></td>
            <td colspan="2"></td>
        </tr>
        <tr>
            <td>2.</td>
            <td colspan="16">Alamat/<i>Addres</i></td>
            <td class="kanan"> : </td>
            <td colspan="28" class="kotak"><?= $kepala_keluarga['alamat_wilayah'] ?></td>
            <td colspan="2"></td>
        </tr>
        <tr>
            <td></td>
            <td colspan="16"></td>
            <td class="kanan"></td>
            <td colspan="28" class="kotak">&nbsp;</td>
            <td colspan="2"></td>
        </tr>
        <tr>
            <td>3.</td>
            <td colspan="16">Kode Pos/<i>Post Code</i></td>
            <td class="kanan"> : </td>
            <?php for ($i = 0; $i < 5; $i++): ?>
                <td class="kotak padat tengah">
                    <?= str_split($config['kode_pos'])[$i] ?: '&nbsp;' ?>
                </td>
            <?php endfor; ?>
            <td colspan="2"></td>
            <td colspan="3" class="kanan">4. RT</td>
            <?php for ($i = 0; $i < 3; $i++): ?>
                <td class="kotak padat tengah">
                    <?php if (isset($kepala_keluarga['wilayah']['rt'][$i])): ?>
                        <?= $kepala_keluarga['wilayah']['rt'][$i]; ?>
                    <?php else: ?>
                        &nbsp;
                    <?php endif; ?>
                </td>
            <?php endfor; ?>
            <td colspan="4" class="kanan">5. RW</td>
            <?php for ($i = 0; $i < 3; $i++) : ?>
                <td class="kotak satu">
                    <?php if (isset($kepala_keluarga['wilayah']['rw'][$i])): ?>
                        <?= $kepala_keluarga['wilayah']['rw'][$i]; ?>
                    <?php else: ?>
                        &nbsp;
                    <?php endif; ?>
                </td>
            <?php endfor; ?>
            <td colspan="5" class="kanan">6. Jumlah Anggota Keluarga</td>
            <?php for ($i = 0; $i < 2; $i++): ?>
                <td class="kotak padat tengah">
                    <?php if (isset($individu['jumlah_anggota'][$i])): ?>
                        <?= $individu['jumlah_anggota'][$i]; ?>
                    <?php else: ?>
                        &nbsp;
                    <?php endif; ?>
                </td>
            <?php endfor; ?>
            <td colspan="2">Orang</td>
            <td colspan="2"></td>
        </tr>
        <tr>
            <td>7.</td>
            <td colspan="16">Telepon/<i>Telephone Number/Handphone</i></td>
            <td class="kanan"> : </td>
            <?php for ($i = 0; $i < 16; $i++): ?>
                <td class="kotak padat tengah">
                    <?php if (isset($kepala_keluarga['telepon'][$i])): ?>
                        <?= $kepala_keluarga['telepon'][$i] ?>
                    <?php else: ?>
                        &nbsp;
                    <?php endif; ?>
                </td>
            <?php endfor; ?>
            <td colspan="13"></td>
        </tr>
        <tr>
            <td>8.</td>
            <td colspan="16">Email</td>
            <td class="kanan"> : </td>
            <td colspan="17" class="kotak"><?= $kepala_keluarga['email'] ?></td>
            <td colspan="13"></td>
        </tr>

        <tr><td colspan="48">&nbsp;</td></tr>
        <tr>
            <td colspan="48">Kode Wilayah diisi oleh Petugas Kependudukan dan Pencatatan Sipil</td>
        </tr>
        <tr>
            <td colspan="48"><strong>DATA WILAYAH</strong></td>
        </tr>
        <tr>
            <td>9.</td>
            <td colspan="16">Kode-Nama Provinsi/<i>Code-Province</i></td>
            <td class="kanan"> : </td>
            <?php for ($i = 0; $i < 2; $i++): ?>
                <td class="kotak padat tengah">
                    <?php if (isset($config['kode_propinsi'][$i])): ?>
                        <?= $config['kode_propinsi'][$i] ?>
                    <?php else: ?>
                        &nbsp;
                    <?php endif; ?>
                </td>
            <?php endfor; ?>
            <td colspan="3"></td>
            <td colspan="23" class="kotak"><?= $config['nama_propinsi'] ?></td>
            <td colspan="2"></td>
        </tr>
        <tr>
            <td>10.</td>
            <td colspan="16">Kode-Nama Kabupaten/Kota/<i>Code-Regency/Municipality</i></td>
            <td class="kanan"> : </td>
            <?php for ($i = 0; $i < 2; $i++): ?>
                <td class="kotak padat tengah">
                    <?php if (isset($config['kode_kabupaten'][$i])): ?>
                        <?= substr($config['kode_kabupaten'], 2, 4)[$i]; ?>
                    <?php else: ?>
                        &nbsp;
                    <?php endif; ?>
                </td>
            <?php endfor; ?>
            <td colspan="3"></td>
            <td colspan="23" class="kotak"><?= $config['nama_kabupaten'] ?></td>
            <td colspan="2"></td>
        </tr>
        <tr>
            <td>11.</td>
            <td colspan="16">Kode-Nama Kecamatan/<i>Code-Sub-District</i></td>
            <td class="kanan"> : </td>
            <?php for ($i = 0; $i < 2; $i++): ?>
                <td class="kotak padat tengah">
                    <?php if (isset($config['kode_kecamatan'][$i])): ?>
                        <?= substr($config['kode_kecamatan'], 4, 6)[$i]; ?>
                    <?php else: ?>
                        &nbsp;
                    <?php endif; ?>
                </td>
            <?php endfor; ?>
            <td colspan="3"></td>
            <td colspan="23" class="kotak"><?= $config['nama_kecamatan'] ?></td>
            <td colspan="2"></td>
        </tr>
        <tr>
            <td>12.</td>
            <td colspan="16">Kode-Nama Kelurahan/Desa/<i>Code-Village</i></td>
            <td class="kanan"> : </td>
            <?php for ($i = 0; $i < 4; $i++): ?>
                <td class="kotak padat tengah">
                    <?php if (isset($config['kode_desa'][$i])): ?>
                        <?= substr($config['kode_desa'], 6, 10)[$i]; ?>
                    <?php else: ?>
                        &nbsp;
                    <?php endif; ?>
                </td>
            <?php endfor; ?>
            <td></td>
            <td colspan="23" class="kotak"><?= $config['nama_desa'] ?></td>
            <td colspan="2"></td>
        </tr>
        <tr>
            <td>13.</td>
            <td colspan="16">Nama Dusun/Kampung/<i>Sub-Village</i></td>
            <td class="kanan"> : </td>
            <td colspan="28" class="kotak"><?= $individu['dusun'] ?></td>
            <td colspan="2"></td>
        </tr>

        <tr><td colspan="48">&nbsp;</td></tr>
        <tr>
            <td colspan="48"><b>Alamat di Luar Negeri (diisi oleh WNI di luar negeri)</b></td>
        </tr>
        <tr>
            <td>1.</td>
            <td colspan="8">Alamat</td>
            <td class="kanan"> : </td>
            <td colspan="36" class="kotak">&nbsp;</td>
            <td colspan="2"></td>
        </tr>
        <tr>
            <td></td>
            <td colspan="8"></td>
            <td class="kanan"></td>
            <td colspan="36" class="kotak">&nbsp;</td>
            <td colspan="2"></td>
        </tr>
        <tr>
            <td>2.</td>
            <td colspan="8">Kota</td>
            <td class="kanan"> : </td>
            <td colspan="15" class="kotak">&nbsp;</td>
            <td colspan="2">&nbsp;</td>
            <td>3.</td>
            <td colspan="4">Provinsi / Negara Bagian</td>
            <td class="kanan"> : </td>
            <td colspan="13" class="kotak">&nbsp;</td>
            <td colspan="2"></td>
        </tr>
        <tr>
            <td>4.</td>
            <td colspan="8">Negara</td>
            <td class="kanan"> : </td>
            <td colspan="36" class="kotak">&nbsp;</td>
            <td colspan="2"></td>
        </tr>
        <tr>
            <td>5.</td>
            <td colspan="8">Kode Pos</td>
            <td class="kanan"> : </td>
            <td colspan="15" class="kotak">&nbsp;</td>
            <td colspan="2">&nbsp;</td>
            <td>6.</td>
            <td colspan="6">Jumlah Anggota Keluarga</td>
            <td class="kanan"> : </td>
            <?php for ($i = 0; $i < 2; $i++): ?>
                <td class="kotak padat tengah">
                    <?php if (isset($config['jumlah_anggota'][$i])): ?>
                        <?= substr($config['jumlah_anggota'], 4, 6)[$i] ?>
                    <?php else: ?>
                        &nbsp;
                    <?php endif; ?>
                </td>
            <?php endfor; ?>
            <td colspan="10">Orang</td>
            <td colspan="2"></td>
        </tr>
        <tr>
            <td>7.</td>
            <td colspan="8">Telepone / Handphone</td>
            <td class="kanan"> : </td>
            <?php for ($i = 0; $i < 13; $i++): ?>
                <td class="kotak padat tengah">
                    <?php if (isset($config['telephone'][$i])): ?>
                        <?= substr($config['telephone'], 4, 6)[$i] ?>
                    <?php else: ?>
                        &nbsp;
                    <?php endif; ?>
                </td>
            <?php endfor; ?>
            <td colspan="2"></td>
        </tr>

        <tr><td colspan="48">&nbsp;</td></tr>
        <tr>
            <td colspan="48"><i>Diisi oleh Petugas</i></td>
        </tr>
        <tr>
            <td colspan="9">Kode - Nama Negara</td>
            <td class="kanan"> : </td>
            <?php for ($i = 0; $i < 3; $i++): ?>
                <td class="kotak padat tengah">
                    <?php if (isset($config['kode_negara'][$i])): ?>
                        <?= substr($config['kode_negara'], 4, 6)[$i] ?>
                    <?php else: ?>
                        &nbsp;
                    <?php endif; ?>
                </td>
            <?php endfor; ?>
            <td></td>
            <td colspan="32" class="kotak"></td>
            <td colspan="2"></td>
        </tr>
        <tr>
            <td colspan="9">Kode - Nama Perwakilan RI</td>
            <td class="kanan"> : </td>
            <?php for ($i = 0; $i < 3; $i++): ?>
                <td class="kotak padat tengah">
                    <?php if (isset($config['kode_negara'][$i])): ?>
                        <?= substr($config['kode_negara'], 4, 6)[$i] ?>
                    <?php else: ?>
                        &nbsp;
                    <?php endif; ?>
                </td>
            <?php endfor; ?>
            <td></td>
            <td colspan="32" class="kotak"></td>
            <td colspan="2"></td>
        </tr>

        <tr><td colspan="48">&nbsp;</td></tr>
        <tr>
            <td colspan="48"><strong>DATA ANGGOTA KELUARGA</strong></td>
        </tr>
        <tr>
            <td colspan="48">Catatan :</td>
        </tr>
        <tr>
            <td colspan="48">&nbsp;&nbsp;&nbsp;- Bagi Penduduk WNI mengisi Kolom 2 s.d 6, 10 s.d 31, 38 s.d 41</td>
        </tr>
        <tr>
            <td colspan="48">&nbsp;&nbsp;&nbsp;- <i>For Foreigners only, please fill column 2 to 13, 15 to 41</i></td>
        </tr>
        <tr>
            <td colspan="48">&nbsp;&nbsp;&nbsp;- Bagi WNI di luar wilayah NKRI mengisi nomor 2 s.d 31, 38 s.d 41</td>
        </tr>
    </table>

    <!-- 1 sampai 7-->
    <?php $kolom = 7 ?>
    <table style="border-collapse: collapse;" class="disdukcapil">
        <col style="width: 2%;">
        <col style="width: 30%;">
        <col style="width: 10%;">
        <col style="width: 10%;">
        <col style="width: 10%;">
        <col style="width: 10%;">
        <col style="width: 28%;">

        <tr>
            <td class="judul tengah" rowspan="2">No.</td>
            <td class="judul tengah" rowspan="2">Nama Lengkap <br><i>Full Name</i></td>
            <td class="judul tengah" colspan="2">Gelar</td>
            <td class="judul tengah" rowspan="2">Nomor Paspor <br><i>Passport Number</i></td>
            <td class="judul tengah" rowspan="2">Tanggal Berakhir Paspor <br><i>Date of Expiry</i></td>
            <td class="judul tengah" rowspan="2">Nama Sponsor <br><i>Sponsor Name</i></td>
        </tr>
        <tr>
            <td class="judul tengah">Depan</td>
            <td class="judul tengah">Belakang</td>
        </tr>
        <tr>
            <?php for ($i = 0; $i < $kolom; $i++): ?>
                <td class="judul abu tengah"><?= $i + 1 ?></td>
            <?php endfor; ?>
        </tr>
        <?php for ($i = 0; $i < MAX_ANGGOTA_F101; $i++): ?>
            <tr>
                <?php $class = ($i == 10 - 1) ? 'bawah' : 'anggota'; ?>
                <td class="tengah <?= $class?>"><?= $i + 1; ?></td>
                <?php if ($i < count($anggota)): ?>
                    <td class="tengah <?= $class?>"><?= $anggota[$i]['nama'] ?></td>
                    <td class="tengah <?= $class?>">-</td>
                    <td class="tengah <?= $class?>">-</td>
                    <td class="tengah <?= $class?>"><?= $anggota[$i]['dokumen_pasport'] ?: '-' ?></td>
                    <td class="tengah <?= $class?>"><?= tgl_indo_out($anggota[$i]['tanggal_akhir_paspor']) ?: '-' ?></td>
                    <td class="tengah <?= $class?>">-</td>
                <?php else: ?>
                    <?php for ($k = 0; $k < $kolom - 1; $k++): ?>
                        <td class="tengah <?= $class ?>">&nbsp;</td>
                    <?php endfor; ?>
                <?php endif; ?>
            </tr>
        <?php endfor; ?>
    </table>
</page>

<page orientation="landscape" format="A3" style="font-size: 8pt">

    <!-- 8 sampai 15-->
    <br /><br />
    <?php $kolom = 9; $mulai = 8; ?>
    <table style="border-collapse: collapse;" class="disdukcapil">
        <col style="width: 2%;">
        <col style="width: 8%;">
        <col style="width: 18%;">
        <col style="width: 8%;">
        <col style="width: 18%;">
        <col style="width: 18%;">
        <col style="width: 12%;">
        <col style="width: 8%;">
        <col style="width: 8%;">

        <tr>
            <td class="judul tengah">No.</td>
            <td class="judul tengah">Tipe Sponsor <br /><i>Type of Sponsor</i></td>
            <td class="judul tengah">Alamat Sponsor <br /><i>Sponsor Address</i></td>
            <td class="judul tengah">Jenis Kelamin <br /><i>Sex</i></td>
            <td class="judul tengah">Tempat Lahir <br /><i>Place of Birth</i></td>
            <td class="judul tengah">Tanggal, Bulan, Tahun Lahir <br /><i>Date of Birth</i></td>
            <td class="judul tengah">Kewarganegaraan <br /><i>Nationaly</i></td>
            <td class="judul tengah">No. SK <br />Penetapan WNI</td>
            <td class="judul tengah">Akta Lahir</td>
        </tr>
        <tr>
            <td class="judul abu tengah">&nbsp;</td>
            <?php for ($i = $mulai; $i < $kolom + ($mulai - 1); $i++): ?>
                <td class="judul abu tengah"><?= $i ?></td>
            <?php endfor; ?>
        </tr>
        <?php for ($i = 0; $i < MAX_ANGGOTA_F101; $i++): ?>
            <tr>
                <?php $class = ($i == 10 - 1) ? 'bawah' : 'anggota'; ?>
                <td class="tengah <?= $class ?>"><?= $i + 1; ?></td>
                <?php if ($i < count($anggota)): ?>
                    <td class="tengah <?= $class ?>">-</td>
                    <td class="tengah <?= $class ?>">-</td>
                    <td class="tengah <?= $class ?>"><?= $anggota[$i]['sex'] ?></td>
                    <td class="tengah <?= $class ?>"><?= $anggota[$i]['tempatlahir'] ?></td>
                    <td class="tengah <?= $class ?>"><?= tgl_indo_out($anggota[$i]['tanggallahir']) ?: '' ?></td>
                    <td class="tengah <?= $class ?>"><?= $anggota[$i]['warganegara_id'] ?></td>
                    <td class="tengah <?= $class ?>">-</td>
                    <td class="tengah <?= $class ?>"><?= $anggota[$i]['akta_lahir'] ? '2' : '1' ?></td>
                <?php else: ?>
                    <?php for ($k = 1; $k < $kolom; $k++): ?>
                        <td class="tengah <?= $class ?>">&nbsp;</td>
                    <?php endfor; ?>
                <?php endif; ?>
            </tr>
        <?php endfor; ?>
    </table>

    <!-- 16 sampai 23-->
    <br /><br />
    <?php $kolom = 9; $mulai = 16; ?>
    <table style="border-collapse: collapse;" class="disdukcapil">
        <col style="width: 2%;">
        <col style="width: 10%;">
        <col style="width: 10%;">
        <col style="width: 8%;">
        <col style="width: 26%;">
        <col style="width: 8%;">
        <col style="width: 8%;">
        <col style="width: 14%;">
        <col style="width: 14%;">

        <tr>
            <td class="judul tengah">No.</td>
            <td class="judul tengah">Nomor Akta Kelahiran</td>
            <td class="judul tengah">Gol. Darah <br /><i>Type of Blood</i></td>
            <td class="judul tengah">Agama <br /><i>Religion</i></td>
            <td class="judul tengah">Nama Organisasi Kepercayaan terhadap Tuhan YME</td>
            <td class="judul tengah">Status Perkawinan <br /><i>Matial Status</i></td>
            <td class="judul tengah">Akta Perkawinan</td>
            <td class="judul tengah">Nomor Akta Perkawinan</td>
            <td class="judul tengah">Tanggal Perkawinan</td>
        </tr>
        <tr>
            <td class="judul abu tengah">&nbsp;</td>
            <?php for ($i = $mulai; $i < $kolom + ($mulai - 1); $i++): ?>
                <td class="judul abu tengah"><?= $i ?></td>
            <?php endfor; ?>
        </tr>
        <?php for ($i = 0; $i < MAX_ANGGOTA_F101; $i++): ?>
            <tr>
                <?php $class = ($i == 10 - 1) ? 'bawah' : 'anggota'; ?>
                <td class="tengah <?= $class ?>"><?= $i + 1; ?></td>
                <?php if ($i < count($anggota)): ?>
                    <td class="tengah <?= $class ?>"><?= $anggota[$i]['akta_lahir'] ?: '-' ?></td>
                    <td class="tengah <?= $class ?>"><?= $anggota[$i]['golongan_darah_id'] ?></td>
                    <td class="tengah <?= $class ?>"><?= $anggota[$i]['agama_id'] ?></td>
                    <td class="tengah <?= $class ?>">-</td>
                    <td class="tengah <?= $class ?>"><?php switch (ucwords(strtolower($anggota[$i]['status_perkawinan']))) {
                        case 'Belum Kawin':
                            $status = 1;
                            break;

                        case 'Kawin Tercatat':
                            $status = 2;
                            break;

                        case 'Kawin Belum Tercatat':
                            $status = 3;
                            break;

                        case 'AB-Cerai Hidup Tercatat' || 'Cerai Hidup':
                            $status = 4;
                            break;

                        case 'Cerai Hidup Belum Tercatat':
                            $status = 5;
                            break;

                        case 'Cerai Mati':
                            $status = 6;
                            break;

                        default:
                            $status = '-';
                            break;
                    } ?><?= $status ?></td>
                    <td class="tengah <?= $class ?>"><?= $anggota[$i]['akta_perkawinan'] ? '2' : '1' ?></td>
                    <td class="tengah <?= $class ?>"><?= $anggota[$i]['akta_perkawinan'] ?: '-' ?></td>
                    <td class="tengah <?= $class ?>"><?= tgl_indo_out($anggota[$i]['tanggalperkawinan']) ?: '' ?></td>
                <?php else: ?>
                    <?php for ($k = 1; $k < $kolom; $k++): ?>
                        <td class="tengah <?= $class ?>">&nbsp;</td>
                    <?php endfor; ?>
                <?php endif; ?>
            </tr>
        <?php endfor; ?>
    </table>

    <!-- 24 sampai 33-->
    <br /><br />
    <?php $kolom = 11; $mulai = 24; ?>
    <table style="border-collapse: collapse;" class="disdukcapil">
        <col style="width: 2%;">
        <col style="width: 8%;">
        <col style="width: 8%;">
        <col style="width: 12%;">
        <col style="width: 8%;">
        <col style="width: 8%;">
        <col style="width: 12%;">
        <col style="width: 12%;">
        <col style="width: 12%;">
        <col style="width: 8%;">
        <col style="width: 10%;">

        <tr>
            <td class="judul tengah">No.</td>
            <td class="judul tengah">Akta Cerai</td>
            <td class="judul tengah">Nomor Akta Perceraian</td>
            <td class="judul tengah">Tanggal Perceraian</td>
            <td class="judul tengah">Status Hubungan <br /> Dalam Keluarga</td>
            <td class="judul tengah">Kelainan Fisik & Mental</td>
            <td class="judul tengah">Penyandang Cacat</td>
            <td class="judul tengah">Pendidikan Terakhir</td>
            <td class="judul tengah">Jenis Pekerjaan</td>
            <td class="judul tengah">Nomor ITAS/ ITAP</td>
            <td class="judul tengah">Tempat Terbit ITAS/ ITAP</td>
        </tr>
        <tr>
            <td class="judul abu tengah"></td>
            <?php for ($i = $mulai; $i < $kolom + 23; $i++): ?>
                <td class="judul abu tengah"><?= $i ?></td>
            <?php endfor; ?>
        </tr>
        <?php for ($i = 0; $i < MAX_ANGGOTA_F101; $i++): ?>
            <tr>
                <?php $class = ($i == 10 - 1) ? 'bawah' : 'anggota'; ?>
                <td class="tengah <?= $class ?>"><?= $i + 1; ?></td>
                <?php if ($i < count($anggota)): ?>
                    <td class="tengah <?= $class ?>"><?= $anggota[$i]['akta_perceraian'] ? '2' : '1' ?></td>
                    <td class="tengah <?= $class ?>"><?= $anggota[$i]['akta_perceraian'] ?></td>
                    <td class="tengah <?= $class ?>"><?= tgl_indo_out($anggota[$i]['tanggalperceraian']) ?: '' ?></td>
                    <td class="tengah <?= $class ?>"><?= $anggota[$i]['hubungan_id'] ?></td>
                    <td class="tengah <?= $class ?>"><?= $anggota[$i]['cacat'] ? '2' : '1' ?></td>
                    <td class="tengah <?= $class ?>"><?= $anggota[$i]['cacat_id'] ?: '-' ?></td>
                    <td class="tengah <?= $class ?>"><?= $anggota[$i]['pendidikan_kk_id'] ?></td>
                    <td class="tengah <?= $class ?>"><?= $anggota[$i]['pekerjaan_id'] ?></td>
                    <td class="tengah <?= $class ?>">-</td>
                    <td class="tengah <?= $class ?>">-</td>
                <?php else: ?>
                    <?php for ($k = 1; $k < $kolom; $k++): ?>
                        <td class="tengah <?= $class ?>">&nbsp;</td>
                    <?php endfor; ?>
                <?php endif; ?>
            </tr>
        <?php endfor; ?>
    </table>

    <!-- 34 sampai 41-->
    <br /><br />
    <?php $kolom = 9; $mulai = 34; ?>
    <table style="border-collapse: collapse;" class="disdukcapil">
        <col style="width: 2%;">
        <col style="width: 10%;">
        <col style="width: 18%;">
        <col style="width: 8%;">
        <col style="width: 18%;">
        <col style="width: 18%;">
        <col style="width: 10%;">
        <col style="width: 8%;">
        <col style="width: 8%;">

        <tr>
            <td class="judul tengah">No.</td>
            <td class="judul tengah">Tanggal Terbit ITAS/ ITAP</td>
            <td class="judul tengah">Tanggal Akhir ITAS/ ITAP</td>
            <td class="judul tengah">Tempat Datang <br /> Pertama</td>
            <td class="judul tengah">Tanggal Kedatangan Pertama</td>
            <td class="judul tengah">NIK Ibu</td>
            <td class="judul tengah">Nama Ibu</td>
            <td class="judul tengah">NIK Ayah</td>
            <td class="judul tengah">Nama Ayah</td>
        </tr>
        <tr>
            <td class="judul abu tengah"></td>
            <?php for ($i = $mulai; $i < $kolom + ($mulai - 1); $i++): ?>
                <td class="judul abu tengah"><?= $i ?></td>
            <?php endfor; ?>
        </tr>
        <?php for ($i = 0; $i < MAX_ANGGOTA_F101; $i++): ?>
            <tr>
                <?php $class = ($i == 10 - 1) ? 'bawah' : 'anggota'; ?>
                <td class="tengah <?= $class ?>"><?= $i + 1; ?></td>
                <?php if ($i < count($anggota)): ?>
                    <td class="tengah <?= $class ?>">-</td>
                    <td class="tengah <?= $class ?>">-</td>
                    <td class="tengah <?= $class ?>">-</td>
                    <td class="tengah <?= $class ?>">-</td>
                    <td class="tengah <?= $class ?>"><?= $anggota[$i]['ibu_nik'] ?: '-' ?></td>
                    <td class="tengah <?= $class ?>"><?= $anggota[$i]['nama_ibu'] ?></td>
                    <td class="tengah <?= $class ?>"><?= $anggota[$i]['ayah_nik'] ?: '-' ?></td>
                    <td class="tengah <?= $class ?>"><?= $anggota[$i]['nama_ayah'] ?></td>
                <?php else: ?>
                    <?php for ($k = 1; $k < $kolom; $k++): ?>
                        <td class="tengah <?= $class ?>">&nbsp;</td>
                    <?php endfor; ?>
                <?php endif; ?>
            </tr>
        <?php endfor; ?>
    </table>

    <table class="ttd" style="margin-top: 15px">
        <col style="width:2%">
        <col style="width:20%">
        <col style="width:48%">
        <col style="width:20%">
        <col style="width:10%">

        <tr>
            <td>&nbsp;</td>
            <td class="center">Mengetahui,</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td class="center">Kepala Dinas Kependudukan dan Pencatatan Sipil</td>
            <td>&nbsp;</td>
            <td class="center">Kepala Keluarga/<i>Head of Family</i></td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td class="center">Kabupaten <?= $config['nama_kabupaten'] ?></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
        <tr><td colspan="7" style="height: 20px;">&nbsp;</td></tr>
        <tr>
            <td>&nbsp;</td>
            <td class="center"><div><?= str_pad('', 390, '&nbsp;')?></div></td>
            <td>&nbsp;</td>
            <td class="center"><?= $kepala_keluarga['nama'] ?></td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td class="center"><br/><?= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;NIP.' . str_pad('', 390, '&nbsp;') ?></td>
            <td>&nbsp;</td>
            <td class="center">&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
    </table>

    <br />
    <p style="margin-top: 0px;">
        PERNYATAAN<br>
        Demikian Formulir ini saya/kami isi dengan sesungguhnya. Apabila keterangan tersebut tidak sesuai dengan sebenarnya, <br />
        saya bersedia dikenakan sanksi sesuai dengan ketentuan peraturan perundang-undangan yang berlaku.
    </p>
</page>
