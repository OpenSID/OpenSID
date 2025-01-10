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
 * Hak Cipta 2016 - 2025 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright Hak Cipta 2016 - 2025 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

use App\Enums\StatusEnum;
use App\Models\GrupAkses;
use App\Models\Modul;
use App\Models\UserGrup;
use App\Traits\Migrator;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

defined('BASEPATH') || exit('No direct script access allowed');

class Migrasi_2024060171 extends MY_Model
{
    use Migrator;

    public function up()
    {
       $this->migrasi_tabel();

       $this->migrasi_data();
    }

    protected function migrasi_tabel()
    {
    }

    // Migrasi perubahan data
    protected function migrasi_data()
    {
        $this->migrasi_2024050271();
        $this->migrasi_2024050272();
        $this->migrasi_2024051571();
        $this->migrasi_2024052151();
        $this->migrasi_2024052871();
        $this->migrasi_2024050551();
        $this->migrasi_2024050251();
        $this->migrasi_2024050751();
        $this->migrasi_2024050851();
        $this->migrasi_2024051251();
        $this->migrasi_2024051252();
        $this->migrasi_2024051253();
        $this->migrasi_2024053151();
    }

    protected function migrasi_2024050251()
    {
        $this->ubah_modul(
            ['slug' => 'peristiwa', 'url' => 'penduduk_log/clear'],
            ['url' => 'penduduk_log']
        );
    }

    protected function migrasi_2024050751()
    {
        DB::statement('delete from grup_akses where id_modul not in (select id from setting_modul)');
    }

    protected function migrasi_2024050851()
    {
        Modul::where('slug', 'wilayah-administratif')->update(['url' => 'wilayah']);
        Modul::where('slug', 'calon-pemilih')->update(['url' => 'dpt']);
        Modul::where('slug', 'data-suplemen')->update(['url' => 'suplemen']);
        Modul::where('slug', 'modul')->update(['url' => 'modul']);
        Modul::where('slug', 'widget')->update(['url' => 'web_widget']);
        Modul::where('slug', 'pengunjung')->update(['url' => 'pengunjung']);
        Modul::where('slug', 'klasifikasi-surat')->update(['url' => 'klasifikasi']);
        Modul::where('slug', 'qr-code')->update(['url' => 'qrcode']);
        Modul::where('slug', 'pengaturan-grup')->update(['url' => 'grup']);
        Modul::where('slug', 'artikel')->update(['url' => 'web']);
        Modul::where('slug', 'buku-ktp-dan-kk')->update(['url' => 'bumindes_penduduk_ktpkk']);
        Modul::where('slug', 'buku-rekapitulasi-jumlah-penduduk')->update(['url' => 'bumindes_penduduk_rekapitulasi']);
        Modul::where('slug', 'penduduk')->update(['url' => 'penduduk']);
        Modul::where('slug', 'keluarga')->update(['url' => 'keluarga']);
        Modul::where('slug', 'surat-keluar')->update(['url' => 'surat_keluar']);
        Modul::where('slug', 'surat-masuk')->update(['url' => 'surat_masuk']);
        Modul::where('slug', 'informasi-publik')->update(['url' => 'dokumen']);
    }

    protected function migrasi_2024051251()
    {
        UserGrup::where('slug', null)->get()->each(static function ($user) {
            $user->update([
                'slug' => unique_slug('user_grup', $user->nama),
            ]);
        });
    }

    protected function migrasi_2024051252()
    {
        DB::table('analisis_master')->where('jenis', 1)->update(['jenis' => 2]);
    }

    protected function migrasi_2024051253()
    {
        DB::table('tweb_penduduk_umur')->where('nama', 'Di Atas 75 Tahun')->update(['nama' => '75 Tahun ke Atas']);
    }

