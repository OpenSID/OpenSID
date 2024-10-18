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

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

defined('BASEPATH') || exit('No direct script access allowed');

class Migrasi_2024010171 extends MY_model
{
    public function up()
    {
        $hasil = true;
        $hasil = $hasil && $this->migrasi_tabel($hasil);

        return $hasil && $this->migrasi_data($hasil);
    }

    protected function migrasi_tabel($hasil)
    {
        $hasil = $hasil && $this->migrasi_2023120752($hasil);
        $hasil = $hasil && $this->migrasi_2023120351($hasil);
        $hasil = $hasil && $this->migrasi_2023121951($hasil);
        $hasil = $hasil && $this->migrasi_2023122751($hasil);

        return $hasil && $this->migrasi_2023121971($hasil);
    }

    // Migrasi perubahan data
    protected function migrasi_data($hasil)
    {
        // Migrasi berdasarkan config_id
        $config_id = DB::table('config')->pluck('id')->toArray();

        foreach ($config_id as $id) {
            $hasil = $hasil && $this->migrasi_2023120554($hasil, $id);
            $hasil = $hasil && $this->migrasi_2023122871($hasil, $id);
        }

        $hasil = $hasil && $this->migrasi_2023120751($hasil);

        return $hasil && $this->migrasi_2023120553($hasil);
    }

    protected function migrasi_2023120351($hasil)
    {
        $this->tambahIndeks('klasifikasi_surat', 'config_id, kode', 'UNIQUE', true);

        return $hasil;
    }

    protected function migrasi_2023121951($hasil)
    {
        if (! Schema::hasTable('pemilihan')) {
            Schema::create('pemilihan', static function (Blueprint $table) {
                $table->uuid('uuid')->primary();
                $table->integer('config_id');
                $table->string('judul', 100);
                $table->date('tanggal');
                $table->integer('status')->default(0);
                $table->text('keterangan');
                $table->timestamps();
                $table->integer('created_by')->nullable();
                $table->integer('updated_by')->nullable();
                $table->unique(['uuid', 'config_id']);
                $table->foreign('config_id')->references('id')->on('config')->onUpdate('cascade')->onDelete('cascade');
            });
        }

        return $hasil;
    }

