<?php

/*
 *
 * File ini bagian dari:
 *
 * OpenSID
 *
 * Sistem informasi desa sumber terbuka untuk memajukan desa
 *
 * Aplikasi dan source code ini dirilis berdasarkan lisensi GPL V3
 *
 * Hak Cipta 2009 - 2015 Combine Resource Institution (http://lumbungkomunitas.net/)
 * Hak Cipta 2016 - 2023 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 *
 * Dengan ini diberikan izin, secara gratis, kepada siapa pun yang mendapatkan salinan
 * dari perangkat lunak ini dan file dokumentasi terkait ("Aplikasi Ini"), untuk diperlakukan
 * tanpa batasan, termasuk hak untuk menggunakan, menyalin, mengubah dan/atau mendistribusikan,
 * asal tunduk pada syarat berikut:
 *
 * Pemberitahuan hak cipta di atas dan pemberitahuan izin ini harus disertakan dalam
 * setiap salinan atau bagian penting Aplikasi Ini. Barang siapa yang menghapus atau menghilangkan
 * pemberitahuan ini melanggar ketentuan lisensi Aplikasi Ini.
 *
 * PERANGKAT LUNAK INI DISEDIAKAN "SEBAGAIMANA ADANYA", TANPA JAMINAN APA PUN, BAIK TERSURAT MAUPUN
 * TERSIRAT. PENULIS ATAU PEMEGANG HAK CIPTA SAMA SEKALI TIDAK BERTANGGUNG JAWAB ATAS KLAIM, KERUSAKAN ATAU
 * KEWAJIBAN APAPUN ATAS PENGGUNAAN ATAU LAINNYA TERKAIT APLIKASI INI.
 *
 * @package   OpenSID
 * @author    Tim Pengembang OpenDesa
 * @copyright Hak Cipta 2009 - 2015 Combine Resource Institution (http://lumbungkomunitas.net/)
 * @copyright Hak Cipta 2016 - 2023 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

use Illuminate\Support\Facades\DB;

defined('BASEPATH') || exit('No direct script access allowed');

class Migrasi_fitur_premium_2303 extends MY_model
{
    public function up()
    {
        $hasil = true;

        // Jalankan migrasi sebelumnya
        $hasil = $hasil && $this->jalankan_migrasi('migrasi_fitur_premium_2302');
        $hasil = $hasil && $this->migrasi_2023020251($hasil);
        $hasil = $hasil && $this->migrasi_2023021471($hasil);
        $hasil = $hasil && $this->migrasi_2023021671($hasil);
        $hasil = $hasil && $this->migrasi_2023022271($hasil);

        return $hasil && true;
    }

    protected function migrasi_2023020251($hasil)
    {
        // Sesuaikan data jabatan agar bisa digunakan di OpenKAB
        // Parameter migrasi ditentukan dari jabatan sekreatis dengan id = 2 jika jenis = 1 akan dilakukan migrasi
        DB::table('ref_jabatan')->where('id', 2)->where('jenis', 1)->update(['jenis' => 2]);

        return $hasil;
    }

    protected function migrasi_2023021471($hasil)
    {
        $fields = [
            'tinggi_badan' => [
                'type'       => 'FLOAT',
                'constraint' => '',
            ],
        ];

        return $hasil && $this->dbforge->modify_column('bulanan_anak', $fields);
    }

    protected function migrasi_2023021671($hasil)
    {
        // Sesuaikan data jabatan agar bisa digunakan di OpenKAB
        // Parameter migrasi ditentukan dari jabatan sekretaris dengan nama like sekretaris akan dilakukan migrasi
        $this->db
            ->like('nama', 'sekretaris')
            ->set('jenis', '2')
            ->update('ref_jabatan');

        return $hasil;
    }

    protected function migrasi_2023022271($hasil)
    {
        if (! $this->db->field_exists('slug', 'setting_modul')) {
            // Tambahkan kolom slug pada tabel setting_modul
            $hasil = $hasil && $this->dbforge->add_column('setting_modul', [
                'slug' => [
                    'type'       => 'VARCHAR',
                    'constraint' => '100',
                    'null'       => true,
                    'after'      => 'modul',
                    'unique'     => true,
                ],
            ]);

            $data_modul = [
                1   => 'home',
                2   => 'kependudukan',
                3   => 'statistik',
                4   => 'layanan-surat',
                5   => 'analisis',
                6   => 'bantuan',
                7   => 'pertanahan',
                8   => 'pengaturan-peta',
                9   => 'pemetaan',
                10  => 'hubung-warga',
                11  => 'pengaturan',
                13  => 'admin-web',
                14  => 'layanan-mandiri',
                15  => 'sekretariat',
                17  => 'identitas-desa',
                18  => 'pemerintah-desa',
                20  => 'wilayah-administratif',
                21  => 'penduduk',
                22  => 'keluarga',
                23  => 'rumah-tangga',
                24  => 'kelompok',
                25  => 'data-suplemen',
                26  => 'calon-pemilih',
                27  => 'statistik-kependudukan',
                28  => 'laporan-bulanan',
                29  => 'laporan-kelompok-rentan',
                30  => 'pengaturan-surat',
                31  => 'cetak-surat',
                32  => 'arsip-layanan',
                39  => 'kirim-pesan',
                40  => 'daftar-kontak',
                42  => 'modul',
                43  => 'aplikasi',
                44  => 'pengguna',
                45  => 'database',
                46  => 'info-sistem',
                47  => 'artikel',
                48  => 'widget',
                49  => 'menu',
                50  => 'komentar',
                51  => 'galeri',
                52  => 'informasi-publik',
                53  => 'media-sosial',
                54  => 'slider',
                55  => 'kotak-pesan',
                56  => 'pendaftar-layanan-mandiri',
                57  => 'surat-masuk',
                58  => 'surat-keluar',
                61  => 'inventaris',
                62  => 'peta',
                63  => 'klasfikasi-surat',
                64  => 'teks-berjalan',
                95  => 'produk-hukum',
                97  => 'daftar-persyaratan',
                98  => 'permohonan-surat',
                101 => 'status-desa',
                110 => 'master-analisis',
                111 => 'pengaturan-analisis',
                200 => 'info-desa',
                201 => 'keuangan',
                202 => 'impor-data',
                203 => 'laporan',
                205 => 'pengunjung',
                206 => 'kesehatan',
                207 => 'pendataan',
                208 => 'pemantauan',
                209 => 'input-data',
                210 => 'laporan-manual',
                211 => 'pengaturan-web',
                212 => 'qr-code',
                213 => 'daftar-persil',
                214 => 'c-desa',
                220 => 'pembangunan',
                301 => 'buku-administrasi-desa',
                302 => 'administrasi-umum',
                303 => 'administrasi-penduduk',
                304 => 'administrasi-keuangan',
                305 => 'administrasi-pembangunan',
                310 => 'buku-eskpedisi',
                311 => 'buku-lembaran-dan-berita-desa',
                312 => 'anjungan',
                313 => 'layanan-pelanggan',
                314 => 'pengaturan-layanan-mandiri',
                315 => 'buku-mutasi-penduduk',
                316 => 'buku-rekapitulasi-jumlah-penduduk',
                317 => 'buku-penduduk-sementara',
                318 => 'buku-ktp-dan-kk',
                319 => 'buku-tanah-kas-desa',
                320 => 'buku-tanah-di-desa',
                321 => 'pendapat',
                322 => 'buku-inventaris-dan-kekayaan-desa',
                323 => 'buku-rencana-kerja-pembangunan',
                324 => 'lapak',
                325 => 'laporan-apbdes',
                326 => 'sinkronisasi',
                327 => 'lembaga-desa',
                330 => 'laporan-penduduk',
                331 => 'pendaftaran-kerjasama',
                334 => 'pengaduan',
                335 => 'vaksin',
                336 => 'arsip-desa',
                337 => 'kehadiran',
                339 => 'jam-kerja',
                340 => 'hari-libur',
                341 => 'rekapitulasi',
                342 => 'kehadiran-pengaduan',
                343 => 'opendk',
                344 => 'pesan',
                345 => 'grup-kontak',
                346 => 'stunting',
                347 => 'daftar-anjungan',
                348 => 'anjungan-menu',
                349 => 'pengaturan-anjungan',
                350 => 'alasan-keluar',
                351 => 'gawai-layanan',
                352 => 'satu-data',
                353 => 'dtks',
                354 => 'buku-tamu',
                355 => 'data-tamu',
                356 => 'data-kepuasan',
                357 => 'data-pertanyaan',
                358 => 'data-keperluan',
                359 => 'optimasi-gambar',
            ];

            foreach ($data_modul as $id => $slug) {
                $this->db->where('id', $id)->update('setting_modul', ['slug' => $slug]);
            }
        }

        return $hasil;
    }
}
