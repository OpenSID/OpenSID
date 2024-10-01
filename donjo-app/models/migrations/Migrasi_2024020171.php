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
 * Hak Cipta 2016 - 2024 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright Hak Cipta 2016 - 2024 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

use App\Models\Galery;
use App\Models\Kategori;
use App\Models\Komentar;
use App\Models\Penduduk;
use App\Models\PesanMandiri;
use App\Models\Suplemen;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

defined('BASEPATH') || exit('No direct script access allowed');

class Migrasi_2024020171 extends MY_model
{
    public function up()
    {
        $hasil = true;

        $hasil = $hasil && $this->migrasi_tabel($hasil);

        return $hasil && $this->migrasi_data($hasil);
    }

    protected function migrasi_tabel($hasil)
    {
        $hasil = $hasil && $this->migrasi_2024011251($hasil);
        $hasil = $hasil && $this->migrasi_2024011751($hasil);
        $hasil = $hasil && $this->migrasi_2024012251($hasil);
        $hasil = $hasil && $this->migrasi_2024011471($hasil);

        return $hasil && $this->migrasi_2024011571($hasil);
    }

    // Migrasi perubahan data
    protected function migrasi_data($hasil)
    {
        // Migrasi berdasarkan config_id
        $config_id = DB::table('config')->pluck('id')->toArray();

        foreach ($config_id as $id) {
            $hasil = $hasil && $this->migrasi_2024010452($hasil, $id);
            $hasil = $hasil && $this->migrasi_2024011371($hasil, $id);
            $hasil = $hasil && $this->migrasi_2024012971($hasil, $id);
        }

        // Migrasi tanpa config_id
        $hasil = $hasil && $this->migrasi_2024010451($hasil);
        $hasil = $hasil && $this->migrasi_2024010851($hasil);
        $hasil = $hasil && $this->migrasi_2024011451($hasil);
        $hasil = $hasil && $this->migrasi_2024011551($hasil);
        $hasil = $hasil && $this->migrasi_2024011051($hasil);
        $hasil = $hasil && $this->migrasi_2024011052($hasil);
        $hasil = $hasil && $this->migrasi_2024011951($hasil);
        $hasil = $hasil && $this->migrasi_2024012351($hasil);
        $hasil = $hasil && $this->migrasi_2024011971($hasil);

        return $hasil && $this->migrasi_2024012371($hasil);
    }

