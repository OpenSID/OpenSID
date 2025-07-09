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

use App\Models\KaderMasyarakat;
use App\Models\Modul;
use App\Models\PendudukMandiri;
use App\Models\RefPendudukBidang;
use App\Models\RefPendudukKursus;
use App\Models\Shortcut;
use App\Traits\Migrator;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

defined('BASEPATH') || exit('No direct script access allowed');

class Migrasi_2024040171 extends MY_Model
{
    use Migrator;

    public function up()
    {
        $this->migrasi_tabel();

        $this->migrasi_data();
    }

    protected function migrasi_tabel()
    {
        $this->migrasi_2024080302();
        $this->migrasi_2024031375();
        $this->migrasi_2024031373();
        $this->migrasi_2024031371();
        $this->migrasi_2024031374();
    }

    protected function migrasi_data()
    {
        $this->migrasi_2024030151();
        $this->migrasi_2024080301();
        $this->migrasi_2024031171();
        $this->migrasi_2024031372();
        $this->migrasi_2024021371();
        $this->migrasi_2024031471();
        $this->migrasi_2024031572();
        $this->migrasi_2024031771();
        $this->migrasi_2024030751();
        $this->migrasi_2024031051();
        $this->migrasi_2024031251();
        $this->migrasi_2024031451();
        $this->migrasi_2024031851();
        $this->migrasi_2024032051();
        $this->migrasi_2024031951();
    }

    protected function migrasi_2024030151()
    {
        $this->createSetting([
            'judul'      => 'Sinkronisasi OpenDK Server',
            'key'        => 'sinkronisasi_opendk',
            'value'      => setting('api_opendk_key') ? 1 : 0,
            'keterangan' => 'Aktifkan Sinkronisasi Server OpenDK',
            'kategori'   => 'opendk',
            'jenis'      => 'boolean',
            'option'     => null,
        ]);
    }

    protected function migrasi_2024030751()
    {
        Modul::where('slug', 'buku-tanah-di-desa')->update(['url' => 'bumindes_tanah_desa']);
    }

    protected function migrasi_2024031051()
    {
        Modul::where('slug', 'rumah-tangga')->update(['url' => 'rtm']);
    }

    protected function migrasi_2024031251()
    {
        $kader  = KaderMasyarakat::get();
        $bidang = RefPendudukBidang::get();
        $kursus = RefPendudukKursus::get();

        foreach ($kader as $item) {
            $resultBidang = [];
            $resultKursus = [];

            foreach ($bidang as $valueBidang) {
                if (strpos($item->bidang, $valueBidang['nama']) !== false) {
                    $resultBidang[] = $valueBidang['nama'];
                }
            }

            foreach ($kursus as $valueKursus) {
                if (strpos($item->kursus, $valueKursus['nama']) !== false) {
                    $resultKursus[] = $valueKursus['nama'];
                }
            }
            KaderMasyarakat::find($item->id)->update([
                'bidang' => json_encode($resultBidang),
                'kursus' => json_encode($resultKursus),
            ]);
        }
    }

    protected function migrasi_2024031451()
    {
        if (! $this->db->field_exists('input', 'log_surat')) {
            $this->db->query('ALTER TABLE `log_surat` ADD COLUMN `input` LONGTEXT NULL AFTER `pemohon`');
        }
    }

    protected function migrasi_2024031851()
    {
        PendudukMandiri::whereDoesntHave('penduduk')->delete();
        $this->tambahForeignKey('tweb_penduduk_mandiri_penduduk_fk', 'tweb_penduduk_mandiri', 'id_pend', 'tweb_penduduk', 'id', false, true);
    }

    protected function migrasi_2024031951()
    {
        // duplikasi foreign key
        $this->hapus_foreign_key('suplemen', 'suplemen_terdata_suplemen_fk', 'suplemen_terdata');
    }

    protected function migrasi_2024080301()
    {
        $this->createModul([
            'modul'      => 'Surat Dinas',
            'slug'       => 'surat-dinas',
            'url'        => '',
            'aktif'      => 1,
            'ikon'       => 'fa-book',
            'urut'       => 60,
            'level'      => 2,
            'hidden'     => 0,
            'ikon_kecil' => 'fa fa-book',
            'parent'     => 0,
        ]);
        $parentId = Modul::withoutGlobalScope(App\Scopes\ConfigIdScope::class)->where(['config_id' => identitas('id'), 'slug' => 'surat-dinas'])->first()->id;

        $this->createModul([
            'modul'      => 'Pengaturan Surat',
            'slug'       => 'pengaturan-surat-dinas',
            'url'        => 'surat_dinas',
            'aktif'      => 1,
            'ikon'       => 'fa-cog',
            'urut'       => 1,
            'level'      => 2,
            'hidden'     => 0,
            'ikon_kecil' => 'fa fa-cog',
            'parent'     => $parentId,
        ]);
    }

