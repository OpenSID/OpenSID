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

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dtks_anggota', static function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('config_id')->nullable()->index('dtks_anggota_config_fk');
            $table->integer('id_dtks')->nullable()->index('FK_dtks_dtks_anggota');
            $table->integer('id_penduduk')->nullable()->index('FK_pend_dtks_anggota');
            $table->integer('id_keluarga')->nullable()->index('FK_kel_dtks_anggota');
            $table->timestamp('created_at')->nullable()->useCurrent();
            $table->timestamp('updated_at')->useCurrentOnUpdate()->nullable()->useCurrent();
            $table->string('kd_ket_keberadaan_art', 2)->nullable();
            $table->string('bulan_meninggal', 2)->nullable();
            $table->year('tahun_meninggal')->nullable();
            $table->string('kd_punya_akta_meniggal', 2)->nullable();
            $table->string('bulan_pindah_tempat', 2)->nullable();
            $table->year('tahun_pindah_tempat')->nullable();
            $table->string('kd_tempat_tinggal_saat_ini', 2)->nullable();
            $table->string('bulan_masuk_ruta', 2)->nullable();
            $table->year('tahun_masuk_ruta')->nullable();
            $table->string('kd_alasan_masuk_ruta', 2)->nullable();
            $table->string('kd_hubungan_dg_krt', 2)->nullable();
            $table->string('kd_hubungan_dg_kk', 2)->nullable();
            $table->string('kd_jenis_kelamin', 2)->nullable();
            $table->string('kd_punya_aktanikah_cerai', 2)->nullable();
            $table->string('kd_punya_kartuid', 2)->nullable();
            $table->string('kd_sulit_penglihatan', 2)->nullable();
            $table->string('kd_sulit_pendengaran', 2)->nullable();
            $table->string('kd_sulit_jalan_naiktangga', 2)->nullable();
            $table->string('kd_sulit_gerak_tangan_jari', 2)->nullable();
            $table->string('kd_sulit_belajar_intelektual', 2)->nullable();
            $table->string('kd_sulit_ingat_konsentrasi', 2)->nullable();
            $table->string('kd_sulit_perilaku_emosi', 2)->nullable();
            $table->string('kd_sulit_paham_bicara_kom', 2)->nullable();
            $table->string('kd_sulit_mandiri', 2)->nullable();
            $table->string('kd_sering_sedih_depresi', 2)->nullable();
            $table->string('kd_memiliki_perawat', 2)->nullable();
            $table->string('kd_merokok_sebulan_akhir', 2)->nullable();
            $table->string('kd_penyakit_kronis_menahun', 2)->nullable();
            $table->string('kd_partisipasi_sekolah', 2)->nullable();
            $table->string('kd_pendidikan_tertinggi', 2)->nullable();
            $table->string('kd_kelas_tertinggi', 2)->nullable();
            $table->string('kd_ijazah_tertinggi', 2)->nullable();
            $table->string('kd_bekerja_seminggu_lalu', 2)->nullable();
            $table->string('jumlah_jam_kerja_seminggu_lalu', 2)->nullable();
            $table->bigInteger('pendapatan_sebulan_terakhir')->nullable();
            $table->string('kd_punya_npwp', 2)->nullable();
            $table->string('npwp', 15)->nullable();
            $table->string('kd_lapangan_usaha_pekerjaan', 2)->nullable();
            $table->string('kd_kedudukan_di_pekerjaan', 2)->nullable();
            $table->string('kd_gizi_seimbang', 2)->nullable();
            $table->string('kd_imunasasi_lengkap', 2)->nullable();
            $table->string('kd_bantuan_pempus', 2)->nullable();
            $table->string('kd_bantuan_pemkot', 2)->nullable();
            $table->string('kd_bantuan_pemdes', 2)->nullable();
            $table->string('kd_jamkes_setahun', 2)->nullable();
            $table->string('kd_ikut_pbijkn_bpjssehat', 2)->nullable();
            $table->string('kd_ikut_bpjssehat_nonpbi', 2)->nullable();
            $table->string('kd_ikut_jamsostek_bpjsk', 2)->nullable();
            $table->string('kd_ikut_pip', 2)->nullable();
            $table->string('kd_ikut_prakerja', 2)->nullable();
            $table->string('kd_ikut_kur', 2)->nullable();
            $table->string('kd_ikut_umi', 2)->nullable();
            $table->string('jumlah_jamket_kerja', 2)->nullable();
            $table->boolean('is_usaha_sendiri_bersama')->default(false);
            $table->string('kd_punya_usaha_sendiri_bersama', 2)->nullable();
            $table->tinyInteger('jumlah_usaha_sendiri_bersama')->nullable();
            $table->string('kd_lapangan_usaha_dr_usaha', 2)->nullable();
            $table->string('tulis_lapangan_usaha_dr_usaha', 191)->default('');
            $table->string('tulis_lapangan_usaha_pekerjaan', 191)->default('');
            $table->tinyInteger('jumlah_pekerja_dibayar')->nullable();
            $table->tinyInteger('jumlah_pekerja_tidak_dibayar')->nullable();
            $table->string('kd_kepemilikan_ijin_usaha', 2)->nullable();
            $table->string('kd_omset_usaha_perbulan', 2)->nullable();
            $table->string('kd_guna_internet_usaha', 2)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dtks_anggota');
    }
};