    protected function migrasi_2024010452($hasil, $id)
    {
        $hasil = $hasil && $this->tambah_setting([
            'judul'      => 'Status Penduduk Lahir',
            'key'        => 'surat_kelahiran_terkait_penduduk',
            'value'      => '[]',
            'keterangan' => 'Status Penduduk Lahir',
            'jenis'      => 'referensi',
            'option'     => json_encode(['model' => 'App\\Models\\FormatSurat', 'value' => 'url_surat', 'label' => 'nama']),
            'attribute'  => null,
            'kategori'   => 'log_penduduk',
        ], $id);

        $hasil = $hasil && $this->tambah_setting([
            'judul'      => 'Status Penduduk Mati',
            'key'        => 'surat_kematian_terkait_penduduk',
            'value'      => '[]',
            'keterangan' => 'Status Penduduk Mati',
            'jenis'      => 'referensi',
            'option'     => json_encode(['model' => 'App\\Models\\FormatSurat', 'value' => 'url_surat', 'label' => 'nama']),
            'attribute'  => null,
            'kategori'   => 'log_penduduk',
        ], $id);

        $hasil = $hasil && $this->tambah_setting([
            'judul'      => 'Status Penduduk Pindah Keluar',
            'key'        => 'surat_pindah_keluar_terkait_penduduk',
            'value'      => '[]',
            'keterangan' => 'Status Penduduk Pindah Keluar',
            'jenis'      => 'referensi',
            'option'     => json_encode(['model' => 'App\\Models\\FormatSurat', 'value' => 'url_surat', 'label' => 'nama']),
            'attribute'  => null,
            'kategori'   => 'log_penduduk',
        ], $id);

        $hasil = $hasil && $this->tambah_setting([
            'judul'      => 'Status Penduduk Hilang',
            'key'        => 'surat_hilang_terkait_penduduk',
            'value'      => '[]',
            'keterangan' => 'Status Penduduk Hilang',
            'jenis'      => 'referensi',
            'option'     => json_encode(['model' => 'App\\Models\\FormatSurat', 'value' => 'url_surat', 'label' => 'nama']),
            'attribute'  => null,
            'kategori'   => 'log_penduduk',
        ], $id);

        $hasil = $hasil && $this->tambah_setting([
            'judul'      => 'Status Penduduk Pindah Masuk',
            'key'        => 'surat_pindah_masuk_terkait_penduduk',
            'value'      => '[]',
            'keterangan' => 'Status Penduduk Pindah Masuk',
            'jenis'      => 'referensi',
            'option'     => json_encode(['model' => 'App\\Models\\FormatSurat', 'value' => 'url_surat', 'label' => 'nama']),
            'attribute'  => null,
            'kategori'   => 'log_penduduk',
        ], $id);

        return $hasil && $this->tambah_setting([
            'judul'      => 'Status Penduduk Pergi',
            'key'        => 'surat_pergi_terkait_penduduk',
            'value'      => '[]',
            'keterangan' => 'Status Penduduk Pergi',
            'jenis'      => 'referensi',
            'option'     => json_encode(['model' => 'App\\Models\\FormatSurat', 'value' => 'url_surat', 'label' => 'nama']),
            'attribute'  => null,
            'kategori'   => 'log_penduduk',
        ], $id);
    }

    protected function migrasi_2024010451($hasil)
    {
        $hasil = $hasil && $this->ubah_modul(
            ['slug' => 'komentar', 'url' => 'komentar/clear'],
            ['url' => 'komentar']
        );

        return $hasil && $this->ubah_modul(
            ['slug' => 'menu', 'url' => 'menu/clear'],
            ['url' => 'menu']
        );
    }

    protected function migrasi_2024010851($hasil)
    {
        return $hasil = $hasil && $this->ubah_modul(
            ['slug' => 'log-penduduk'],
            ['url' => 'penduduk_log/clear', 'hidden' => 0, 'ikon' => 'fa-archive', 'modul' => 'Catatan Peristiwa', 'slug' => 'catatan-peristiwa', 'level' => 2]
        );
    }

    protected function migrasi_2024011051($hasil)
    {
        // ubah status enabled menjadi 0 untuk nonaktif, sebelumnya 2
        Galery::where(['enabled' => 2])->update(['enabled' => 0]);

        return $hasil && $this->ubah_modul(
            ['slug' => 'galeri', 'url' => 'gallery/clear'],
            ['url' => 'gallery']
        );
    }

    protected function migrasi_2024011052($hasil)
    {
        return $hasil && $this->ubah_modul(
            ['slug' => 'pemerintah-desa', 'url' => 'pengurus/clear'],
            ['url' => 'pengurus']
        );
    }

    protected function migrasi_2024011251($hasil)
    {
        $hasil = $hasil && $this->hapus_foreign_key('lokasi', 'pembangunan_lokasi_fk', 'pembangunan');

        return $hasil && $this->tambahForeignKey('pembangunan_lokasi_cluster_fk', 'pembangunan', 'id_lokasi', 'tweb_wil_clusterdesa', 'id', true);
    }

    protected function migrasi_2024011451($hasil)
    {
        $tanpaSlug = Suplemen::whereNull('slug')->get();
        if ($tanpaSlug) {
            foreach ($tanpaSlug as $slug) {
                $slug->update();
            }
        }

        return $hasil;
    }

