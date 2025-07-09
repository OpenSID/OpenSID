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

use App\Models\Modul;
use App\Traits\Migrator;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

defined('BASEPATH') || exit('No direct script access allowed');

class Migrasi_2024010171 extends MY_Model
{
    use Migrator;

    public function up()
    {
        $this->migrasi_tabel();

        $this->migrasi_data();
    }

    protected function migrasi_tabel()
    {
        $this->migrasi_2023120752();
        $this->migrasi_2023120351();
        $this->migrasi_2023121951();
        $this->migrasi_2023122751();
        $this->migrasi_2023121971();
        $this->buat_ulang_view();
        $this->penyesuaian_data_awal();
    }

    protected function migrasi_data()
    {
        $this->migrasi_2023122871();
        $this->migrasi_2023120751();
        $this->migrasi_2023120553();
    }

    protected function migrasi_2023120351()
    {
        $this->tambahIndeks('klasifikasi_surat', 'config_id, kode', 'UNIQUE', true);
    }

    protected function migrasi_2023121951()
    {
        if (! Schema::hasTable('pemilihan')) {
            Schema::create('pemilihan', static function (Blueprint $table) {
                $table->uuid('uuid')->primary();
                $table->configId();
                $table->string('judul', 100);
                $table->date('tanggal');
                $table->integer('status')->default(0);
                $table->text('keterangan');
                $table->timesWithUserstamps();
                $table->unique(['uuid', 'config_id']);
            });
        }
    }

    protected function migrasi_2023121971()
    {
        if ($this->db->field_exists('gambar', 'gambar_gallery')) {
            $this->dbforge->modify_column('gambar_gallery', [
                'gambar' => [
                    'type' => 'TEXT',
                    'null' => true,
                ],
            ]);
        }

        if (! $this->db->field_exists('jenis', 'gambar_gallery')) {
            $this->dbforge->add_column('gambar_gallery', [
                'jenis' => [
                    'type'       => 'TINYINT',
                    'constraint' => 4,
                    'null'       => false,
                    'default'    => 1,
                ],
            ]);
        }
    }