    protected function migrasi_2024080302()
    {
        if (! Schema::hasTable('surat_dinas')) {
            Schema::create('surat_dinas', static function (Blueprint $table) {
                $table->integer('id', true);
                $table->integer('config_id')->nullable();
                $table->string('nama', 100);
                $table->string('url_surat', 100);
                $table->string('kode_surat', 10)->nullable();
                $table->string('lampiran', 100)->nullable();
                $table->boolean('kunci')->default(false);
                $table->boolean('favorit')->default(false);
                $table->tinyInteger('jenis')->default(2);
                $table->integer('masa_berlaku')->nullable()->default(1);
                $table->string('satuan_masa_berlaku', 15)->nullable()->default('M');
                $table->boolean('qr_code')->default(false);
                $table->boolean('logo_garuda')->default(false);
                $table->longText('template')->nullable();
                $table->longText('template_desa')->nullable();
                $table->longText('form_isian')->nullable();
                $table->longText('kode_isian')->nullable();
                $table->string('orientasi', 10)->nullable();
                $table->string('ukuran', 10)->nullable();
                $table->text('margin')->nullable();
                $table->boolean('margin_global')->nullable()->default(false);
                $table->integer('footer')->default(1);
                $table->integer('header')->default(1);
                $table->string('format_nomor', 100)->nullable();
                $table->tinyInteger('format_nomor_global')->nullable()->default(1);
                $table->timesWithUserstamps();

                $table->unique(['config_id', 'url_surat'], 'url_surat_dinas_config');
                $table->foreign(['config_id'], 'surat_dinas_config_fk')->references(['id'])->on('config')->onUpdate('CASCADE')->onDelete('CASCADE');
            });
        }
    }