    protected function migrasi_2024011551($hasil)
    {
        $hasil = $hasil && $this->hapus_foreign_key('inventaris_tanah', 'FK_mutasi_inventaris_tanah', 'mutasi_inventaris_tanah');
        $hasil && $this->tambahForeignKey('mutasi_inventaris_tanah_inventaris_tanah_fk', 'mutasi_inventaris_tanah', 'id_inventaris_tanah', 'inventaris_tanah', 'id', true);
        $hasil = $hasil && $this->hapus_foreign_key('suplemen', 'suplemen_terdata_ibfk_1', 'suplemen_terdata');
        $hasil = $hasil && $this->tambahForeignKey('suplemen_terdata_suplemen_fk', 'suplemen_terdata', 'id_suplemen', 'suplemen', 'id', true);

        // hapus salah satu foreignkey karena dobel
        return $hasil && $this->hapus_foreign_key('tweb_penduduk', 'id_pend_fk', 'tweb_penduduk_mandiri');
    }

    protected function migrasi_2024011751($hasil)
    {
        if (! Schema::hasTable('pesan_mandiri')) {
            Schema::create('pesan_mandiri', static function (Blueprint $table) {
                $table->uuid('uuid')->primary();
                $table->integer('config_id');
                $table->string('owner', 50);
                $table->integer('penduduk_id');
                $table->tinyText('subjek')->nullable();
                $table->text('komentar');
                $table->timestamp('tgl_upload')->useCurrent();
                $table->tinyInteger('status')->nullable();
                $table->tinyInteger('tipe')->nullable();
                $table->text('permohonan')->nullable();
                $table->timestamp('created_at')->useCurrent();
                $table->timestamp('updated_at')->useCurrent();
                $table->tinyInteger('is_archived')->nullable()->default(0);
                $table->unique(['uuid', 'config_id']);
                $table->foreign('config_id')->references('id')->on('config')->onUpdate('cascade')->onDelete('cascade');
                $table->foreign('penduduk_id')->references('id')->on('tweb_penduduk')->onUpdate('cascade')->onDelete('cascade');
            });

            $komentarMandiri = Komentar::whereJenis(LAPORAN_MANDIRI)->get();
            if ($komentarMandiri) {
                foreach ($komentarMandiri as $key => $item) {
                    $penduduk = Penduduk::whereNik(trim($item->email))->first();
                    // masukkan data penduduk yang valid saja, ada kemungkinan nik tidak ditemukan ( case ganti nik )
                    if ($penduduk) {
                        $item->penduduk_id = $penduduk->id;
                        PesanMandiri::create($item->toArray());
                    }
                }
                Komentar::whereJenis(LAPORAN_MANDIRI)->delete();
            }

            Schema::table('komentar', static function (Blueprint $table) {
                $table->dropColumn('jenis');
            });
        }

        return $hasil;
    }

    protected function migrasi_2024011951($hasil)
    {
        return $hasil && $this->ubah_modul(
            ['slug' => 'kotak-pesan', 'url' => 'mailbox/clear'],
            ['url' => 'mailbox']
        );
    }

    protected function migrasi_2024012251($hasil)
    {
        if ($this->db->field_exists('userid', 'program')) {
            $hasil = $hasil && $this->dbforge->drop_column('program', 'userid');
        }

        return $hasil;
    }

    // migrasi 2312.0.0 - 2312.0.3 yang tidak sama dengan struktur instalasi awal
    protected function migrasi_2024012351($hasil)
    {
        $this->tambahIndeks('klasifikasi_surat', 'config_id, kode', 'UNIQUE', true);

        $hasil = $hasil && $this->ubah_modul(
            ['slug' => 'qr-code', 'url' => 'qr_code/clear'],
            ['url' => 'qr_code']
        );

        $hasil = $hasil && $this->ubah_modul(
            ['slug' => 'pendaftar-layanan-mandiri', 'url' => 'mandiri/clear'],
            ['url' => 'mandiri']
        );

        return $hasil && $this->ubah_modul(
            ['slug' => 'pengguna', 'url' => 'man_user/clear'],
            ['url' => 'man_user']
        );
    }

