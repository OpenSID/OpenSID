<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KESEPAKATAN MENJADI DESA DIGITAL {{ strtoupper(config_item('nama_lembaga')) }}</title>
</head>
<style>
    @media print {
        @page {
            size: 21.59cm 33.00cm;
            margin: 3cm;
        }
    }
</style>

<body onload="window.print()">
    <table width="100%">
        <tr>
            <td width="25" align="center" valign="top">
                <img src="{{ gambar_desa($logo) }}" width="70%">
            </td>
            <td width="50" align="center">
                <p align="center">
                    <strong>KESEPAKATAN KERJASAMA</strong>
                </p>
                <p align="center">
                    <strong>ANTARA</strong>
                </p>
                <p align="center">
                    <strong>PEMERINTAH DESA {{ strtoupper($desa) }}</strong>
                </p>
                <p align="center">
                    <strong>DENGAN</strong>
                </p>
                <p align="center">
                    <strong>PERKUMPULAN DESA DIGITAL TERBUKA</strong>
                </p>
                <p align="justify">
                    <strong> </strong>
                </p>
                <p align="center">
                    <strong>TENTANG</strong>
                </p>
                <p align="center">
                    <strong>
                        PEMANFAATAN APLIKASI DAN LAYANAN {{ strtoupper(config_item('nama_lembaga')) }} MENUJU DESA CERDAS<em></em>
                    </strong>
                </p>
                <p align="center">
                    <strong><em></em></strong>
                </p>
                <p align="center">
                    <strong>NOMOR : {{ "{$random}/{$hari}/{$bulan}/{$tahun}" }}</strong><br>
                    <strong>NOMOR : {{ "14. {$random}/DDT/{$bulan}/{$tahun}" }}</strong>
                </p>
            </td>
            <td width="25" align="center" valign="top">
                <img src="{{ $layanan_logo }}" width="70%">
            </td>
        </tr>
    </table>
    <p align="justify">
        Pada hari ini, <strong>{{ $nama_hari }} </strong>Tanggal <strong>{{ $hari }}</strong> Bulan <strong>{{ $nama_bulan }}</strong> Tahun <strong>{{ $nama_tahun }}</strong>
        bertempat di {{ $alamat }}, yang bertandatangan di bawah ini :
    </p>
    <table border="0" cellspacing="0" cellpadding="0" width="100%">
        <tbody>
            <tr>
                <td width="31" valign="top"></td>
                <td width="282" valign="top">
                    <p align="justify">
                        <strong>{{ $kepala_desa }}</strong>
                    </p>
                </td>
                <td width="18" valign="top">
                    <p align="justify">
                        :
                    </p>
                </td>
                <td width="300" valign="top">
                    <p align="justify">
                        Kepala Desa/Lurah <strong>{{ $pamong }}</strong> yang berkedudukan dan
                        berkantor di <strong>{{ $alamat }}</strong>, dalam hal ini bertindak
                        dalam jabatannya selanjutnya disebut <strong>PIHAK KESATU</strong>.
                    </p>
                </td>
            </tr>
            <tr>
                <td width="631" colspan="4" valign="top">
                </td>
            </tr>
            <tr>
                <td width="31" valign="top"></td>
                <td width="282" valign="top">
                    <p align="justify">
                        <strong>LUSIANTO, S.Kom., M.Si</strong>
                    </p>
                </td>
                <td width="18" valign="top">
                    <p align="justify">
                        :
                    </p>
                </td>
                <td width="300" align="top">
                    <p align="justify">
                        Jabatan Ketua Umum Perkumpulan Desa Digital Terbuka, berkedudukan di Sekretariat Nasional Perkumpulan Desa Digital Terbuka, Nagari Tanjung Haro Sikabu-kabu Padang panjang {{ ucwords(setting('sebutan_kecamatan')) }} Luak kabupaten Lima Puluh Kota Provinsi Sumatera Barat,
                        dalam hal ini bertindak untuk atas nama
                        perkumpulan desa digital terbuka, selanjutnya disebut <strong>PIHAK KEDUA.</strong>
                    </p>
                </td>
            </tr>
            <tr>
                <td width="631" colspan="4" valign="top">
                </td>
            </tr>
        </tbody>
    </table>
    <p align="justify">
        <strong>PIHAK KESATU </strong>
        dan <strong>PIHAK KEDUA </strong>yang selanjutnya disebut sebagai <strong>PARA PIHAK </strong>sepakat mengadakan
        Kerjasama yang saling
        menguntungkan dalam rangka pemenuhan tugas dan fungsi untuk kemajuan Desa
        <strong>{{ $desa }}</strong> mewujudkan Desa Digital melalui pemanfaatan aplikasi dan layanan yang
        disediakan PIHAK KEDUA, dengan ketentuan sebagai berikut:
    </p>
    <p style="page-break-after: always;"></p>
    <p align="center">
        <strong>PASAL 1</strong><br />
        <strong>DASAR HUKUM</strong>
    </p>
    <ol>
        <li>
            <p align="justify">
                Undang-Undang Nomor 11 Tahun 2008 tentang Informasi dan Transaksi
                Elektronik.
            </p>
        </li>
        <li>
            <p align="justify">
                Undang Undang Nomor 14 Tahun 2008 tentang Keterbukaan Informasi Publik.
            </p>
        </li>
        <li>
            <p align="justify">
                Undang-undang Nomor 25 Tahun 2009 tentang Pelayanan Publik.
            </p>
        </li>
        <li>
            <p align="justify">
                Undang-Undang Nomor 6 Tahun 2014 tentang Desa.
            </p>
        </li>
        <li>
            <p align="justify">
                Peraturan Pemerintah Nomor 43 Tahun 2014 tentang Peraturan Pelaksanaan
                Undang-Undang Nomor 6 tahun 2014 tentang Desa (Lembaran Negara Republik
                Indonesia Tahun 2014 Nomor 123, Tambahan Lembaran Negara Republik Indonesia
                Nomor 5539) sebagaimana telah diubah dengan Peraturan Pemerintah Nomor 47
                Tahun 2015 tentang Perubahan Peraturan Pemerintah Nomor 43 Tahun 2014
                tentang Peraturan Pelaksanaan Undang Undang Nomor 6 tahun 2014 tentang
                Desa.
            </p>
        </li>
        <li>
            <p align="justify">
                Peraturan Menteri Dalam Negeri Republik Indonesia Nomor 96 Tahun 2017
                tentang Tata Cara Kerja Sama Desa di Bidang Pemerintahan.
            </p>
        </li>
        <li>
            <p align="justify">
                Peraturan Menteri Dalam Negeri Republik Indonesia Nomor 20 Tahun 2018
                Tentang Pengelolaan Keuangan Desa.
            </p>
        </li>
        <li>
            <p align="justify">
                Peraturan Lembaga Kebijakan Pengadaan Barang/Jasa Pemerintah Nomor 12
                Tahun 2019 tentang pedoman Penyusunan Tata Cara Pengadaan Barang/Jasa di
                Desa.
            </p>
        </li>
        <li>
            <p align="justify">
                Peraturan Menteri Desa, Pembangunan Daerah Tertinggi dan Transmigrasi
                Republik Indonesia Nomor 21 Tahun 2020 tentang Pedoman Umum Pembangunan
                Desa dan Pemberdayaan Masyarakat Desa.
            </p>
        </li>
    </ol>
    <br />
    <p align="center">
        <strong>PASAL 2</strong><br />
        <strong>MAKSUD DAN TUJUAN</strong>
    </p>
    <p align="justify">
        <strong>PIHAK KESATU </strong>
        bertujuan mewujudkan Desa <strong>{{ $desa }}</strong> menjadi Desa Digital menuju
        Desa Cerdas. Untuk tujuan itu, <strong>PIHAK KESATU</strong> bermaksud
        menggunakan aplikasi dan layanan yang disediakan {{ config_item('nama_lembaga') }}.
    </p>
    <p align="justify">
        Maksud dan tujuan Kesepakatan Kerjasama ini adalah untuk saling mendukung dan
        bersinergi dalam rangka mewujudkan pemerintahan desa yang transparan dan
        efisien dengan menggunakan teknologi informasi dan komunikasi (ICT) untuk
        meningkatkan kapasitas desa dalam rangka mewujudkan Desa Digital menuju
        Desa Cerdas.
    </p>
    <br />
    <p align="center">
        <strong>PASAL 3</strong><br />
        <strong>RUANG LINGKUP</strong>
    </p>
    <p align="justify">
        Ruang lingkup Kesepakatan Kerjasama ini adalah pemanfaatan aplikasi dan
        layanan yang disediakan {{ config_item('nama_lembaga') }}.
    </p>
    <p style="page-break-after: always;"></p>
    <p align="center">
        <strong>PASAL 4</strong><br />
        <strong>PELAKSANAAN</strong>
    </p>
    <ol>
        <li>
            <p align="justify">
                Pelaksanaan Kesepakatan Kerjasama ini adalah mewujudkan Desa Digital di Desa <strong>{{ $desa }}
                </strong> dengan
                memanfaatkan aplikasi dan
                layanan yang disediakan <strong>PIHAK KEDUA</strong>.<em> </em>
            </p>
        </li>
        <li>
            <p align="justify">
                Dengan berlakunya Kesepakatan Kerjasama ini, <strong>PIHAK KEDUA</strong>
                akan menyediakan layanan {{ config_item('nama_lembaga') }} untuk dapat dimanfaatkan oleh <strong>PIHAK KESATU</strong>, di mana
                layanan
                tersebut hanya tersedia bagi
                desa yang telah bekerjasama sehingga terdaftar sebagai Desa Digital
                {{ config_item('nama_lembaga') }}.
            </p>
        </li>
        <li>
            <p align="justify">
                Untuk mewujudkan Desa Digital, <strong>PIHAK KESATU</strong> dapat
                memanfaatkan dan memesan aplikasi dan layanan <strong>PIHAK KEDUA</strong>
                sesuai ketentuan pemesanan dan penggunaan masing-masing aplikasi dan
                layanan.
            </p>
        </li>
        <li>
            <p align="justify">
                Terhadap pelaksanaan Kesepakatan Kerjasama ini sebagaimana dimaksud pada
                ayat (1) akan dilakukan pemantauan dan evaluasi secara berkala oleh <strong>PARA PIHAK</strong> sebagai
                laporan
                dalam rangka mendukung
                perencanaan program kerja sama selanjutnya.
            </p>
        </li>
    </ol>
    <br />
    <p align="center">
        <strong>PASAL 5</strong><br />
        <strong>JANGKA WAKTU</strong>
    </p>
    <ol>
        <li>
            <p align="justify">
                Kesepakatan Kerjasama berlaku terhitung sejak ditandatanganinya
                Kesepakatan Kerjasama ini tanpa batas waktu.
            </p>
        </li>
        <li>
            <p align="justify">
                Kesepakatan Kerjasama ini dapat diakhiri oleh <strong>PARA PIHAK</strong>
                dengan pemberitahuan tertulis dari satu pihak kepada pihak yang lain.
            </p>
        </li>
    </ol>
    <br />
    <p align="center">
        <strong>PASAL 6</strong><br />
        <strong>PEMBIAYAAN</strong>
    </p>
    <p align="justify">
        Desa <strong>{{ $desa }}</strong> akan berkontribusi dalam pembiayaan gotong-royong nasional
        pengembangan dan penerapan aplikasi kelolaan {{ config_item('nama_lembaga') }} sesuai dengan
        aplikasi dan layanan yang dimanfaatkan. Pembiayaan tersebut diturunkan
        dalam perjanjian atau pemesanan terpisah yang disepakati <strong>PARA PIHAK</strong> untuk aplikasi dan layanan
        yang
        digunakan.
    </p>
    <br />
    <p align="center">
        <strong>PASAL 7</strong><br />
        <strong>PENYELESAIAN PERSELISIHAN</strong>
    </p>
    <ol>
        <li>
            <p align="justify">
                Setiap perselisihan, pertentangan dan perbedaan pendapat yang timbul
                sehubungan dengan Perjanjian ini akan diselesaikan terlebih dahulu secara
                musyawarah dan mufakat oleh <strong>PARA PIHAK</strong>.
            </p>
        </li>
        <li>
            <p align="justify">
                Apabila penyelesaian secara musyawarah tidak berhasil mencapai mufakat,
                maka <strong>PARA PIHAK</strong> sepakat untuk menyerahkan penyelesaian
                perselisihan tersebut melalui Pengadilan.
            </p>
        </li>
        <li>
            <p align="justify">
                <a name="_gjdgxs"></a>
                Mengenai Perjanjian ini dan segala akibatnya, <strong>PARA PIHAK</strong> memilih kediaman hukum atau
                domisili
                yang tetap
                dan umum di Kantor Pengadilan Negeri Kabupaten Lima Puluh Kota.
            </p>
        </li>
    </ol>
    <p style="page-break-after: always;"></p>
    <p align="center">
        <strong>PASAL 8</strong><br />
        <strong>LAIN-LAIN</strong>
    </p>
    <ol>
        <li>
            <p align="justify">
                Pelaksanaan Kesepakatan Kerjasama ini tidak terpengaruh dengan terjadinya
                pergantian kepemimpinan dari <strong>PARA PIHAK</strong>.
            </p>
        </li>
        <li>
            <p align="justify">
                Dalam hal terjadi perubahan atau terdapat ketentuan yang belum diatur
                dalam Kesepakatan Kerjasama ini dituangkan dalam bentuk <em>addendum</em>
                atas persetujuan <strong>PARA PIHAK</strong> yang merupakan bagian tidak
                terpisahkan dari Kesepakatan Kerjasama ini.
            </p>
        </li>
    </ol>
    <br />
    <p align="center">
        <strong>PASAL 9</strong><br />
        <strong>PENUTUP</strong>
    </p>
    <ol>
        <li>
            <p align="justify">
                Kesepakatan Kerjasama ini dibuat dalam rangkap 1 (satu) bermeterai cukup
                dan ditandatangani basah oleh <strong>PIHAK KESATU </strong>dan tanda
                tangan digital oleh <strong>PIHAK KEDUA</strong>.
            </p>
        </li>
        <li>
            <p align="justify">
                Kesepakatan Kerjasama ini disampaikan untuk disepakati <strong>PIHAK KEDUA</strong> dengan diunggahnya
                hasil
                scan
                Perjanjian ini
                yang telah ditandatangani <strong>PIHAK KESATU</strong> sesuai ayat (1)
                melalui fitur pendaftaran Desa Digital {{ config_item('nama_lembaga') }} yang disediakan di aplikasi
                {{ config_item('nama_aplikasi') }}. <strong>PIHAK KEDUA</strong> secara resmi menyatakan persetujuan
                dengan Kesepakatan Kerjasama ini dengan mengubah status pendaftaran menjadi
                TERDAFTAR.
            </p>
        </li>
        <li>
            <p align="justify">
                Setelah Kesepakatan Kerjasama ini dieksekusi, Desa <strong>{{ $desa }}</strong> akan resmi
                terdaftar sebagai Desa Digital {{ config_item('nama_lembaga') }}, dan berhak mengakses aplikasi,
                layanan dan kegiatan yang hanya tersedia bagi desa yang terdaftar sebagai
                Desa Digital {{ config_item('nama_lembaga') }}.
            </p>
        </li>
        <li>
            <p align="justify">
                Kesepakatan Kerjasama ini dibuat dengan semangat kerja sama yang baik,
                untuk dipatuhi dan dilaksanakan oleh <strong>PARA PIHAK</strong>.
            </p>
        </li>
    </ol>
    <br />
    <table border="0" cellspacing="0" cellpadding="0" width="631">
        <tbody>
            <tr>
                <td width="333" valign="top">
                    <p align="center">
                        <strong></strong>
                    </p>
                </td>
                <td width="298" align="center">
                    Desa {{ $desa }}, {{ tgl_indo(date('Y m d')) }}
                </td>
            </tr>
            <tr>
                <td width="333" valign="top">
                    <p align="center">
                        <strong>PIHAK KESATU,</strong>
                    </p>
                </td>
                <td width="298" valign="top">
                    <p align="center">
                        <strong>PIHAK KEDUA,</strong>
                    </p>
                </td>
            </tr>
            <tr>
                <td></td>
                <td><img src="{{ $stempel }}" width="255"></td>
            </tr>
            <tr>
                <td>
                    <p align="center">
                        <strong>{{ $kepala_desa }}</strong>
                    </p>
                </td>
                <td>
                    <p align="center">
                        <strong>LUSIANTO, S.Kom., M.Si</strong>
                    </p>
                </td>
            </tr>
        </tbody>
    </table>
</body>

</html>