    protected function migrasi_2023122751()
    {
        try {
            // analisis master
            $this->tambahForeignKey('analisis_master_subjek_fk', 'analisis_master', 'subjek_tipe', 'analisis_ref_subjek', 'id', true);

            // analisis_indikator
            $this->tambahForeignKey('analisis_indikator_master_fk', 'analisis_indikator', 'id_master', 'analisis_master', 'id', true);
            $this->tambahForeignKey('analisis_indikator_id_kategori_fk', 'analisis_indikator', 'id_kategori', 'analisis_kategori_indikator', 'id', true);
            $this->tambahForeignKey('analisis_indikator_tipe_fk', 'analisis_indikator', 'id_tipe', 'analisis_tipe_indikator', 'id', true);

            // analisis_kategori_indikator
            $this->tambahForeignKey('analisis_kategori_indikator_master_fk', 'analisis_kategori_indikator', 'id_master', 'analisis_master', 'id', true);

            // analisis_klasifikasi
            $this->tambahForeignKey('analisis_klasifikasi_master_fk', 'analisis_klasifikasi', 'id_master', 'analisis_master', 'id', true);

            // analisis_parameter
            $this->tambahForeignKey('analisis_parameter_indikator_fk', 'analisis_parameter', 'id_indikator', 'analisis_indikator', 'id', true);

            // analisis_partisipasi
            $this->tambahForeignKey('analisis_partisipasi_subjek_fk', 'analisis_partisipasi', 'id_subjek', 'analisis_parameter', 'id', true);
            $this->tambahForeignKey('analisis_partisipasi_master_fk', 'analisis_partisipasi', 'id_master', 'analisis_master', 'id', true);
            $this->tambahForeignKey('analisis_partisipasi_periode_fk', 'analisis_partisipasi', 'id_periode', 'analisis_periode', 'id', true);
            $this->tambahForeignKey('analisis_partisipasi_klasifikasi_fk', 'analisis_partisipasi', 'id_klassifikasi', 'analisis_klasifikasi', 'id', true);

            // analisis_periode
            $this->tambahForeignKey('analisis_periode_master_fk', 'analisis_periode', 'id_master', 'analisis_master', 'id', true);
            $this->tambahForeignKey('state_fk', 'analisis_periode', 'id_state', 'analisis_ref_state', 'id', true);

            // analisis_respon
            if ($this->cek_primary_key('analisis_respon', ['id_indikator', 'id_parameter', 'id_subjek', 'id_periode'])) {
                Schema::table('analisis_respon', static function (Blueprint $table) {
                    $table->dropPrimary(['id_indikator', 'id_parameter', 'id_subjek', 'id_periode']);
                });
            }
            $this->tambahForeignKey('analisis_respon_indikator_fk', 'analisis_respon', 'id_indikator', 'analisis_indikator', 'id', true);
            $this->tambahForeignKey('analisis_respon_parameter_fk', 'analisis_respon', 'id_parameter', 'analisis_parameter', 'id', true);
            $this->tambahForeignKey('analisis_respon_subjek_fk', 'analisis_respon', 'id_subjek', 'analisis_parameter', 'id', true);
            $this->tambahForeignKey('analisis_respon_periode_fk', 'analisis_respon', 'id_periode', 'analisis_periode', 'id', true);

            // analisis_respon_bukti
            $this->tambahForeignKey('analisis_respon_bukti_master_fk', 'analisis_respon_bukti', 'id_master', 'analisis_master', 'id', true);
            $this->tambahForeignKey('analisis_respon_bukti_periode_fk', 'analisis_respon_bukti', 'id_periode', 'analisis_periode', 'id', true);
            $this->tambahForeignKey('analisis_respon_bukti_subjek_fk', 'analisis_respon_bukti', 'id_subjek', 'analisis_ref_subjek', 'id', true);

            // analisis_respon_hasil
            $this->tambahForeignKey('analisis_respon_hasil_subjek_fk', 'analisis_respon_hasil', 'id_subjek', 'analisis_parameter', 'id', true);
            $this->tambahForeignKey('analisis_respon_hasil_master_fk', 'analisis_respon_hasil', 'id_master', 'analisis_master', 'id', true);
            $this->tambahForeignKey('analisis_respon_hasil_periode_fk', 'analisis_respon_hasil', 'id_periode', 'analisis_periode', 'id', true);

            // area
            $this->tambahForeignKey('area_cluster_fk', 'area', 'id_cluster', 'tweb_wil_clusterdesa', 'id', true);

            // artikel
            // untuk artikel, datanya harus disesuaikan dulu misalnya id_kategori 999
            DB::statement('alter table `artikel` modify column `id_kategori` int(11) NULL');

            if (! Schema::hasColumn('artikel', 'tipe')) {
                DB::statement('alter table artikel add column tipe varchar(50) NOT NULL default "dinamis"');
                DB::statement('update artikel set tipe = "statis", id_kategori = null where id_kategori = 999');
                DB::statement('update artikel set tipe = "agenda", id_kategori = null where id_kategori = 1000');
                DB::statement('update artikel set tipe = "keuangan", id_kategori = null where id_kategori = 1001');
            }

            $this->tambahForeignKey('artikel_kategori_fk', 'artikel', 'id_kategori', 'kategori', 'id', true);
            $this->tambahForeignKey('artikel_kategori_id_user_fk', 'artikel', 'id_user', 'user', 'id', true);

            // buku_kepuasan
            $this->tambahForeignKey('buku_kepuasan_nama_fk', 'buku_kepuasan', 'id_nama', 'buku_tamu', 'id', true);
            $this->tambahForeignKey('buku_kepuasan_pertanyaan_fk', 'buku_kepuasan', 'id_pertanyaan', 'buku_pertanyaan', 'id', true);

            // bulanan anak
            DB::statement('ALTER TABLE `posyandu` CHANGE COLUMN `id` `id` INT(11) NOT NULL AUTO_INCREMENT');
            DB::statement('ALTER TABLE `kia` CHANGE COLUMN `id` `id` INT(11) NOT NULL AUTO_INCREMENT');
            $this->tambahForeignKey('bulanan_anak_posyandu_fk', 'bulanan_anak', 'posyandu_id', 'posyandu', 'id', true);
            $this->tambahForeignKey('bulanan_anak_kia_fk', 'bulanan_anak', 'kia_id', 'kia', 'id', true);

            // cdesa_penduduk
            $this->tambahForeignKey('cdesa_penduduk_pend_fk', 'cdesa_penduduk', 'id_pend', 'tweb_penduduk', 'id', true);

            // fcm_token
            $this->tambahForeignKey('fcm_token_dd_user_fk', 'fcm_token', 'id_user', 'user', 'id', true);
            $this->tambahForeignKey('fcm_token_config_fk', 'fcm_token', 'config_id', 'config', 'id', true);

            // fcm_token_mandiri
            $this->tambahForeignKey('fcm_token_mandiri_config_fk', 'fcm_token_mandiri', 'config_id', 'config', 'id', true);
            $this->tambahForeignKey('fcm_token_mandiri_user_mandiri_fk', 'fcm_token_mandiri', 'id_user_mandiri', 'tweb_penduduk_mandiri', 'id_pend');

            // garis
            $this->tambahForeignKey('garis_cluster_fk', 'garis', 'id_cluster', 'tweb_wil_clusterdesa', 'id', true);

            // ibu_hamil
            $this->tambahForeignKey('ibu_hamil_posyandu_fk', 'ibu_hamil', 'posyandu_id', 'posyandu', 'id', true);
            $this->tambahForeignKey('ibu_hamil_kia_fk', 'ibu_hamil', 'kia_id', 'kia', 'id', true);

            // tweb_desa_pamong
            // untuk tipe data pamong_id pada tweb_desa_pamong ubah menjadi int(11) sama seperti table lainnya, agar tidak perlu ubah tipe data pada tabel lain yang merujuk pada kolom id tersebut
            // tambahkan relasi ke tabel tweb_penduduk pada kolom id_pend
            $this->tambahForeignKey('tweb_desa_pamong_pend_fk', 'tweb_desa_pamong', 'id_pend', 'tweb_penduduk', 'id', true);

            // kader_pemberdayaan_masyarakat
            $this->tambahForeignKey('kader_pemberdayaan_masyarakat_penduduk_fk', 'kader_pemberdayaan_masyarakat', 'penduduk_id', 'tweb_penduduk', 'id', true);

            // kehadiran_pengaduan
            $this->tambahForeignKey('kehadiran_pengaduan_penduduk_fk', 'kehadiran_pengaduan', 'id_penduduk', 'tweb_penduduk', 'id', true);
            $this->tambahForeignKey('kehadiran_pengaduan_pamong_fk', 'kehadiran_pengaduan', 'id_pamong', 'tweb_desa_pamong', 'pamong_id');

            // kehadiran_perangkat_desa
            $this->tambahForeignKey('kehadiran_perangkat_desa_pamong_fk', 'kehadiran_perangkat_desa', 'pamong_id', 'tweb_desa_pamong', 'pamong_id');

            // kelompok
            $this->tambahForeignKey('kelompok_ketua_fk', 'kelompok', 'id_ketua', 'tweb_penduduk', 'id', true);

            // kia
            $this->tambahForeignKey('kia_ibu_fk', 'kia', 'ibu_id', 'tweb_penduduk', 'id', true);
            $this->tambahForeignKey('kia_anak_fk', 'kia', 'anak_id', 'tweb_penduduk', 'id', true);

            // komentar
            DB::statement('alter table `komentar` modify column `id_artikel` int(11) NULL');

            if (! Schema::hasColumn('komentar', 'jenis')) {
                DB::statement('alter table komentar add column jenis varchar(50) NULL default NULL');
                DB::statement('update komentar set jenis = "pesan-mandiri", id_artikel = null where id_artikel = 775');
            }
            $this->tambahForeignKey('komentar_artikel_fk', 'komentar', 'id_artikel', 'artikel', 'id', true);

            // log_hapus_penduduk
            $this->tambahForeignKey('log_hapus_penduduk_pend_fk', 'log_hapus_penduduk', 'id_pend', 'tweb_penduduk', 'id', true);

            // tweb_penduduk_map
            if ($this->cek_primary_key('tweb_penduduk_map', ['id'])) {
                Schema::table('tweb_penduduk_map', static function (Blueprint $table) {
                    $table->dropPrimary(['id']);
                });
            }
            $this->tambahForeignKey('tweb_penduduk_map_pend_fk', 'tweb_penduduk_map', 'id', 'tweb_penduduk', 'id', true);

            // log_keluarga
            $this->tambahForeignKey('log_keluarga_pend_fk', 'log_keluarga', 'id_pend', 'tweb_penduduk', 'id', true);
            $this->tambahForeignKey('log_keluarga_kk_fk', 'log_keluarga', 'id_kk', 'tweb_keluarga', 'id', true);

            // log_perubahan_penduduk
            $this->tambahForeignKey('log_perubahan_penduduk_pend_fk', 'log_perubahan_penduduk', 'id_pend', 'tweb_penduduk', 'id', true);
            $this->tambahForeignKey('log_perubahan_penduduk_cluster_fk', 'log_perubahan_penduduk', 'id_cluster', 'tweb_wil_clusterdesa', 'id', true);

            // log_surat
            $this->tambahForeignKey('log_surat_format_surat_fk', 'log_surat', 'id_format_surat', 'tweb_surat_format', 'id', true);
            $this->tambahForeignKey('log_surat_pend_fk', 'log_surat', 'id_pend', 'tweb_penduduk', 'id', true);
            $this->tambahForeignKey('log_surat_pamong_fk', 'log_surat', 'id_pamong', 'tweb_desa_pamong', 'pamong_id');
            $this->tambahForeignKey('log_surat_pamong_urls_fk', 'log_surat', 'urls_id', 'urls', 'id');
            $this->tambahForeignKey('log_surat_user_fk', 'log_surat', 'id_user', 'user', 'id', true);

            // log_tolak
            $this->tambahForeignKey('log_tolak_surat_fk', 'log_tolak', 'id_surat', 'log_surat', 'id', true);

            // lokasi
            $this->tambahForeignKey('lokasi_cluster_fk', 'lokasi', 'id_cluster', 'tweb_wil_clusterdesa', 'id', true);
            $this->tambahForeignKey('lokasi_point_fk', 'lokasi', 'ref_point', 'point', 'id', true);

            // cek lagi, apakah benar id_peta reference ke lokasi ?
            // pelapak
            $this->tambahForeignKey('pelapak_pend_fk', 'pelapak', 'id_pend', 'tweb_penduduk', 'id', true);

            // pembangunan
            // $this->tambahForeignKey('pembangunan_lokasi_fk', 'pembangunan', 'id_lokasi', 'tweb_wil_clusterdesa', 'id', true);

            // pembangunan_ref_dokumentasi
            $this->tambahForeignKey('pembangunan_ref_dokumentasi_pembangunan_fk', 'pembangunan_ref_dokumentasi', 'id_pembangunan', 'pembangunan', 'id', true);

            // permohonan_surat
            $this->tambahForeignKey('permohonan_surat_pemohon_fk', 'permohonan_surat', 'id_pemohon', 'tweb_penduduk', 'id', true);
            $this->tambahForeignKey('permohonan_surat_surat_fk', 'permohonan_surat', 'id_surat', 'tweb_surat_format', 'id', true);

            // persil
            // $this->tambahForeignKey('persil_wilayah_fk', 'persil', 'id_wilayah', 'tweb_wil_clusterdesa', 'id', true);

            // cek lagi, apakah benar id_peta reference ke lokasi ?
            // pesan_detail
            DB::statement('ALTER TABLE `pesan` CHANGE COLUMN `id` `id` INT(11) NOT NULL AUTO_INCREMENT');
            $this->tambahForeignKey('pesan_detail_pesan_fk', 'pesan_detail', 'pesan_id', 'pesan', 'id', true);

            // program_peserta
            $this->tambahForeignKey('program_peserta_program_fk', 'program_peserta', 'program_id', 'program', 'id', true);
            $this->tambahForeignKey('program_peserta_kartu_fk', 'program_peserta', 'kartu_id_pend', 'tweb_penduduk', 'id', true);

            // sasaran_paud
            $this->tambahForeignKey('sasaran_paud_posyandu_fk', 'sasaran_paud', 'posyandu_id', 'posyandu', 'id', true);
            $this->tambahForeignKey('sasaran_paud_kia_fk', 'sasaran_paud', 'kia_id', 'kia', 'id', true);

            // tanah_desa
            $this->tambahForeignKey('tanah_desa_penduduk_fk', 'tanah_desa', 'id_penduduk', 'tweb_penduduk', 'id', true);

            // tweb_desa_pamong
            $this->tambahForeignKey('tweb_desa_pamong_jabatan_fk', 'tweb_desa_pamong', 'jabatan_id', 'ref_jabatan', 'id', true);

            // tweb_keluarga
            $this->tambahForeignKey('tweb_keluarga_kepala_fk', 'tweb_keluarga', 'nik_kepala', 'tweb_penduduk', 'id', true);
            $this->tambahForeignKey('tweb_keluarga_cluster_fk', 'tweb_keluarga', 'id_cluster', 'tweb_wil_clusterdesa', 'id', true);

            // tweb_penduduk
            // DB::statement('alter table tweb_penduduk modify column id_rtm int(11)');
            $this->tambahForeignKey('tweb_penduduk_kk_fk', 'tweb_penduduk', 'id_kk', 'tweb_keluarga', 'id', true);
            // id_rtm yang disimpan adalah no_kk pada table tweb_rtm, bukan id-nya
            // $this->tambahForeignKey('tweb_penduduk_rtm_fk', 'tweb_penduduk', 'id_rtm','tweb_rtm', 'id', true);
            $this->tambahForeignKey('tweb_penduduk_cluster_fk', 'tweb_penduduk', 'id_cluster', 'tweb_wil_clusterdesa', 'id', true);

            // rtm
            $this->tambahForeignKey('tweb_rtm_kepala_fk', 'tweb_rtm', 'nik_kepala', 'tweb_penduduk', 'id', true);

            // tweb_wil_clusterdesa
            $this->tambahForeignKey('tweb_wil_clusterdesa_kepala_fk', 'tweb_wil_clusterdesa', 'id_kepala', 'tweb_penduduk', 'id', true);

            // user
            DB::statement('ALTER TABLE `user` CHANGE COLUMN `id` `id` INT(11) NOT NULL AUTO_INCREMENT');
            $this->tambahForeignKey('user_grup_fk', 'user', 'id_grup', 'user_grup', 'id', true);
            $this->tambahForeignKey('user_pamong_fk', 'user', 'pamong_id', 'tweb_desa_pamong', 'pamong_id');

            // alias_kodeisian
            $this->hapus_foreign_key('config', 'alias_kodeisian_config_id_foreign', 'alias_kodeisian');
            $this->tambahForeignKey('alias_kodeisian_config_fk', 'alias_kodeisian', 'config_id', 'config', 'id', true);

            // suplemen
            if (! Schema::hasTable('suplemen')) {
                $this->runMigration('2023_12_22_015242_create_suplemen_table');
                $this->runMigration('2023_12_22_015245_add_foreign_keys_to_suplemen_table');
            }

            // suplemen_terdata
            if (! Schema::hasTable('suplemen_terdata')) {
                $this->runMigration('2023_12_22_015242_create_suplemen_terdata_table');
                $this->runMigration('2023_12_22_015245_add_foreign_keys_to_suplemen_terdata_table');
            }
            $this->hapus_foreign_key('suplemen', 'suplemen_terdata_ibfk_1', 'suplemen_terdata');
            $this->tambahForeignKey('suplemen_terdata_suplemen_1', 'suplemen_terdata', 'id_suplemen', 'suplemen', 'id', true);

            // log_notifikasi_admin
            $this->runMigration('2023_12_22_015242_create_log_notifikasi_admin_table');
            $this->tambahForeignKey('log_notifikasi_admin_config_fk', 'log_notifikasi_admin', 'config_id', 'config', 'id', true);
            $this->tambahForeignKey('log_notifikasi_admin_user_fk', 'log_notifikasi_admin', 'id_user', 'user', 'id', true);

            // log_notifikasi_mandiri
            $this->runMigration('2023_12_22_015242_create_log_notifikasi_mandiri_table');
            DB::statement('ALTER TABLE `log_notifikasi_mandiri` CHANGE COLUMN `id_user_mandiri` `id_user_mandiri` INT(11) NULL DEFAULT NULL');
            $this->tambahForeignKey('log_notifikasi_mandiri_config_fk', 'log_notifikasi_mandiri', 'config_id', 'config', 'id', true);
            $this->tambahForeignKey('log_notifikasi_mandiri_user_mandiri_fk', 'log_notifikasi_mandiri', 'id_user_mandiri', 'tweb_penduduk_mandiri', 'id_pend');

            // tidak digunakan
            if (Schema::hasTable('captcha_codes')) {
                Schema::dropIfExists('captcha_codes');
            }

            if (Schema::hasColumn('program', 'userid')) {
                Schema::table('program', static function ($table) {
                    $table->dropColumn('userid');
                });
            }

            if (Schema::hasColumn('dtks_ref_lampiran', 'id')) {
                Schema::table('dtks_ref_lampiran', static function ($table) {
                    $table->dropColumn('id');
                });
            }

            if (Schema::hasColumn('analisis_partisipasi', 'id')) {
                Schema::table('analisis_partisipasi', static function ($table) {
                    $table->dropColumn('id');
                });
            }

            if (Schema::hasColumn('analisis_respon', 'config_id')) {
                Schema::table('analisis_respon', static function ($table) {
                    $table->dropForeign('analisis_respon_config_fk');
                    $table->dropColumn('config_id');
                });
            }
        } catch (Exception $e) {
            log_message('error', $e->getMessage());

            throw new Exception($e->getMessage());
        }
    }