    protected function migrasi_2024011371($hasil, $id)
    {
        $statis = [
            [
                'id'   => 'statis',
                'nama' => 'Halaman Statis',
            ],
            [
                'id'   => 'agenda',
                'nama' => 'Agenda',
            ],
            [
                'id'   => 'keuangan',
                'nama' => 'Keuangan',
            ],
        ];

        return $hasil && $this->tambah_setting([
            'judul'      => 'Artikel Statis / Halaman',
            'key'        => 'artikel_statis',
            'value'      => json_encode(array_column($statis, 'id')),
            'keterangan' => 'Artikel Statis / Halaman yang akan ditampilkan pada halaman utama.',
            'kategori'   => 'conf_web',
            'jenis'      => 'multiple-option-array',
            'option'     => json_encode($statis),
        ], $id);
    }

    protected function migrasi_2024011471($hasil)
    {
        if (! $this->db->field_exists('tampilan', 'artikel')) {
            $hasil = $hasil && $this->db->query("ALTER TABLE `artikel` ADD COLUMN `tampilan` TINYINT(4) NULL DEFAULT '1' AFTER `hit`");
        }

        return $hasil;
    }

    protected function migrasi_2024011571($hasil)
    {
        if (! $this->db->field_exists('media_sosial', 'tweb_desa_pamong')) {
            $this->db->query('ALTER TABLE `tweb_desa_pamong` ADD `media_sosial` TEXT NULL');
        }

        return $hasil;
    }

    protected function migrasi_2024011971($hasil)
    {
        Kategori::where(['enabled' => 2])->update(['enabled' => 0]);

        return $hasil && $this->ubah_modul(
            ['slug' => 'kategori'],
            ['hidden' => 0, 'level' => 4, 'ikon' => 'fa-list-alt', 'urut' => 2]
        );
    }

    protected function migrasi_2024012371($hasil)
    {
        if (! $this->db->field_exists('format_nomor_global', 'tweb_surat_format')) {
            $hasil = $hasil && $this->dbforge->add_column('tweb_surat_format', [
                'format_nomor_global' => [
                    'type'       => 'TINYINT',
                    'constraint' => 1,
                    'null'       => true,
                    'default'    => 1,
                    'after'      => 'format_nomor',
                ],
            ]);
        }

        return $hasil;
    }

    protected function migrasi_2024012971($hasil, $id)
    {
        $pleaceholder = [
            'facebook'  => 'https://www.facebook.com/groups/komunitasopendesa',
            'instagram' => 'https://www.instagram.com/OpenDesa',
            'telegram'  => 'https://t.me/OpenDesa',
            'twitter'   => 'https://twitter.com/opendesa',
            'whatsapp'  => 'https://api.whatsapp.com/send?phone=62851234567890',
            'youtube'   => 'https://www.youtube.com/@KomunitasOpenSID-OpenDesa',
        ];

        $mediaSosial = DB::table('media_sosial')->get()
            ->map(static function ($item) use ($pleaceholder) {
                return [
                    'id'   => Str::slug($item->nama),
                    'nama' => $item->nama,
                    'url'  => $pleaceholder[Str::slug($item->nama)] ?? '',
                ];
            })->toArray();

        return $hasil && $this->tambah_setting([
            'judul'      => 'Media Sosial [Pemerintah Desa]',
            'key'        => 'media_sosial_pemerintah_desa',
            'value'      => json_encode(array_column($mediaSosial, 'id')),
            'keterangan' => 'Media Sosial yang akan ditampilkan pada halaman [Pemerintah Desa].',
            'kategori'   => 'Pemerintah Desa',
            'jenis'      => 'multiple-option-array',
            'option'     => json_encode($mediaSosial),
        ], $id);
    }
}