    protected function migrasi_2024052151()
    {
        $media_sosial = DB::table('media_sosial')
            ->where('config_id', identitas('id'))
            ->pluck('nama')->map(static fn ($item) => Str::slug($item))->toArray();

        $setting = DB::table('setting_aplikasi')
            ->where('config_id', identitas('id'))
            ->where('key', 'media_sosial_pemerintah_desa')
            ->first() ?? [];

        if (! $setting) {
        }

        $value  = json_decode($setting->value, true);
        $option = json_decode($setting->option, true);

        if (count($value) > count($media_sosial) || count($option) > count($media_sosial)) {
            $value  = array_values(array_filter(array_unique($value), static fn ($item) => in_array($item, $media_sosial)));
            $option = array_filter(array_unique($option, SORT_REGULAR), static fn ($item) => in_array($item['id'], $media_sosial));

            DB::table('setting_aplikasi')
                ->where('config_id', identitas('id'))
                ->where('key', 'media_sosial_pemerintah_desa')
                ->update([
                    'value'  => json_encode($value),
                    'option' => json_encode($option),
                ]);
        }
    }

    protected function migrasi_2024050272()
    {
        $this->createSetting([
            'judul'      => 'Icon Pembangunan Peta',
            'key'        => 'icon_pembangunan_peta',
            'value'      => 'construction.png',
            'keterangan' => 'Icon penanda Lokasi Pembangunan yang ditampilkan pada Peta',
            'jenis'      => 'select-simbol',
            'option'     => json_encode(['model' => 'App\\Models\\Simbol', 'value' => 'simbol', 'label' => 'simbol']),
            'attribute'  => 'class="required"',
            'kategori'   => 'pembangunan',
        ]);
    }

    protected function migrasi_2024050271()
    {
        $this->createSetting([
            'judul'      => 'Jumlah Gambar Galeri',
            'key'        => 'jumlah_gambar_galeri',
            'value'      => 4,
            'keterangan' => 'Jumlah gambar galeri yang ditampilkan pada widget galeri',
            'jenis'      => 'input-number',
            'attribute'  => 'min="1" max="50" step="1"',
            'kategori'   => 'galeri',
        ]);

        $this->createSetting([
            'judul'      => 'Urutan Gambar Galeri',
            'key'        => 'urutan_gambar_galeri',
            'value'      => 'acak',
            'keterangan' => 'Urutan gambar galeri yang ditampilkan pada widget galeri',
            'jenis'      => 'option',
            'option'     => json_encode([
                'asc'  => 'A - Z',
                'desc' => 'Z - A',
                'acak' => 'Acak',
            ]),
            'kategori' => 'galeri',
        ]);

        $this->createSetting([
            'judul'      => 'Jumlah Pengajuan Produk Oleh Warga',
            'key'        => 'jumlah_pengajuan_produk',
            'value'      => 3,
            'keterangan' => 'Jumlah pengajuan produk perhari oleh warga melalui layanan mandiri',
            'jenis'      => 'input-number',
            'attribute'  => 'min="1" max="50" step="1"',
            'kategori'   => 'lapak',
        ]);
    }

    protected function migrasi_2024051571()
    {
        $option = json_encode([
            '1' => 'Nomor berurutan untuk masing-masing surat masuk dan keluar; dan untuk semua surat layanan',
            '2' => 'Nomor berurutan untuk masing-masing surat masuk dan keluar; dan untuk setiap surat layanan dengan jenis yang sama',
            '3' => 'Nomor berurutan untuk keseluruhan surat layanan, masuk dan keluar',
            '4' => 'Nomor berurutan untuk masing-masing klasifikasi surat yang sama',
        ]);
        $this->createSetting([
            'judul'      => 'Penomoran Surat',
            'key'        => 'penomoran_surat',
            'value'      => '2',
            'keterangan' => 'Penomoran surat mulai dari satu (1) setiap tahun',
            'jenis'      => 'option',
            'option'     => $option,
            'kategori'   => 'sistem',
        ]);

        $this->createSetting([
            'judul'      => 'Penomoran Surat Dinas',
            'key'        => 'penomoran_surat_dinas',
            'value'      => '2',
            'keterangan' => 'Penomoran surat dinas mulai dari satu (1) setiap tahun',
            'jenis'      => 'option',
            'option'     => $option,
            'kategori'   => 'format_surat_dinas',
        ]);

       $this->createSetting([
           'judul'      => 'Panjang Nomor Surat Dinas',
           'key'        => 'panjang_nomor_surat_dinas',
           'value'      => '3',
           'keterangan' => "Nomor akan diisi '0' di sebelah kiri, kalau perlu",
           'jenis'      => 'text',
           'attribute'  => 'class="int"',
           'kategori'   => 'format_surat_dinas',
       ]);
    }