    protected function migrasi_2023120553()
    {
        DB::table('tweb_penduduk')->where('kk_level', 0)->update(['kk_level' => null]);
    }

    protected function migrasi_2023120751()
    {
        Modul::where('slug', 'data-suplemen')->update(['url' => 'suplemen']);
        Modul::where('slug', 'wilayah-administratif')->update(['url' => 'wilayah']);
        Modul::where('slug', 'pengunjung')->update(['url' => 'pengunjung']);
        Modul::where('slug', 'klasifikasi-surat')->update(['url' => 'klasifikasi']);
        Modul::where('slug', 'polygon')->update(['url' => 'polygon']);
        Modul::where('slug', 'area')->update(['url' => 'area']);
        Modul::where('slug', 'garis')->update(['url' => 'garis']);
        Modul::where('slug', 'widget')->update(['url' => 'web_widget']);
        Modul::where('slug', 'line')->update(['url' => 'line']);
        Modul::where('slug', 'point')->update(['url' => 'point']);
        Modul::where('slug', 'arsip-layanan')->update(['url' => 'keluar/clear']);
        Modul::where('slug', 'modul')->update(['url' => 'modul']);
        Modul::where('slug', 'arsip-layanan')->update(['url' => 'keluar']);
        Modul::where('slug', 'calon-pemilih')->update(['url' => 'dpt']);
    }