    protected function migrasi_2024031171()
    {
        $this->createSetting([
            'judul'      => 'Tinggi Header',
            'key'        => 'tinggi_header_surat_dinas',
            'value'      => 3.5,
            'keterangan' => 'Tinggi Header Surat',
            'jenis'      => 'text',
            'option'     => null,
            'attribute'  => null,
            'kategori'   => 'format_surat_dinas',
        ]);

        $this->createSetting([
            'judul'      => 'Tinggi Footer',
            'key'        => 'tinggi_footer_surat_dinas',
            'value'      => 2,
            'keterangan' => 'Tinggi Footer Surat',
            'jenis'      => 'text',
            'option'     => null,
            'attribute'  => null,
            'kategori'   => 'format_surat_dinas',
        ]);

        $this->createSetting([
            'judul' => 'Header Surat',
            'key'   => 'header_surat_dinas',
            'value' => '<table style="border-collapse: collapse; width: 100%;">
            <tbody>
            <tr>
            <td style="width: 10%;">[logo]</td>
            <td style="text-align: center; width: 90%;">
            <p style="margin: 0; text-align: center;"><span style="font-size: 14pt;">PEMERINTAH [SEbutan_kabupaten] [NAma_kabupaten] <br>KECAMATAN [NAma_kecamatan]<strong><br>[SEbutan_desa] [NAma_desa] </strong></span></p>
            <p style="margin: 0; text-align: center;"><em><span style="font-size: 10pt;">[Alamat_desA]</span></em></p>
            </td>
            </tr>
            </tbody>
            </table>
            <hr style="border: 3px solid;">',
            'keterangan' => 'Header Surat',
            'jenis'      => 'text',
            'option'     => null,
            'attribute'  => null,
            'kategori'   => 'format_surat_dinas',
        ]);

        $this->createSetting([
            'judul' => 'Footer Surat',
            'key'   => 'footer_surat_dinas',
            'value' => "<table style=\"border-collapse: collapse; width: 100%; height: 10px;\" border=\"0\">
            <tbody>
            <tr>
            <td style=\"width: 11.2886%; height: 10px;\">[kode_desa]</td>
            <td style=\"width: 78.3174%; height: 10px;\">
            <p style=\"text-align: center;\">\u{a0}</p>
            </td>
            <td style=\"width: 10.3939%; height: 10px; text-align: right;\">[KOde_surat]</td>
            </tr>
            </tbody>
            </table>",
            'keterangan' => 'Footer Surat',
            'jenis'      => 'text',
            'option'     => null,
            'attribute'  => null,
            'kategori'   => 'format_surat_dinas',
        ]);

        $this->createSetting([
            'judul' => 'Footer Surat TTE',
            'key'   => 'footer_surat_dinas_tte',
            'value' => "<table style=\"border-collapse: collapse; width: 100%; height: 10px;\" border=\"0\">
            <tbody>
            <tr>
            <td style=\"width: 11.2886%; height: 10px;\">[kode_desa]</td>
            <td style=\"width: 78.3174%; height: 10px;\">
            <p style=\"text-align: center;\">\u{a0}</p>
            </td>
            <td style=\"width: 10.3939%; height: 10px; text-align: right;\">[KOde_surat]</td>
            </tr>
            </tbody>
            </table>",
            'keterangan' => 'Footer Surat TTE',
            'jenis'      => 'text',
            'option'     => null,
            'attribute'  => null,
            'kategori'   => 'format_surat_dinas',
        ]);

        $this->createSetting([
            'judul'      => 'Font Surat',
            'key'        => 'font_surat_dinas',
            'value'      => 'Arial',
            'keterangan' => 'Font Surat',
            'jenis'      => 'text',
            'option'     => null,
            'attribute'  => null,
            'kategori'   => 'format_surat_dinas',
        ]);

        $this->createSetting([
            'judul'      => 'Format Nomor Surat',
            'key'        => 'format_nomor_surat_dinas',
            'value'      => '[kode_surat]/[nomor_surat, 3]/[kode_desa]/[bulan_romawi]/[tahun]',
            'keterangan' => 'Fomat penomoran surat',
            'jenis'      => 'text',
            'option'     => null,
            'attribute'  => null,
            'kategori'   => 'format_surat_dinas',
        ]);

        $this->createSetting([
            'judul'      => 'Format Tanggal Surat',
            'key'        => 'format_tanggal_surat_dinas',
            'value'      => 'd F Y',
            'keterangan' => 'Format tanggal pada kode isian surat.',
            'jenis'      => 'text',
            'option'     => null,
            'attribute'  => null,
            'kategori'   => 'format_surat_dinas',
        ]);

        $this->createSetting([
            'judul'      => 'Margin Global',
            'key'        => 'surat_dinas_margin',
            'value'      => json_encode(['kiri' => 1.78, 'atas' => 0.63, 'kanan' => 1.78, 'bawah' => 1.37]),
            'keterangan' => 'Margin Global untuk surat',
            'jenis'      => 'text',
            'option'     => null,
            'attribute'  => null,
            'kategori'   => 'format_surat_dinas',
        ]);
    }