    protected function migrasi_2024050551()
    {
        if (! $this->db->field_exists('status', 'user_grup')) {
            $this->dbforge->add_column('user_grup', [
                'status' => [
                    'type'       => 'TINYINT',
                    'constraint' => 4,
                    'null'       => false,
                    'default'    => 1,
                    'after'      => 'jenis',
                ],
            ]);
        }

        if ($this->db->field_exists('nama', 'user_grup')) {
            $this->dbforge->modify_column('user_grup', [
                'nama' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 255,
                ],
            ]);
        }

        $pengguna = [
            [
                'config_id'  => identitas('id'),
                'nama'       => 'Sekretaris Desa',
                'slug'       => 'sekretaris-desa',
                'jenis'      => 1,
                'status'     => StatusEnum::TIDAK,
                'created_at' => Carbon::now(),
                'created_by' => 0,
                'updated_at' => Carbon::now(),
                'updated_by' => 0,
            ],
            [
                'config_id'  => identitas('id'),
                'nama'       => 'Kaur Perencanaan',
                'slug'       => 'kaur-perencanaan',
                'jenis'      => 1,
                'status'     => StatusEnum::TIDAK,
                'created_at' => Carbon::now(),
                'created_by' => 0,
                'updated_at' => Carbon::now(),
                'updated_by' => 0,
            ],
            [
                'config_id'  => identitas('id'),
                'nama'       => 'Kasi Pemerintahan',
                'slug'       => 'kasi-pemerintahan',
                'jenis'      => 1,
                'status'     => StatusEnum::TIDAK,
                'created_at' => Carbon::now(),
                'created_by' => 0,
                'updated_at' => Carbon::now(),
                'updated_by' => 0,
            ],
            [
                'config_id'  => identitas('id'),
                'nama'       => 'Kasi Pelayanan',
                'slug'       => 'kasi-pelayanan',
                'jenis'      => 1,
                'status'     => StatusEnum::TIDAK,
                'created_at' => Carbon::now(),
                'created_by' => 0,
                'updated_at' => Carbon::now(),
                'updated_by' => 0,
            ],
            [
                'config_id'  => identitas('id'),
                'nama'       => 'Kasi Kesejahteraan',
                'slug'       => 'kasi-kesejahteraan',
                'jenis'      => 1,
                'status'     => StatusEnum::TIDAK,
                'created_at' => Carbon::now(),
                'created_by' => 0,
                'updated_at' => Carbon::now(),
                'updated_by' => 0,
            ],
            [
                'config_id'  => identitas('id'),
                'nama'       => 'Kaur Umum dan Perencanaan',
                'slug'       => 'kaur-umum-dan-perencanaan',
                'jenis'      => 1,
                'status'     => StatusEnum::TIDAK,
                'created_at' => Carbon::now(),
                'created_by' => 0,
                'updated_at' => Carbon::now(),
                'updated_by' => 0,
            ],
            [
                'config_id'  => identitas('id'),
                'nama'       => 'Kaur Keuangan',
                'slug'       => 'kaur-keuangan',
                'jenis'      => 1,
                'status'     => StatusEnum::TIDAK,
                'created_at' => Carbon::now(),
                'created_by' => 0,
                'updated_at' => Carbon::now(),
                'updated_by' => 0,
            ],
            [
                'config_id'  => identitas('id'),
                'nama'       => 'Kepala Dusun',
                'slug'       => 'kepala-dusun',
                'jenis'      => 1,
                'status'     => StatusEnum::TIDAK,
                'created_at' => Carbon::now(),
                'created_by' => 0,
                'updated_at' => Carbon::now(),
                'updated_by' => 0,
            ],
        ];