    protected function migrasi_2023121971($hasil)
    {
        if ($this->db->field_exists('gambar', 'gambar_gallery')) {
            $hasil = $hasil && $this->dbforge->modify_column('gambar_gallery', [
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

        return $hasil;
    }

    protected function migrasi_2023122751($hasil)
    {
        try {
            // analisis master
            $hasil = $hasil && $this->tambahForeignKey('analisis_master_subjek_fk', 'analisis_master', 'subjek_tipe', 'analisis_ref_subjek', 'id', true);

            // analisis_indikator
            $hasil = $hasil && $this->tambahForeignKey('analisis_indikator_master_fk', 'analisis_indikator', 'id_master', 'analisis_master', 'id', true);
            $hasil = $hasil && $this->tambahForeignKey('analisis_indikator_id_kategori_fk', 'analisis_indikator', 'id_kategori', 'analisis_kategori_indikator', 'id', true);
            $hasil = $hasil && $this->tambahForeignKey('analisis_indikator_tipe_fk', 'analisis_indikator', 'id_tipe', 'analisis_tipe_indikator', 'id', true);

            // analisis_kategori_indikator
            $hasil = $hasil && $this->tambahForeignKey('analisis_kategori_indikator_master_fk', 'analisis_kategori_indikator', 'id_master', 'analisis_master', 'id', true);

            // analisis_klasifikasi
            $hasil = $hasil && $this->tambahForeignKey('analisis_klasifikasi_master_fk', 'analisis_klasifikasi', 'id_master', 'analisis_master', 'id', true);

            // analisis_parameter
            $hasil = $hasil && $this->tambahForeignKey('analisis_parameter_indikator_fk', 'analisis_parameter', 'id_indikator', 'analisis_indikator', 'id', true);

            // analisis_partisipasi
            $hasil = $hasil && $this->tambahForeignKey('analisis_partisipasi_subjek_fk', 'analisis_partisipasi', 'id_subjek', 'analisis_parameter', 'id', true);
            $hasil = $hasil && $this->tambahForeignKey('analisis_partisipasi_master_fk', 'analisis_partisipasi', 'id_master', 'analisis_master', 'id', true);
            $hasil = $hasil && $this->tambahForeignKey('analisis_partisipasi_periode_fk', 'analisis_partisipasi', 'id_periode', 'analisis_periode', 'id', true);
            $hasil = $hasil && $this->tambahForeignKey('analisis_partisipasi_klasifikasi_fk', 'analisis_partisipasi', 'id_klassifikasi', 'analisis_klasifikasi', 'id', true);

            // analisis_periode
            $hasil = $hasil && $this->tambahForeignKey('analisis_periode_master_fk', 'analisis_periode', 'id_master', 'analisis_master', 'id', true);
            $hasil = $hasil && $this->tambahForeignKey('state_fk', 'analisis_periode', 'id_state', 'analisis_ref_state', 'id', true);

            // analisis_respon
            $hasil = $hasil && $this->tambahForeignKey('analisis_respon_indikator_fk', 'analisis_respon', 'id_indikator', 'analisis_indikator', 'id', true);
            $hasil = $hasil && $this->tambahForeignKey('analisis_respon_parameter_fk', 'analisis_respon', 'id_parameter', 'analisis_parameter', 'id', true);
            $hasil = $hasil && $this->tambahForeignKey('analisis_respon_subjek_fk', 'analisis_respon', 'id_subjek', 'analisis_parameter', 'id', true);
            $hasil = $hasil && $this->tambahForeignKey('analisis_respon_periode_fk', 'analisis_respon', 'id_periode', 'analisis_periode', 'id', true);

            // analisis_respon_bukti
            $hasil = $hasil && $this->tambahForeignKey('analisis_respon_bukti_master_fk', 'analisis_respon_bukti', 'id_master', 'analisis_master', 'id', true);
            $hasil = $hasil && $this->tambahForeignKey('analisis_respon_bukti_periode_fk', 'analisis_respon_bukti', 'id_periode', 'analisis_periode', 'id', true);
            $hasil = $hasil && $this->tambahForeignKey('analisis_respon_bukti_subjek_fk', 'analisis_respon_bukti', 'id_subjek', 'analisis_ref_subjek', 'id', true);

            // analisis_respon_hasil
            $hasil = $hasil && $this->tambahForeignKey('analisis_respon_hasil_subjek_fk', 'analisis_respon_hasil', 'id_subjek', 'analisis_parameter', 'id', true);
            $hasil = $hasil && $this->tambahForeignKey('analisis_respon_hasil_master_fk', 'analisis_respon_hasil', 'id_master', 'analisis_master', 'id', true);
            $hasil = $hasil && $this->tambahForeignKey('analisis_respon_hasil_periode_fk', 'analisis_respon_hasil', 'id_periode', 'analisis_periode', 'id', true);

            // area
            $hasil = $hasil && $this->tambahForeignKey('area_cluster_fk', 'area', 'id_cluster', 'tweb_wil_clusterdesa', 'id', true);

            // artikel
            // untuk artikel, datanya harus disesuaikan dulu misalnya id_kategori 999
            DB::statement('alter table `artikel` modify column `id_kategori` int(11) NULL');

            if (! Schema::hasColumn('artikel', 'tipe')) {
                DB::statement('alter table artikel add column tipe varchar(50) NOT NULL default "dinamis"');
                DB::statement('update artikel set tipe = "statis", id_kategori = null where id_kategori = 999');
                DB::statement('update artikel set tipe = "agenda", id_kategori = null where id_kategori = 1000');
                DB::statement('update artikel set tipe = "keuangan", id_kategori = null where id_kategori = 1001');
            }

            $hasil = $hasil && $this->tambahForeignKey('artikel_kategori_fk', 'artikel', 'id_kategori', 'kategori', 'id', true);
            $hasil = $hasil && $this->tambahForeignKey('artikel_kategori_id_user_fk', 'artikel', 'id_user', 'user', 'id', true);

            // buku_kepuasan
            $hasil = $hasil && $this->tambahForeignKey('buku_kepuasan_nama_fk', 'buku_kepuasan', 'id_nama', 'buku_tamu', 'id', true);
            $hasil = $hasil && $this->tambahForeignKey('buku_kepuasan_pertanyaan_fk', 'buku_kepuasan', 'id_pertanyaan', 'buku_pertanyaan', 'id', true);

            // bulanan anak
            $hasil = $hasil && DB::statement('ALTER TABLE `posyandu` CHANGE COLUMN `id` `id` INT(11) NOT NULL AUTO_INCREMENT');
            $hasil = $hasil && DB::statement('ALTER TABLE `kia` CHANGE COLUMN `id` `id` INT(11) NOT NULL AUTO_INCREMENT');
            $hasil = $hasil && $this->tambahForeignKey('bulanan_anak_posyandu_fk', 'bulanan_anak', 'posyandu_id', 'posyandu', 'id', true);
            $hasil = $hasil && $this->tambahForeignKey('bulanan_anak_kia_fk', 'bulanan_anak', 'kia_id', 'kia', 'id', true);

            // cdesa_penduduk
            $hasil = $hasil && $this->tambahForeignKey('cdesa_penduduk_pend_fk', 'cdesa_penduduk', 'id_pend', 'tweb_penduduk', 'id', true);

            // fcm_token
            $hasil = $hasil && $this->tambahForeignKey('fcm_token_dd_user_fk', 'fcm_token', 'id_user', 'user', 'id', true);
            $hasil = $hasil && $this->tambahForeignKey('fcm_token_config_fk', 'fcm_token', 'config_id', 'config', 'id', true);

            // fcm_token_mandiri
            $hasil = $hasil && $this->tambahForeignKey('fcm_token_mandiri_config_fk', 'fcm_token_mandiri', 'config_id', 'config', 'id', true);
            $hasil = $hasil && $this->tambahForeignKey('fcm_token_mandiri_user_mandiri_fk', 'fcm_token_mandiri', 'id_user_mandiri', 'tweb_penduduk_mandiri', 'id_pend');

            // garis
            $hasil = $hasil && $this->tambahForeignKey('garis_cluster_fk', 'garis', 'id_cluster', 'tweb_wil_clusterdesa', 'id', true);

            // ibu_hamil
            $hasil = $hasil && $this->tambahForeignKey('ibu_hamil_posyandu_fk', 'ibu_hamil', 'posyandu_id', 'posyandu', 'id', true);
            $hasil = $hasil && $this->tambahForeignKey('ibu_hamil_kia_fk', 'ibu_hamil', 'kia_id', 'kia', 'id', true);

            // tweb_desa_pamong
            // untuk tipe data pamong_id pada tweb_desa_pamong ubah menjadi int(11) sama seperti table lainnya, agar tidak perlu ubah tipe data pada tabel lain yang merujuk pada kolom id tersebut
            // tambahkan relasi ke tabel tweb_penduduk pada kolom id_pend
            $hasil = $hasil && $this->tambahForeignKey('tweb_desa_pamong_pend_fk', 'tweb_desa_pamong', 'id_pend', 'tweb_penduduk', 'id', true);

            // kader_pemberdayaan_masyarakat
            $hasil = $hasil && $this->tambahForeignKey('kader_pemberdayaan_masyarakat_penduduk_fk', 'kader_pemberdayaan_masyarakat', 'penduduk_id', 'tweb_penduduk', 'id', true);

            // kehadiran_pengaduan
            $hasil = $hasil && $this->tambahForeignKey('kehadiran_pengaduan_penduduk_fk', 'kehadiran_pengaduan', 'id_penduduk', 'tweb_penduduk', 'id', true);
            $hasil = $hasil && $this->tambahForeignKey('kehadiran_pengaduan_pamong_fk', 'kehadiran_pengaduan', 'id_pamong', 'tweb_desa_pamong', 'pamong_id');

            // kehadiran_perangkat_desa
            $hasil = $hasil && $this->tambahForeignKey('kehadiran_perangkat_desa_pamong_fk', 'kehadiran_perangkat_desa', 'pamong_id', 'tweb_desa_pamong', 'pamong_id');

            // kelompok
            $hasil = $hasil && $this->tambahForeignKey('kelompok_ketua_fk', 'kelompok', 'id_ketua', 'tweb_penduduk', 'id', true);

            // kia
            $hasil = $hasil && $this->tambahForeignKey('kia_ibu_fk', 'kia', 'ibu_id', 'tweb_penduduk', 'id', true);
            $hasil = $hasil && $this->tambahForeignKey('kia_anak_fk', 'kia', 'anak_id', 'tweb_penduduk', 'id', true);

            // komentar
            DB::statement('alter table `komentar` modify column `id_artikel` int(11) NULL');

            if (! Schema::hasColumn('komentar', 'jenis')) {
                DB::statement('alter table komentar add column jenis varchar(50) NULL default NULL');
                DB::statement('update komentar set jenis = "pesan-mandiri", id_artikel = null where id_artikel = 775');
            }
            $hasil = $hasil && $this->tambahForeignKey('komentar_artikel_fk', 'komentar', 'id_artikel', 'artikel', 'id', true);

            // log_hapus_penduduk
            $hasil = $hasil && $this->tambahForeignKey('log_hapus_penduduk_pend_fk', 'log_hapus_penduduk', 'id_pend', 'tweb_penduduk', 'id', true);

            // tweb_penduduk_map
            $hasil = $hasil && $this->tambahForeignKey('tweb_penduduk_map_pend_fk', 'tweb_penduduk_map', 'id', 'tweb_penduduk', 'id', true);

            // log_keluarga
            $hasil = $hasil && $this->tambahForeignKey('log_keluarga_pend_fk', 'log_keluarga', 'id_pend', 'tweb_penduduk', 'id', true);
            $hasil = $hasil && $this->tambahForeignKey('log_keluarga_kk_fk', 'log_keluarga', 'id_kk', 'tweb_keluarga', 'id', true);

            // log_perubahan_penduduk
            $hasil = $hasil && $this->tambahForeignKey('log_perubahan_penduduk_pend_fk', 'log_perubahan_penduduk', 'id_pend', 'tweb_penduduk', 'id', true);
            $hasil = $hasil && $this->tambahForeignKey('log_perubahan_penduduk_cluster_fk', 'log_perubahan_penduduk', 'id_cluster', 'tweb_wil_clusterdesa', 'id', true);

            // log_surat
            $hasil = $hasil && $this->tambahForeignKey('log_surat_format_surat_fk', 'log_surat', 'id_format_surat', 'tweb_surat_format', 'id', true);
            $hasil = $hasil && $this->tambahForeignKey('log_surat_pend_fk', 'log_surat', 'id_pend', 'tweb_penduduk', 'id', true);
            $hasil = $hasil && $this->tambahForeignKey('log_surat_pamong_fk', 'log_surat', 'id_pamong', 'tweb_desa_pamong', 'pamong_id');
            $hasil = $hasil && $this->tambahForeignKey('log_surat_pamong_urls_fk', 'log_surat', 'urls_id', 'urls', 'id');
            $hasil = $hasil && $this->tambahForeignKey('log_surat_user_fk', 'log_surat', 'id_user', 'user', 'id', true);

            // log_tolak
            $hasil = $hasil && $this->tambahForeignKey('log_tolak_surat_fk', 'log_tolak', 'id_surat', 'log_surat', 'id', true);

            // lokasi
            $hasil = $hasil && $this->tambahForeignKey('lokasi_cluster_fk', 'lokasi', 'id_cluster', 'tweb_wil_clusterdesa', 'id', true);
            $hasil = $hasil && $this->tambahForeignKey('lokasi_point_fk', 'lokasi', 'ref_point', 'point', 'id', true);

            // cek lagi, apakah benar id_peta reference ke lokasi ?
            // mutasi_cdesa
            $hasil = $hasil && $this->tambahForeignKey('mutasi_cdesa_peta_fk', 'mutasi_cdesa', 'id_peta', 'lokasi', 'id', true);

            // pelapak
            $hasil = $hasil && $this->tambahForeignKey('pelapak_pend_fk', 'pelapak', 'id_pend', 'tweb_penduduk', 'id', true);

            // pembangunan
            // $hasil = $hasil && $this->tambahForeignKey('pembangunan_lokasi_fk', 'pembangunan', 'id_lokasi', 'tweb_wil_clusterdesa', 'id', true);

            // pembangunan_ref_dokumentasi
            $hasil = $hasil && $this->tambahForeignKey('pembangunan_ref_dokumentasi_pembangunan_fk', 'pembangunan_ref_dokumentasi', 'id_pembangunan', 'pembangunan', 'id', true);

            // permohonan_surat
            $hasil = $hasil && $this->tambahForeignKey('permohonan_surat_pemohon_fk', 'permohonan_surat', 'id_pemohon', 'tweb_penduduk', 'id', true);
            $hasil = $hasil && $this->tambahForeignKey('permohonan_surat_surat_fk', 'permohonan_surat', 'id_surat', 'tweb_surat_format', 'id', true);

            // persil
            $hasil = $hasil && $this->tambahForeignKey('persil_wilayah_fk', 'persil', 'id_wilayah', 'tweb_wil_clusterdesa', 'id', true);

            // cek lagi, apakah benar id_peta reference ke lokasi ?
            // persil
            $hasil = $hasil && $this->tambahForeignKey('persil_peta_fk', 'persil', 'id_peta', 'lokasi', 'id', true);

            // pesan_detail
            $hasil = $hasil && DB::statement('ALTER TABLE `pesan` CHANGE COLUMN `id` `id` INT(11) NOT NULL AUTO_INCREMENT');
            $hasil = $hasil && $this->tambahForeignKey('pesan_detail_pesan_fk', 'pesan_detail', 'pesan_id', 'pesan', 'id', true);

            // program_peserta
            $hasil = $hasil && $this->tambahForeignKey('program_peserta_program_fk', 'program_peserta', 'program_id', 'program', 'id', true);
            $hasil = $hasil && $this->tambahForeignKey('program_peserta_kartu_fk', 'program_peserta', 'kartu_id_pend', 'tweb_penduduk', 'id', true);

            // sasaran_paud
            $hasil = $hasil && $this->tambahForeignKey('sasaran_paud_posyandu_fk', 'sasaran_paud', 'posyandu_id', 'posyandu', 'id', true);
            $hasil = $hasil && $this->tambahForeignKey('sasaran_paud_kia_fk', 'sasaran_paud', 'kia_id', 'kia', 'id', true);

            // tanah_desa
            $hasil = $hasil && $this->tambahForeignKey('tanah_desa_penduduk_fk', 'tanah_desa', 'id_penduduk', 'tweb_penduduk', 'id', true);

            // tweb_desa_pamong
            $hasil = $hasil && $this->tambahForeignKey('tweb_desa_pamong_jabatan_fk', 'tweb_desa_pamong', 'jabatan_id', 'ref_jabatan', 'id', true);

            // tweb_keluarga
            $hasil = $hasil && $this->tambahForeignKey('tweb_keluarga_kepala_fk', 'tweb_keluarga', 'nik_kepala', 'tweb_penduduk', 'id', true);
            $hasil = $hasil && $this->tambahForeignKey('tweb_keluarga_cluster_fk', 'tweb_keluarga', 'id_cluster', 'tweb_wil_clusterdesa', 'id', true);

            // tweb_penduduk
            // $hasil = $hasil && DB::statement('alter table tweb_penduduk modify column id_rtm int(11)');
            $hasil = $hasil && $this->tambahForeignKey('tweb_penduduk_kk_fk', 'tweb_penduduk', 'id_kk', 'tweb_keluarga', 'id', true);
            // id_rtm yang disimpan adalah no_kk pada table tweb_rtm, bukan id-nya
            // $hasil = $hasil && $this->tambahForeignKey('tweb_penduduk_rtm_fk', 'tweb_penduduk', 'id_rtm','tweb_rtm', 'id', true);
            $hasil = $hasil && $this->tambahForeignKey('tweb_penduduk_cluster_fk', 'tweb_penduduk', 'id_cluster', 'tweb_wil_clusterdesa', 'id', true);

            // rtm
            $hasil = $hasil && $this->tambahForeignKey('tweb_rtm_kepala_fk', 'tweb_rtm', 'nik_kepala', 'tweb_penduduk', 'id', true);

            // tweb_wil_clusterdesa
            $hasil = $hasil && $this->tambahForeignKey('tweb_wil_clusterdesa_kepala_fk', 'tweb_wil_clusterdesa', 'id_kepala', 'tweb_penduduk', 'id', true);

            // user
            $hasil = $hasil && DB::statement('ALTER TABLE `user` CHANGE COLUMN `id` `id` INT(11) NOT NULL AUTO_INCREMENT');
            $hasil = $hasil && $this->tambahForeignKey('user_grup_fk', 'user', 'id_grup', 'user_grup', 'id', true);
            $hasil = $hasil && $this->tambahForeignKey('user_pamong_fk', 'user', 'pamong_id', 'tweb_desa_pamong', 'pamong_id');

            // alias_kodeisian
            $hasil = $hasil && $this->hapus_foreign_key('config', 'alias_kodeisian_config_id_foreign', 'alias_kodeisian');
            $hasil = $hasil && $this->tambahForeignKey('alias_kodeisian_config_fk', 'alias_kodeisian', 'config_id', 'config', 'id', true);

            // suplemen_terdata
            $hasil = $hasil && $this->hapus_foreign_key('suplemen', 'suplemen_terdata_ibfk_1', 'suplemen_terdata');
            $hasil = $hasil && $this->tambahForeignKey('suplemen_terdata_suplemen_1', 'suplemen_terdata', 'id_suplemen', 'suplemen', 'id', true);

            // log_notifikasi_admin
            $hasil = $hasil && $this->tambahForeignKey('log_notifikasi_admin_config_fk', 'log_notifikasi_admin', 'config_id', 'config', 'id', true);
            $hasil = $hasil && $this->tambahForeignKey('log_notifikasi_admin_user_fk', 'log_notifikasi_admin', 'id_user', 'user', 'id', true);

            // log_notifikasi_mandiri
            $hasil = $hasil && DB::statement('ALTER TABLE `log_notifikasi_mandiri` CHANGE COLUMN `id_user_mandiri` `id_user_mandiri` INT(11) NULL DEFAULT NULL');
            $hasil = $hasil && $this->tambahForeignKey('log_notifikasi_mandiri_config_fk', 'log_notifikasi_mandiri', 'config_id', 'config', 'id', true);
            $hasil = $hasil && $this->tambahForeignKey('log_notifikasi_mandiri_user_mandiri_fk', 'log_notifikasi_mandiri', 'id_user_mandiri', 'tweb_penduduk_mandiri', 'id_pend');

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

        return $hasil;
    }

    protected function migrasi_2023120553($hasil)
    {
        DB::table('tweb_penduduk')->where('kk_level', 0)->update(['kk_level' => null]);

        return $hasil;
    }

    protected function migrasi_2023120554($hasil, $config_id)
    {
        return $hasil && $this->tambah_modul([
            'config_id'  => $config_id,
            'modul'      => 'Simbol',
            'slug'       => 'simbol',
            'url'        => 'simbol',
            'aktif'      => 1,
            'ikon'       => 'fa-location-arrow',
            'urut'       => 3,
            'level'      => 1,
            'hidden'     => 0,
            'ikon_kecil' => 'fa-location-arrow',
            'parent'     => $this->db->get_where('setting_modul', ['config_id' => $config_id, 'slug' => 'simbol'])->row()->id,
        ]);
    }

    protected function migrasi_2023120751($hasil)
    {
        $hasil = $hasil && $this->ubah_modul(
            ['slug' => 'data-suplemen', 'url' => 'suplemen/clear'],
            ['url' => 'suplemen']
        );

        $hasil = $hasil && $this->ubah_modul(
            ['slug' => 'wilayah-administratif', 'url' => 'wilayah/clear'],
            ['url' => 'wilayah']
        );

        $hasil = $hasil && $this->ubah_modul(
            ['slug' => 'pengunjung', 'url' => 'pengunjung/clear'],
            ['url' => 'pengunjung']
        );

        $hasil = $hasil && $this->ubah_modul(
            ['slug' => 'klasifikasi-surat', 'url' => 'klasifikasi/clear'],
            ['url' => 'klasifikasi']
        );

        $hasil = $hasil && $this->ubah_modul(
            ['slug' => 'polygon', 'url' => 'polygon/clear'],
            ['url' => 'polygon']
        );

        $hasil = $hasil && $this->ubah_modul(
            ['slug' => 'area', 'url' => 'area/clear'],
            ['url' => 'area']
        );

        $hasil = $hasil && $this->ubah_modul(
            ['slug' => 'garis', 'url' => 'garis/clear'],
            ['url' => 'garis']
        );

        $hasil = $hasil && $this->ubah_modul(
            ['slug' => 'widget', 'url' => 'web_widget/clear'],
            ['url' => 'web_widget']
        );

        $hasil = $hasil = $hasil && $this->ubah_modul(
            ['slug' => 'line', 'url' => 'line/clear'],
            ['url' => 'line']
        );

        $hasil = $hasil && $this->ubah_modul(
            ['slug' => 'point', 'url' => 'point/clear'],
            ['url' => 'point']
        );

        $hasil = $hasil && $this->ubah_modul(
            ['slug' => 'arsip-layanan', 'url' => 'keluar/clear/masuk'],
            ['url' => 'keluar/clear']
        );

        $hasil = $hasil && $this->ubah_modul(
            ['slug' => 'modul', 'url' => 'modul/clear'],
            ['url' => 'modul']
        );

        $hasil = $hasil && $this->ubah_modul(
            ['slug' => 'arsip-layanan', 'url' => 'keluar/clear'],
            ['url' => 'keluar']
        );

        return $hasil && $this->ubah_modul(
            ['slug' => 'calon-pemilih', 'url' => 'dpt/clear'],
            ['url' => 'dpt']
        );
    }

    protected function migrasi_2023122871($hasil, $id)
    {
        $hasil = $hasil && $this->tambah_setting([
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
        ], $id);

        $hasil = $hasil && $this->tambah_setting([
            'judul'      => 'Jumlah Gambar Slider',
            'key'        => 'jumlah_gambar_slider',
            'value'      => '10',
            'keterangan' => 'Jumlah Gambar Slider Yang di Tampilkan',
            'jenis'      => 'text',
            'option'     => null,
            'attribute'  => null,
            'kategori'   => 'artikel',
        ], $id);

        return $hasil && $this->tambah_setting([
            'judul'      => 'Tagline / Motto [desa]',
            'key'        => 'motto_desa',
            'value'      => '',
            'keterangan' => 'Tagline / Motto [desa]',
            'jenis'      => 'text',
            'attribute'  => null,
            'kategori'   => 'sistem',
        ], $id);
    }

    protected function migrasi_2023120752($hasil)
    {
        $this->db->query('ALTER TABLE config MODIFY path LONGTEXT DEFAULT NULL;');

        return $hasil;
    }
}