    protected function migrasi_2024031371()
    {
        if (! Schema::hasTable('log_surat_dinas')) {
            Schema::create('log_surat_dinas', static function (Blueprint $table) {
                $table->integer('id', true);
                $table->integer('config_id')->nullable()->index('log_surat_config_fk');
                $table->integer('id_format_surat');
                $table->integer('id_pamong');
                $table->string('nama_pamong', 100)->nullable()->comment('Nama pamong agar tidak berubah saat ada perubahan di master pamong');
                $table->string('nama_jabatan', 100)->nullable();
                $table->integer('id_user');
                $table->timestamp('tanggal')->useCurrent();
                $table->string('bulan', 2)->nullable();
                $table->string('tahun', 4)->nullable();
                $table->string('no_surat', 20)->nullable();
                $table->string('nama_surat', 100)->nullable();
                $table->string('lampiran', 100)->nullable();
                $table->string('keterangan', 200)->nullable();
                $table->string('lokasi_arsip', 150)->nullable()->default('');
                $table->integer('urls_id')->nullable()->unique('urls_id');
                $table->tinyInteger('status')->default(0)->comment('0. Konsep, 1. Cetak');
                $table->string('log_verifikasi', 100)->nullable();
                $table->boolean('tte')->nullable();
                $table->boolean('verifikasi_operator')->nullable();
                $table->boolean('verifikasi_kades')->nullable();
                $table->boolean('verifikasi_sekdes')->nullable();
                $table->longText('isi_surat')->nullable();
                $table->longText('input')->nullable();
                $table->tinyInteger('karakter')->default(1)->nullable()->comment('1:biasa, 2:terbatas, 3:rahasia');
                $table->tinyInteger('derajat')->default(1)->nullable()->comment('1:biasa, 2:segera, 3:sangat segera');
                $table->timesWithUserstamps();
                $table->dateTime('deleted_at')->nullable();

                $table->foreign(['config_id'], 'log_surat_dinas_config_fk')->references(['id'])->on('config')->onUpdate('CASCADE')->onDelete('CASCADE');
                $table->foreign(['id_format_surat'], 'log_surat_dinas_format_fk')->references(['id'])->on('surat_dinas')->onUpdate('CASCADE')->onDelete('CASCADE');
                $table->foreign(['id_user'], 'log_surat_dinas_user_fk')->references(['id'])->on('user')->onUpdate('CASCADE')->onDelete('CASCADE');
                $table->foreign(['created_by'], 'log_surat_dinas_created_by_fk')->references(['id'])->on('user')->onUpdate('CASCADE')->onDelete('CASCADE');
                $table->foreign(['updated_by'], 'log_surat_dinas_updated_by_fk')->references(['id'])->on('user')->onUpdate('CASCADE')->onDelete('CASCADE');
            });
        }

        if (! Schema::hasColumn('log_tolak', 'id_surat_dinas') && Schema::hasTable('log_surat_dinas')) {
            Schema::table('log_tolak', static function (Blueprint $table) {
                $table->integer('id_surat_dinas')->nullable();
            });
            $this->tambahForeignKey('log_tolak_surat_dinas_fk', 'log_tolak', 'id_surat_dinas', 'log_surat_dinas', 'id', true);
        }
    }

    protected function migrasi_2024031372()
    {
        $parentId = Modul::withoutGlobalScope(App\Scopes\ConfigIdScope::class)->where(['config_id' => identitas('id'), 'slug' => 'surat-dinas'])->first()->id;
        $this->createModul([
            'modul'      => 'Cetak Surat',
            'slug'       => 'cetak-surat-dinas',
            'url'        => 'surat_dinas_cetak',
            'aktif'      => 1,
            'ikon'       => 'fa-files-o',
            'urut'       => 2,
            'level'      => 2,
            'hidden'     => 0,
            'ikon_kecil' => 'fa fa-files-o',
            'parent'     => $parentId,
        ]);

        $this->createModul([
            'modul'      => 'Arsip Layanan',
            'slug'       => 'arsip-surat-dinas',
            'url'        => 'surat_dinas_arsip',
            'aktif'      => 1,
            'ikon'       => 'fa-folder-open',
            'urut'       => 3,
            'level'      => 2,
            'hidden'     => 0,
            'ikon_kecil' => 'fa fa-folder-open',
            'parent'     => $parentId,
        ]);
    }