        UserGrup::upsert($pengguna, ['slug']);

        $data = [
            [
                'grup'  => 'kaur-perencanaan',
                'slug'  => 'info-desa',
                'akses' => 0,
            ],
            [
                'grup'  => 'kaur-perencanaan',
                'slug'  => 'wilayah-administratif',
                'akses' => 7,
            ],
            [
                'grup'  => 'kaur-perencanaan',
                'slug'  => 'status-desa',
                'akses' => 7,
            ],
            [
                'grup'  => 'kaur-perencanaan',
                'slug'  => 'pemetaan',
                'akses' => 0,
            ],
            [
                'grup'  => 'kaur-perencanaan',
                'slug'  => 'peta',
                'akses' => 7,
            ],
            [
                'grup'  => 'kaur-perencanaan',
                'slug'  => 'pengaturan-peta',
                'akses' => 7,
            ],
            [
                'grup'  => 'kaur-perencanaan',
                'slug'  => 'plan',
                'akses' => 7,
            ],
            [
                'grup'  => 'kaur-perencanaan',
                'slug'  => 'point',
                'akses' => 7,
            ],
            [
                'grup'  => 'kaur-perencanaan',
                'slug'  => 'garis',
                'akses' => 7,
            ],
            [
                'grup'  => 'kaur-perencanaan',
                'slug'  => 'line',
                'akses' => 7,
            ],
            [
                'grup'  => 'kaur-perencanaan',
                'slug'  => 'area',
                'akses' => 7,
            ],
            [
                'grup'  => 'kaur-perencanaan',
                'slug'  => 'polygon',
                'akses' => 7,
            ],
            [
                'grup'  => 'kaur-perencanaan',
                'slug'  => 'admin-web',
                'akses' => 0,
            ],
            [
                'grup'  => 'kaur-perencanaan',
                'slug'  => 'artikel',
                'akses' => 7,
            ],
            [
                'grup'  => 'kaur-perencanaan',
                'slug'  => 'widget',
                'akses' => 7,
            ],
            [
                'grup'  => 'kaur-perencanaan',
                'slug'  => 'kategori',
                'akses' => 7,
            ],
            [
                'grup'  => 'kaur-perencanaan',
                'slug'  => 'menu',
                'akses' => 7,
            ],
            [
                'grup'  => 'kaur-perencanaan',
                'slug'  => 'komentar',
                'akses' => 7,
            ],
            [
                'grup'  => 'kaur-perencanaan',
                'slug'  => 'galeri',
                'akses' => 7,
            ],
            [
                'grup'  => 'kaur-perencanaan',
                'slug'  => 'theme',
                'akses' => 7,
            ],
            [
                'grup'  => 'kaur-perencanaan',
                'slug'  => 'media-sosial',
                'akses' => 7,
            ],
            [
                'grup'  => 'kaur-perencanaan',
                'slug'  => 'slider',
                'akses' => 7,
            ],
            [
                'grup'  => 'kaur-perencanaan',
                'slug'  => 'teks-berjalan',
                'akses' => 7,
            ],
            [
                'grup'  => 'kaur-perencanaan',
                'slug'  => 'pengunjung',
                'akses' => 7,
            ],
            [
                'grup'  => 'kaur-perencanaan',
                'slug'  => 'pengaturan-web',
                'akses' => 7,
            ],
            [
                'grup'  => 'kasi-pemerintahan',
                'slug'  => 'kependudukan',
                'akses' => 0,
            ],
            [
                'grup'  => 'kasi-pemerintahan',
                'slug'  => 'penduduk',
                'akses' => 7,
            ],
            [
                'grup'  => 'kasi-pemerintahan',
                'slug'  => 'keluarga',
                'akses' => 7,
            ],
            [
                'grup'  => 'kasi-pemerintahan',
                'slug'  => 'rumah-tangga',
                'akses' => 7,
            ],
            [
                'grup'  => 'kasi-pemerintahan',
                'slug'  => 'kelompok',
                'akses' => 7,
            ],
            [
                'grup'  => 'kasi-pemerintahan',
                'slug'  => 'data-suplemen',
                'akses' => 7,
            ],
            [
                'grup'  => 'kasi-pemerintahan',
                'slug'  => 'calon-pemilih',
                'akses' => 7,
            ],
            [
                'grup'  => 'kasi-pemerintahan',
                'slug'  => 'kategori-kelompok',
                'akses' => 7,
            ],
            [
                'grup'  => 'kasi-pemerintahan',
                'slug'  => 'peristiwa',
                'akses' => 7,
            ],
            [
                'grup'  => 'kasi-pemerintahan',
                'slug'  => 'statistik',
                'akses' => 0,
            ],
            [
                'grup'  => 'kasi-pemerintahan',
                'slug'  => 'statistik-kependudukan',
                'akses' => 7,
            ],
            [
                'grup'  => 'kasi-pemerintahan',
                'slug'  => 'laporan-bulanan',
                'akses' => 7,
            ],
            [
                'grup'  => 'kasi-pemerintahan',
                'slug'  => 'laporan-kelompok-rentan',
                'akses' => 7,
            ],
            [
                'grup'  => 'kasi-pemerintahan',
                'slug'  => 'laporan-penduduk',
                'akses' => 7,
            ],
            [
                'grup'  => 'kasi-pemerintahan',
                'slug'  => 'sekretariat',
                'akses' => 0,
            ],
            [
                'grup'  => 'kasi-pemerintahan',
                'slug'  => 'produk-hukum',
                'akses' => 7,
            ],
            [
                'grup'  => 'kasi-pemerintahan',
                'slug'  => 'informasi-publik',
                'akses' => 7,
            ],
            [
                'grup'  => 'kasi-pemerintahan',
                'slug'  => 'buku-administrasi-desa',
                'akses' => 0,
            ],
            [
                'grup'  => 'kasi-pemerintahan',
                'slug'  => 'administrasi-umum',
                'akses' => 7,
            ],
            [
                'grup'  => 'kasi-pemerintahan',
                'slug'  => 'administrasi-penduduk',
                'akses' => 7,
            ],
            [
                'grup'  => 'kasi-pemerintahan',
                'slug'  => 'buku-mutasi-penduduk',
                'akses' => 7,
            ],
            [
                'grup'  => 'kasi-pemerintahan',
                'slug'  => 'buku-rekapitulasi-jumlah-penduduk',
                'akses' => 7,
            ],
            [
                'grup'  => 'kasi-pemerintahan',
                'slug'  => 'buku-penduduk-sementara',
                'akses' => 7,
            ],
            [
                'grup'  => 'kasi-pemerintahan',
                'slug'  => 'buku-ktp-dan-kk',
                'akses' => 7,
            ],
            [
                'grup'  => 'kasi-pemerintahan',
                'slug'  => 'pertanahan',
                'akses' => 0,
            ],
            [
                'grup'  => 'kasi-pemerintahan',
                'slug'  => 'daftar-persil',
                'akses' => 7,
            ],
            [
                'grup'  => 'kasi-pemerintahan',
                'slug'  => 'c-desa',
                'akses' => 7,
            ],
            [
                'grup'  => 'kasi-pelayanan',
                'slug'  => 'layanan-surat',
                'akses' => 0,
            ],
            [
                'grup'  => 'kasi-pelayanan',
                'slug'  => 'pengaturan-surat',
                'akses' => 7,
            ],
            [
                'grup'  => 'kasi-pelayanan',
                'slug'  => 'cetak-surat',
                'akses' => 7,
            ],
            [
                'grup'  => 'kasi-pelayanan',
                'slug'  => 'permohonan-surat',
                'akses' => 7,
            ],
            [
                'grup'  => 'kasi-pelayanan',
                'slug'  => 'arsip-layanan',
                'akses' => 7,
            ],
            [
                'grup'  => 'kasi-pelayanan',
                'slug'  => 'daftar-persyaratan',
                'akses' => 7,
            ],
            [
                'grup'  => 'kasi-pelayanan',
                'slug'  => 'sekretariat',
                'akses' => 0,
            ],
            [
                'grup'  => 'kasi-pelayanan',
                'slug'  => 'surat-masuk',
                'akses' => 7,
            ],
            [
                'grup'  => 'kasi-pelayanan',
                'slug'  => 'surat-keluar',
                'akses' => 7,
            ],
            [
                'grup'  => 'kasi-pelayanan',
                'slug'  => 'surat-dinas',
                'akses' => 0,
            ],
            [
                'grup'  => 'kasi-pelayanan',
                'slug'  => 'pengaturan-surat-dinas',
                'akses' => 7,
            ],
            [
                'grup'  => 'kasi-pelayanan',
                'slug'  => 'cetak-surat-dinas',
                'akses' => 7,
            ],
            [
                'grup'  => 'kasi-pelayanan',
                'slug'  => 'arsip-surat-dinas',
                'akses' => 7,
            ],
            [
                'grup'  => 'kasi-kesejahteraan',
                'slug'  => 'analisis',
                'akses' => 0,
            ],
            [
                'grup'  => 'kasi-kesejahteraan',
                'slug'  => 'analisis-kategori',
                'akses' => 7,
            ],
            [
                'grup'  => 'kasi-kesejahteraan',
                'slug'  => 'analisis-indikator',
                'akses' => 7,
            ],
            [
                'grup'  => 'kasi-kesejahteraan',
                'slug'  => 'analisis-klasifikasi',
                'akses' => 7,
            ],
            [
                'grup'  => 'kasi-kesejahteraan',
                'slug'  => 'analisis-periode',
                'akses' => 7,
            ],
            [
                'grup'  => 'kasi-kesejahteraan',
                'slug'  => 'analisis-respon',
                'akses' => 7,
            ],
            [
                'grup'  => 'kasi-kesejahteraan',
                'slug'  => 'analisis-laporan',
                'akses' => 7,
            ],
            [
                'grup'  => 'kasi-kesejahteraan',
                'slug'  => 'analisis-statistik-jawaban',
                'akses' => 7,
            ],
            [
                'grup'  => 'kasi-kesejahteraan',
                'slug'  => 'master-analisis',
                'akses' => 7,
            ],
            [
                'grup'  => 'kasi-kesejahteraan',
                'slug'  => 'pengaturan-analisis',
                'akses' => 7,
            ],
            [
                'grup'  => 'kasi-kesejahteraan',
                'slug'  => 'bantuan',
                'akses' => 0,
            ],
            [
                'grup'  => 'kasi-kesejahteraan',
                'slug'  => 'program-bantuan',
                'akses' => 7,
            ],
            [
                'grup'  => 'kasi-kesejahteraan',
                'slug'  => 'peserta-bantuan',
                'akses' => 7,
            ],
            [
                'grup'  => 'kasi-kesejahteraan',
                'slug'  => 'satu-data',
                'akses' => 0,
            ],
            [
                'grup'  => 'kasi-kesejahteraan',
                'slug'  => 'dtks',
                'akses' => 7,
            ],
            [
                'grup'  => 'kaur-umum-dan-perencanaan',
                'slug'  => 'sekretariat',
                'akses' => 0,
            ],
            [
                'grup'  => 'kaur-umum-dan-perencanaan',
                'slug'  => 'inventaris',
                'akses' => 7,
            ],
            [
                'grup'  => 'kaur-umum-dan-perencanaan',
                'slug'  => 'inventaris-asset',
                'akses' => 7,
            ],
            [
                'grup'  => 'kaur-umum-dan-perencanaan',
                'slug'  => 'inventaris-gedung',
                'akses' => 7,
            ],
            [
                'grup'  => 'kaur-umum-dan-perencanaan',
                'slug'  => 'inventaris-jalan',
                'akses' => 7,
            ],
            [
                'grup'  => 'kaur-umum-dan-perencanaan',
                'slug'  => 'inventaris-kontruksi',
                'akses' => 7,
            ],
            [
                'grup'  => 'kaur-umum-dan-perencanaan',
                'slug'  => 'inventaris-peralatan',
                'akses' => 7,
            ],
            [
                'grup'  => 'kaur-umum-dan-perencanaan',
                'slug'  => 'api-inventaris-asset',
                'akses' => 7,
            ],
            [
                'grup'  => 'kaur-umum-dan-perencanaan',
                'slug'  => 'api-inventaris-gedung',
                'akses' => 7,
            ],
            [
                'grup'  => 'kaur-umum-dan-perencanaan',
                'slug'  => 'api-inventaris-gedung-1',
                'akses' => 7,
            ],
            [
                'grup'  => 'kaur-umum-dan-perencanaan',
                'slug'  => 'api-inventaris-jalan',
                'akses' => 7,
            ],
            [
                'grup'  => 'kaur-umum-dan-perencanaan',
                'slug'  => 'api-inventaris-kontruksi',
                'akses' => 7,
            ],
            [
                'grup'  => 'kaur-umum-dan-perencanaan',
                'slug'  => 'api-inventaris-peralatan',
                'akses' => 7,
            ],
            [
                'grup'  => 'kaur-umum-dan-perencanaan',
                'slug'  => 'api-inventaris-tanah',
                'akses' => 7,
            ],
            [
                'grup'  => 'kaur-umum-dan-perencanaan',
                'slug'  => 'laporan-inventaris',
                'akses' => 7,
            ],
            [
                'grup'  => 'kaur-keuangan',
                'slug'  => 'keuangan',
                'akses' => 0,
            ],
            [
                'grup'  => 'kaur-keuangan',
                'slug'  => 'impor-data',
                'akses' => 7,
            ],
            [
                'grup'  => 'kaur-keuangan',
                'slug'  => 'laporan',
                'akses' => 7,
            ],
            [
                'grup'  => 'kaur-keuangan',
                'slug'  => 'input-data',
                'akses' => 7,
            ],
            [
                'grup'  => 'kaur-keuangan',
                'slug'  => 'laporan-manual',
                'akses' => 7,
            ],
            [
                'grup'  => 'kaur-keuangan',
                'slug'  => 'laporan-apbdes',
                'akses' => 7,
            ],
            [
                'grup'  => 'kepala-dusun',
                'slug'  => 'kependudukan',
                'akses' => 0,
            ],
            [
                'grup'  => 'kepala-dusun',
                'slug'  => 'penduduk',
                'akses' => 3,
            ],
            [
                'grup'  => 'kepala-dusun',
                'slug'  => 'keluarga',
                'akses' => 3,
            ],
            [
                'grup'  => 'kepala-dusun',
                'slug'  => 'rumah-tangga',
                'akses' => 3,
            ],
            [
                'grup'  => 'kepala-dusun',
                'slug'  => 'kelompok',
                'akses' => 3,
            ],
            [
                'grup'  => 'kepala-dusun',
                'slug'  => 'peristiwa',
                'akses' => 3,
            ],
            [
                'grup'  => 'kepala-dusun',
                'slug'  => 'statistik',
                'akses' => 0,
            ],
            [
                'grup'  => 'kepala-dusun',
                'slug'  => 'statistik-kependudukan',
                'akses' => 3,
            ],
            [
                'grup'  => 'kepala-dusun',
                'slug'  => 'laporan-bulanan',
                'akses' => 3,
            ],
            [
                'grup'  => 'kepala-dusun',
                'slug'  => 'laporan-kelompok-rentan',
                'akses' => 3,
            ],
            [
                'grup'  => 'kepala-dusun',
                'slug'  => 'laporan-penduduk',
                'akses' => 3,
            ],
            [
                'grup'  => 'kepala-dusun',
                'slug'  => 'buku-administrasi-desa',
                'akses' => 0,
            ],
            [
                'grup'  => 'kepala-dusun',
                'slug'  => 'administrasi-umum',
                'akses' => 3,
            ],
            [
                'grup'  => 'kepala-dusun',
                'slug'  => 'administrasi-penduduk',
                'akses' => 3,
            ],
            [
                'grup'  => 'kepala-dusun',
                'slug'  => 'buku-mutasi-penduduk',
                'akses' => 3,
            ],
            [
                'grup'  => 'kepala-dusun',
                'slug'  => 'buku-rekapitulasi-jumlah-penduduk',
                'akses' => 3,
            ],
            [
                'grup'  => 'kepala-dusun',
                'slug'  => 'buku-penduduk-sementara',
                'akses' => 3,
            ],
            [
                'grup'  => 'kepala-dusun',
                'slug'  => 'buku-ktp-dan-kk',
                'akses' => 3,
            ],
            [
                'grup'  => 'kepala-dusun',
                'slug'  => 'pertanahan',
                'akses' => 0,
            ],
            [
                'grup'  => 'kepala-dusun',
                'slug'  => 'daftar-persil',
                'akses' => 3,
            ],
            [
                'grup'  => 'kepala-dusun',
                'slug'  => 'c-desa',
                'akses' => 3,
            ],
        ];