    protected function migrasi_2023122871()
    {
        $this->createSetting([
            'judul' => 'Notifikasi Reset PIN',
            'key'   => 'notifikasi_reset_pin',
            'value' => 'HALO [nama],
            BERIKUT ADALAH KODE PIN YANG BARU SAJA DIHASILKAN,
            KODE PIN INI SANGAT RAHASIA
            JANGAN BERIKAN KODE PIN KEPADA SIAPA PUN,
            TERMASUK PIHAK YANG MENGAKU DARI DESA ANDA.
            KODE PIN: [pin]
            JIKA BUKAN ANDA YANG MELAKUKAN RESET PIN TERSEBUT
            SILAHKAN LAPORKAN KEPADA OPERATOR DESA
            LINK : [website]',
            'keterangan' => 'Pesan notifikasi reset PIN',
            'jenis'      => 'textarea',
            'option'     => null,
            'attribute'  => null,
            'kategori'   => 'sistem',
        ]);

        $this->createSetting([
            'judul'      => 'Jumlah Gambar Slider',
            'key'        => 'jumlah_gambar_slider',
            'value'      => '10',
            'keterangan' => 'Jumlah Gambar Slider Yang di Tampilkan',
            'jenis'      => 'text',
            'option'     => null,
            'attribute'  => null,
            'kategori'   => 'artikel',
        ]);

        $this->createSetting([
            'judul'      => 'Tagline / Motto [desa]',
            'key'        => 'motto_desa',
            'value'      => '',
            'keterangan' => 'Tagline / Motto [desa]',
            'jenis'      => 'text',
            'attribute'  => null,
            'kategori'   => 'sistem',
        ]);
    }