    public function migrasi_2024021371()
    {
        if (! Schema::hasTable('shortcut')) {
            Schema::create('shortcut', static function (Blueprint $table) {
                $table->id();
                $table->configId();
                $table->string('judul', 50);
                $table->string('link', 50)->nullable();
                $table->string('akses', 100)->nullable();
                $table->tinyInteger('jenis_query')->default(0);
                $table->string('raw_query', 150)->nullable();
                $table->string('icon', 50)->nullable();
                $table->string('warna', 25)->nullable();
                $table->integer('urut')->default(0);
                $table->tinyInteger('status')->default(0);
                $table->timestamps();
            });
        }

        if (Shortcut::count() === 0) {
            $shortcut = [
                [
                    'judul'     => 'Wilayah [desa]',
                    'link'      => 'wilayah',
                    'akses'     => 'wilayah-administratif',
                    'raw_query' => 'Dusun',
                    'icon'      => 'fa-map-marker',
                    'urut'      => 1,
                    'warna'     => '#605ca8',
                    'status'    => 1,
                ],
                [
                    'judul'     => 'Penduduk',
                    'link'      => 'penduduk',
                    'akses'     => 'penduduk',
                    'raw_query' => 'Penduduk',
                    'icon'      => 'fa-user',
                    'urut'      => 2,
                    'warna'     => '#00c0ef',
                    'status'    => 1,
                ],
                [
                    'judul'     => 'Keluarga',
                    'link'      => 'keluarga',
                    'akses'     => 'keluarga',
                    'raw_query' => 'Keluarga',
                    'icon'      => 'fa-users',
                    'urut'      => 3,
                    'warna'     => '#00a65a',
                    'status'    => 1,
                ],
                [
                    'judul'     => 'Surat Tercetak',
                    'link'      => 'keluar',
                    'akses'     => 'arsip-layanan',
                    'raw_query' => 'Surat Tercetak',
                    'icon'      => 'fa-file-text-o',
                    'urut'      => 4,
                    'warna'     => '#0073b7',
                    'status'    => 1,
                ],
                [
                    'judul'     => 'Kelompok',
                    'link'      => 'kelompok',
                    'akses'     => 'kelompok',
                    'raw_query' => 'Kelompok',
                    'icon'      => 'fa-user-plus',
                    'urut'      => 5,
                    'warna'     => '#dd4b39',
                    'status'    => 1,
                ],
                [
                    'judul'     => 'Rumah Tangga',
                    'link'      => 'rtm',
                    'akses'     => 'rumah-tangga',
                    'raw_query' => 'RTM',
                    'icon'      => 'fa-home',
                    'urut'      => 6,
                    'warna'     => '#d2d6de',
                    'status'    => 1,
                ],
                [
                    'judul'     => 'Bantuan',
                    'link'      => 'program_bantuan',
                    'akses'     => 'bantuan',
                    'raw_query' => 'Bantuan',
                    'icon'      => 'fa-handshake-o',
                    'urut'      => 7,
                    'warna'     => '#f39c12',
                    'status'    => 1,
                ],
                [
                    'judul'     => 'Verifikasi Layanan Mandiri',
                    'link'      => 'mandiri',
                    'akses'     => 'pendaftar-layanan-mandiri',
                    'raw_query' => 'Verifikasi Layanan Mandiri',
                    'icon'      => 'fa-drivers-license',
                    'urut'      => 8,
                    'warna'     => '#39cccc',
                    'status'    => 1,
                ],
            ];

            if (! Schema::hasColumn('shortcut', 'akses')) {
                $shortcut = array_map(static fn ($item) => array_diff_key($item, ['akses' => '', 'link' => '']), $shortcut);
            }

            foreach ($shortcut as $item) {
                Shortcut::create($item);
            }
        }

        $this->createModul([
            'modul'      => 'Shortcut',
            'slug'       => 'shortcut',
            'url'        => 'shortcut',
            'aktif'      => 1,
            'ikon'       => 'fa-chain',
            'urut'       => 20,
            'level'      => 1,
            'hidden'     => 0,
            'ikon_kecil' => 'fa-chain',
            'parent'     => $this->db->get_where('setting_modul', ['config_id' => identitas('id'), 'slug' => 'pengaturan'])->row()->id,
        ]);
    }

    protected function migrasi_2024031375()
    {
        if (! $this->db->field_exists('parent_id', 'komentar')) {
           $this->db->query('ALTER TABLE `komentar` ADD COLUMN `parent_id` INT(11) NULL');
        }
    }

    protected function migrasi_2024031373()
    {
        if (! $this->db->field_exists('file_akta_mati', 'log_penduduk')) {
           $this->db->query('ALTER TABLE `log_penduduk` ADD `file_akta_mati` VARCHAR(255) NULL DEFAULT NULL AFTER `akta_mati`;');
        }
    }

    public function migrasi_2024031471()
    {
       $this->createModul([
           'modul'      => 'Tema',
           'slug'       => 'theme',
           'url'        => 'theme',
           'aktif'      => 1,
           'ikon'       => 'fa-object-group',
           'urut'       => 5,
           'level'      => 1,
           'hidden'     => 0,
           'ikon_kecil' => 'fa-object-group',
           'parent'     => $this->db->get_where('setting_modul', ['config_id' => identitas('id'), 'slug' => 'admin-web'])->row()->id,
       ]);

        if (! Schema::hasTable('theme')) {
            Schema::create('theme', static function (Blueprint $table) {
                $table->id();
                $table->integer('config_id');
                $table->string('nama', 50)->default('0');
                $table->string('slug', 60)->nullable();
                $table->string('versi', 10)->nullable();
                $table->tinyInteger('sistem')->default(0);
                $table->string('path', 100)->default('');
                $table->tinyInteger('status')->default(0);
                $table->text('keterangan')->nullable();
                $table->text('opsi')->nullable();
                $table->timestamps();

                $table->unique(['slug', 'config_id']);
                $table->foreign('config_id')->references('id')->on('config')->onUpdate('cascade')->onDelete('cascade');
            });
        }

        if (DB::table('theme')->where('config_id', identitas('id'))->count() == 0) {
            $this->load->helper('theme');
            theme_scan();

            $this->sesuaikanTemaAktif(identitas('id'));
        }
    }