        $result = [];

        foreach (Modul::select(['slug', 'parent'])->get() as $value) {
            $sekre         = [];
            $sekre['grup'] = 'sekretaris-desa';
            $sekre['slug'] = $value->slug;

            if ($value->parent == 0) {
                $sekre['akses'] = 0;
            } else {
                $sekre['akses'] = 7;
            }

            $result[] = $sekre;
        }

        $data = array_merge($data, $result);

        foreach ($data as $row) {
            if ($id_modul = Modul::where('slug', $row['slug'])->first()->id) {
                $dataInsert = [
                    'config_id' => identitas('id'),
                    'id_grup'   => UserGrup::where('slug', $row['grup'])->first()->id,
                    'id_modul'  => $id_modul,
                    'akses'     => $row['akses'],
                ];
            }
            GrupAkses::upsert($dataInsert, ['id_grup'], ['id_modul']);
        }
    }

    protected function migrasi_2024052871()
    {
        $this->createSetting([
            'judul'      => 'Jumlah Gambar Galeri',
            'key'        => 'jumlah_gambar_galeri',
            'value'      => 4,
            'keterangan' => 'Jumlah gambar galeri yang ditampilkan pada widget galeri',
            'jenis'      => 'input-number',
            'attribute'  => 'min="1" max="50" step="1"',
            'kategori'   => 'galeri',
        ]);

        $this->createSetting([
            'judul'      => 'Urutan Gambar Galeri',
            'key'        => 'urutan_gambar_galeri',
            'value'      => 'acak',
            'keterangan' => 'Urutan gambar galeri yang ditampilkan pada widget galeri',
            'jenis'      => 'option',
            'option'     => json_encode([
                'asc'  => 'A - Z',
                'desc' => 'Z - A',
                'acak' => 'Acak',
            ]),
            'kategori' => 'galeri',
        ]);
    }

    protected function migrasi_2024053151()
    {
        DB::table('tweb_wil_clusterdesa')->where('dusun', '')->delete();
        DB::table('tweb_wil_clusterdesa')->where('rt', '')->update(['rt' => 0]);
        DB::table('tweb_wil_clusterdesa')->where('rw', '')->update(['rw' => 0]);
    }
}