    protected function migrasi_2023120752()
    {
        $this->db->query('ALTER TABLE config MODIFY path LONGTEXT DEFAULT NULL;');
    }

    protected function buat_ulang_view()
    {
        // Penduduk Hidup
        DB::statement('CREATE OR REPLACE VIEW `penduduk_hidup` AS select `tweb_penduduk`.`id` AS `id`,`tweb_penduduk`.`config_id` AS `config_id`,`tweb_penduduk`.`nama` AS `nama`,`tweb_penduduk`.`nik` AS `nik`,`tweb_penduduk`.`id_kk` AS `id_kk`,`tweb_penduduk`.`kk_level` AS `kk_level`,`tweb_penduduk`.`id_rtm` AS `id_rtm`,`tweb_penduduk`.`rtm_level` AS `rtm_level`,`tweb_penduduk`.`sex` AS `sex`,`tweb_penduduk`.`tempatlahir` AS `tempatlahir`,`tweb_penduduk`.`tanggallahir` AS `tanggallahir`,`tweb_penduduk`.`agama_id` AS `agama_id`,`tweb_penduduk`.`pendidikan_kk_id` AS `pendidikan_kk_id`,`tweb_penduduk`.`pendidikan_sedang_id` AS `pendidikan_sedang_id`,`tweb_penduduk`.`pekerjaan_id` AS `pekerjaan_id`,`tweb_penduduk`.`status_kawin` AS `status_kawin`,`tweb_penduduk`.`warganegara_id` AS `warganegara_id`,`tweb_penduduk`.`dokumen_pasport` AS `dokumen_pasport`,`tweb_penduduk`.`dokumen_kitas` AS `dokumen_kitas`,`tweb_penduduk`.`ayah_nik` AS `ayah_nik`,`tweb_penduduk`.`ibu_nik` AS `ibu_nik`,`tweb_penduduk`.`nama_ayah` AS `nama_ayah`,`tweb_penduduk`.`nama_ibu` AS `nama_ibu`,`tweb_penduduk`.`foto` AS `foto`,`tweb_penduduk`.`golongan_darah_id` AS `golongan_darah_id`,`tweb_penduduk`.`id_cluster` AS `id_cluster`,`tweb_penduduk`.`status` AS `status`,`tweb_penduduk`.`alamat_sebelumnya` AS `alamat_sebelumnya`,`tweb_penduduk`.`alamat_sekarang` AS `alamat_sekarang`,`tweb_penduduk`.`status_dasar` AS `status_dasar`,`tweb_penduduk`.`hamil` AS `hamil`,`tweb_penduduk`.`cacat_id` AS `cacat_id`,`tweb_penduduk`.`sakit_menahun_id` AS `sakit_menahun_id`,`tweb_penduduk`.`akta_lahir` AS `akta_lahir`,`tweb_penduduk`.`akta_perkawinan` AS `akta_perkawinan`,`tweb_penduduk`.`tanggalperkawinan` AS `tanggalperkawinan`,`tweb_penduduk`.`akta_perceraian` AS `akta_perceraian`,`tweb_penduduk`.`tanggalperceraian` AS `tanggalperceraian`,`tweb_penduduk`.`cara_kb_id` AS `cara_kb_id`,`tweb_penduduk`.`telepon` AS `telepon`,`tweb_penduduk`.`tanggal_akhir_paspor` AS `tanggal_akhir_paspor`,`tweb_penduduk`.`no_kk_sebelumnya` AS `no_kk_sebelumnya`,`tweb_penduduk`.`ktp_el` AS `ktp_el`,`tweb_penduduk`.`status_rekam` AS `status_rekam`,`tweb_penduduk`.`waktu_lahir` AS `waktu_lahir`,`tweb_penduduk`.`tempat_dilahirkan` AS `tempat_dilahirkan`,`tweb_penduduk`.`jenis_kelahiran` AS `jenis_kelahiran`,`tweb_penduduk`.`kelahiran_anak_ke` AS `kelahiran_anak_ke`,`tweb_penduduk`.`penolong_kelahiran` AS `penolong_kelahiran`,`tweb_penduduk`.`berat_lahir` AS `berat_lahir`,`tweb_penduduk`.`panjang_lahir` AS `panjang_lahir`,`tweb_penduduk`.`tag_id_card` AS `tag_id_card`,`tweb_penduduk`.`created_at` AS `created_at`,`tweb_penduduk`.`created_by` AS `created_by`,`tweb_penduduk`.`updated_at` AS `updated_at`,`tweb_penduduk`.`updated_by` AS `updated_by`,`tweb_penduduk`.`id_asuransi` AS `id_asuransi`,`tweb_penduduk`.`no_asuransi` AS `no_asuransi`,`tweb_penduduk`.`email` AS `email`,`tweb_penduduk`.`email_token` AS `email_token`,`tweb_penduduk`.`email_tgl_kadaluarsa` AS `email_tgl_kadaluarsa`,`tweb_penduduk`.`email_tgl_verifikasi` AS `email_tgl_verifikasi`,`tweb_penduduk`.`telegram` AS `telegram`,`tweb_penduduk`.`telegram_token` AS `telegram_token`,`tweb_penduduk`.`telegram_tgl_kadaluarsa` AS `telegram_tgl_kadaluarsa`,`tweb_penduduk`.`telegram_tgl_verifikasi` AS `telegram_tgl_verifikasi`,`tweb_penduduk`.`bahasa_id` AS `bahasa_id`,`tweb_penduduk`.`ket` AS `ket`,`tweb_penduduk`.`negara_asal` AS `negara_asal`,`tweb_penduduk`.`tempat_cetak_ktp` AS `tempat_cetak_ktp`,`tweb_penduduk`.`tanggal_cetak_ktp` AS `tanggal_cetak_ktp`,`tweb_penduduk`.`suku` AS `suku`,`tweb_penduduk`.`bpjs_ketenagakerjaan` AS `bpjs_ketenagakerjaan`,`tweb_penduduk`.`hubung_warga` AS `hubung_warga` from `tweb_penduduk` where `tweb_penduduk`.`status_dasar` = 1');

        // Keluarga Aktif
        DB::statement('CREATE OR REPLACE VIEW `keluarga_aktif` AS select `k`.`id` AS `id`,`k`.`config_id` AS `config_id`,`k`.`no_kk` AS `no_kk`,`k`.`nik_kepala` AS `nik_kepala`,`k`.`tgl_daftar` AS `tgl_daftar`,`k`.`kelas_sosial` AS `kelas_sosial`,`k`.`tgl_cetak_kk` AS `tgl_cetak_kk`,`k`.`alamat` AS `alamat`,`k`.`id_cluster` AS `id_cluster`,`k`.`updated_at` AS `updated_at`,`k`.`updated_by` AS `updated_by` from (`tweb_keluarga` `k` left join `tweb_penduduk` `p` on(`k`.`nik_kepala` = `p`.`id`)) where `p`.`status_dasar` = 1');

        // Master Inventaris
        DB::statement("CREATE OR REPLACE VIEW `master_inventaris` AS select 'inventaris_asset' AS `asset`,`inventaris_asset`.`config_id` AS `config_id`,`inventaris_asset`.`id` AS `id`,`inventaris_asset`.`nama_barang` AS `nama_barang`,`inventaris_asset`.`kode_barang` AS `kode_barang`,'Baik' AS `kondisi`,`inventaris_asset`.`keterangan` AS `keterangan`,`inventaris_asset`.`asal` AS `asal`,`inventaris_asset`.`tahun_pengadaan` AS `tahun_pengadaan` from `inventaris_asset` where `inventaris_asset`.`visible` = 1 union all select 'inventaris_gedung' AS `asset`,`inventaris_gedung`.`config_id` AS `config_id`,`inventaris_gedung`.`id` AS `id`,`inventaris_gedung`.`nama_barang` AS `nama_barang`,`inventaris_gedung`.`kode_barang` AS `kode_barang`,`inventaris_gedung`.`kondisi_bangunan` AS `kondisi_bangunan`,`inventaris_gedung`.`keterangan` AS `keterangan`,`inventaris_gedung`.`asal` AS `asal`,year(`inventaris_gedung`.`tanggal_dokument`) AS `tahun_pengadaan` from `inventaris_gedung` where `inventaris_gedung`.`visible` = 1 union all select 'inventaris_jalan' AS `asset`,`inventaris_jalan`.`config_id` AS `config_id`,`inventaris_jalan`.`id` AS `id`,`inventaris_jalan`.`nama_barang` AS `nama_barang`,`inventaris_jalan`.`kode_barang` AS `kode_barang`,`inventaris_jalan`.`kondisi` AS `kondisi`,`inventaris_jalan`.`keterangan` AS `keterangan`,`inventaris_jalan`.`asal` AS `asal`,year(`inventaris_jalan`.`tanggal_dokument`) AS `tahun_pengadaan` from `inventaris_jalan` where `inventaris_jalan`.`visible` = 1 union all select 'inventaris_peralatan' AS `asset`,`inventaris_peralatan`.`config_id` AS `config_id`,`inventaris_peralatan`.`id` AS `id`,`inventaris_peralatan`.`nama_barang` AS `nama_barang`,`inventaris_peralatan`.`kode_barang` AS `kode_barang`,'Baik' AS `Baik`,`inventaris_peralatan`.`keterangan` AS `keterangan`,`inventaris_peralatan`.`asal` AS `asal`,`inventaris_peralatan`.`tahun_pengadaan` AS `tahun_pengadaan` from `inventaris_peralatan` where `inventaris_peralatan`.`visible` = 1");

        // Rekap Mutasi Inventaris
        DB::statement("CREATE OR REPLACE VIEW `rekap_mutasi_inventaris` AS select 'inventaris_asset' AS `asset`,`mutasi_inventaris_asset`.`config_id` AS `config_id`,`mutasi_inventaris_asset`.`id_inventaris_asset` AS `id_inventaris_asset`,`mutasi_inventaris_asset`.`status_mutasi` AS `status_mutasi`,`mutasi_inventaris_asset`.`jenis_mutasi` AS `jenis_mutasi`,`mutasi_inventaris_asset`.`tahun_mutasi` AS `tahun_mutasi`,`mutasi_inventaris_asset`.`keterangan` AS `keterangan` from `mutasi_inventaris_asset` where `mutasi_inventaris_asset`.`visible` = 1 union all select 'inventaris_gedung' AS `inventaris_gedung`,`mutasi_inventaris_gedung`.`config_id` AS `config_id`,`mutasi_inventaris_gedung`.`id_inventaris_gedung` AS `id_inventaris_gedung`,`mutasi_inventaris_gedung`.`status_mutasi` AS `status_mutasi`,`mutasi_inventaris_gedung`.`jenis_mutasi` AS `jenis_mutasi`,`mutasi_inventaris_gedung`.`tahun_mutasi` AS `tahun_mutasi`,`mutasi_inventaris_gedung`.`keterangan` AS `keterangan` from `mutasi_inventaris_gedung` where `mutasi_inventaris_gedung`.`visible` = 1 union all select 'inventaris_jalan' AS `inventaris_jalan`,`mutasi_inventaris_jalan`.`config_id` AS `config_id`,`mutasi_inventaris_jalan`.`id_inventaris_jalan` AS `id_inventaris_jalan`,`mutasi_inventaris_jalan`.`status_mutasi` AS `status_mutasi`,`mutasi_inventaris_jalan`.`jenis_mutasi` AS `jenis_mutasi`,`mutasi_inventaris_jalan`.`tahun_mutasi` AS `tahun_mutasi`,`mutasi_inventaris_jalan`.`keterangan` AS `keterangan` from `mutasi_inventaris_jalan` where `mutasi_inventaris_jalan`.`visible` = 1 union all select 'inventaris_peralatan' AS `inventaris_peralatan`,`mutasi_inventaris_peralatan`.`config_id` AS `config_id`,`mutasi_inventaris_peralatan`.`id_inventaris_peralatan` AS `id_inventaris_peralatan`,`mutasi_inventaris_peralatan`.`status_mutasi` AS `status_mutasi`,`mutasi_inventaris_peralatan`.`jenis_mutasi` AS `jenis_mutasi`,`mutasi_inventaris_peralatan`.`tahun_mutasi` AS `tahun_mutasi`,`mutasi_inventaris_peralatan`.`keterangan` AS `keterangan` from `mutasi_inventaris_peralatan` where `mutasi_inventaris_peralatan`.`visible` = 1");

        // Dokumen Hidup
        DB::statement('CREATE OR REPLACE VIEW `dokumen_hidup` AS select `dokumen`.`id` AS `id`,`dokumen`.`config_id` AS `config_id`,`dokumen`.`satuan` AS `satuan`,`dokumen`.`nama` AS `nama`,`dokumen`.`enabled` AS `enabled`,`dokumen`.`tgl_upload` AS `tgl_upload`,`dokumen`.`id_pend` AS `id_pend`,`dokumen`.`kategori` AS `kategori`,`dokumen`.`attr` AS `attr`,`dokumen`.`tipe` AS `tipe`,`dokumen`.`url` AS `url`,`dokumen`.`tahun` AS `tahun`,`dokumen`.`kategori_info_publik` AS `kategori_info_publik`,`dokumen`.`updated_at` AS `updated_at`,`dokumen`.`deleted` AS `deleted`,`dokumen`.`id_syarat` AS `id_syarat`,`dokumen`.`id_parent` AS `id_parent`,`dokumen`.`created_at` AS `created_at`,`dokumen`.`created_by` AS `created_by`,`dokumen`.`updated_by` AS `updated_by`,`dokumen`.`dok_warga` AS `dok_warga`,`dokumen`.`lokasi_arsip` AS `lokasi_arsip` from `dokumen` where (`dokumen`.`deleted` <> 1)');
    }

    // Penyesuaian data awal dari versi 2312.0.3
    protected function penyesuaian_data_awal()
    {
        // Tgl Data Lengkap
        DB::table('setting_aplikasi')->where('key', 'tgl_data_lengkap')->delete();

        // ID Pengunjung Kehadiran
        DB::table('setting_aplikasi')->where('key', 'id_pengunjung_kehadiran')->update(['judul' => 'ID Pengunjung Kehadiran']);
    }
}