    protected function sesuaikanTemaAktif($config_id)
    {
        if (DB::table('setting_aplikasi')->where('config_id', $config_id)->where('key', 'web_theme')->exists()) {
            $temaSetting = DB::table('setting_aplikasi')->where('config_id', $config_id)->where('key', 'web_theme')->first()->value;
            $temaSetting = Str::slug($temaSetting);

            DB::table('theme')->where('config_id', $config_id)->where('slug', $temaSetting)->update(['status' => 1]);
            DB::table('theme')->where('config_id', $config_id)->where('slug', '!=', $temaSetting)->update(['status' => 0]);

            DB::table('setting_aplikasi')->where('config_id', $config_id)->where('key', 'web_theme')->delete();
        }
    }

    protected function migrasi_2024031374()
    {
        if (! $this->db->field_exists('kk_level', 'program')) {
           $this->db->query('ALTER TABLE `program` ADD COLUMN `kk_level` TEXT NULL DEFAULT NULL AFTER `sasaran`');
        }
    }

    protected function migrasi_2024031572()
    {
        $this->createSetting([
            'judul'      => 'Rentang Waktu Notifikasi Rilis',
            'key'        => 'rentang_waktu_notifikasi_rilis',
            'value'      => 7,
            'keterangan' => 'Pengaturan rentang waktu notifikasi rilis dalam satuan hari.',
            'jenis'      => 'input',
            'option'     => null,
            'attribute'  => 'class="bilangan required" placeholder="7" min="0" type="number"',
            'kategori'   => 'beranda',
        ]);
    }

    protected function migrasi_2024031771()
    {
        $wilayah = [
            ['id' => 'Peta Wilayah [desa]', 'nama' => 'Peta Wilayah [desa]'],
            ['id' => 'Peta Wilayah [dusun]', 'nama' => 'Peta Wilayah [dusun]'],
            ['id' => 'Peta Wilayah RW', 'nama' => 'Peta Wilayah RW'],
            ['id' => 'Peta Wilayah RT', 'nama' => 'Peta Wilayah RT'],
        ];

        $this->createSetting([
            'judul'      => 'Default Tampil Peta Wilayah',
            'key'        => 'default_tampil_peta_wilayah',
            'value'      => '',
            'keterangan' => 'Default peta wilayah yang akan ditampilkan saat pertama kali akses peta',
            'jenis'      => 'multiple-option-array',
            'option'     => json_encode($wilayah),
            'attribute'  => null,
            'kategori'   => 'peta',
        ]);

        $infrastruktur = [
            ['id' => 'Infrastruktur [desa]', 'nama' => 'Infrastruktur [desa]'],
            ['id' => 'Infrastruktur (Area)', 'nama' => 'Infrastruktur (Area)'],
            ['id' => 'Infrastruktur (Garis)', 'nama' => 'Infrastruktur (Garis)'],
            ['id' => 'Infrastruktur (Lokasi)', 'nama' => 'Infrastruktur (Lokasi)'],
            ['id' => 'Infrastruktur (Lokasi Pembangunan)', 'nama' => 'Infrastruktur (Lokasi Pembangunan)'],
            ['id' => 'Letter C-Desa', 'nama' => 'Letter C-Desa'],
        ];

        $this->createSetting([
            'judul'      => 'Default Tampil Peta Infrastruktur',
            'key'        => 'default_tampil_peta_infrastruktur',
            'value'      => '',
            'keterangan' => 'Default peta infrastruktur yang akan ditampilkan saat pertama kali akses peta',
            'jenis'      => 'multiple-option-array',
            'option'     => json_encode($infrastruktur),
            'attribute'  => null,
            'kategori'   => 'peta',
        ]);
    }

    protected function migrasi_2024032051()
    {
        DB::table('setting_modul')->whereIn('slug', ['beranda', 'home'])->delete();
    }
}
