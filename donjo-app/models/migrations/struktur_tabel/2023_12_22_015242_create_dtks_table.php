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
        Schema::create('dtks', static function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('config_id')->nullable()->index('dtks_config_fk');
            $table->boolean('is_draft')->default(true);
            $table->integer('id_rtm')->nullable()->index('FK_dtks_rtm');
            $table->integer('id_keluarga')->nullable()->index('FK_kel_dtks');
            $table->timestamp('created_at')->nullable()->useCurrent();
            $table->timestamp('updated_at')->useCurrentOnUpdate()->nullable()->useCurrent();
            $table->string('versi_kuisioner', 20)->nullable();
            $table->text('catatan')->nullable();
            $table->string('kode_provinsi', 100)->nullable();
            $table->string('kode_kabupaten', 100)->nullable();
            $table->string('kode_kecamatan', 100)->nullable();
            $table->string('kode_desa', 100)->nullable();
            $table->string('kode_sls_non_sls', 4)->nullable();
            $table->string('kode_sub_sls', 2)->nullable();
            $table->string('nama_sls_non_sls', 100)->nullable();
            $table->string('no_urut_bangunan_tinggal', 3)->nullable();
            $table->string('no_urut_keluarga_verif', 3)->nullable();
            $table->string('status_keluarga', 1)->nullable();
            $table->string('kode_landmark_wilkerstat', 6)->nullable();
            $table->string('kd_kk', 2)->nullable();
            $table->string('no_urut_ruta', 15)->nullable();
            $table->date('tanggal_pencacahan')->nullable();
            $table->string('nama_petugas_pencacahan', 100)->nullable();
            $table->string('kode_petugas_pencacahan', 5)->nullable();
            $table->date('tanggal_pemeriksaan')->nullable();
            $table->string('nama_pemeriksa', 100)->nullable();
            $table->string('kode_pemeriksa', 5)->nullable();
            $table->string('nama_responden', 100)->nullable();
            $table->string('kd_hasil_pencacahan_ruta', 2)->nullable();
            $table->date('tanggal_pendataan')->nullable();
            $table->string('nama_ppl', 100)->nullable();
            $table->string('kode_ppl', 4)->nullable();
            $table->string('nama_pml', 100)->nullable();
            $table->string('kode_pml', 3)->nullable();
            $table->string('kd_hasil_pendataan_keluarga', 2)->nullable();
            $table->string('no_hp_responden', 16)->nullable();
            $table->string('kd_stat_bangunan_tinggal', 2)->nullable();
            $table->string('kd_stat_lahan_tinggal', 2)->nullable();
            $table->string('kd_sertiv_lahan_milik', 2)->nullable();
            $table->integer('luas_lantai')->nullable();
            $table->string('kd_jenis_lantai_terluas', 2)->nullable();
            $table->string('kd_jenis_dinding', 2)->nullable();
            $table->string('kd_kondisi_dinding', 2)->nullable();
            $table->string('kd_jenis_atap', 2)->nullable();
            $table->string('kd_kondisi_atap', 2)->nullable();
            $table->string('jumlah_kamar_tidur', 2)->nullable();
            $table->string('kd_sumber_air_minum', 2)->nullable();
            $table->string('kd_jarak_sumber_air_ke_tpl', 2)->nullable();
            $table->string('kd_memperoleh_air_minum', 2)->nullable();
            $table->string('kd_sumber_penerangan_utama', 2)->nullable();
            $table->string('kd_daya_terpasang', 2)->nullable();
            $table->string('kd_daya_terpasang2', 2)->nullable();
            $table->string('kd_daya_terpasang3', 2)->nullable();
            $table->string('kode_pelanggan_daya', 16)->nullable();
            $table->string('kd_bahan_bakar_memasak', 2)->nullable();
            $table->string('kd_fasilitas_tempat_bab', 2)->nullable();
            $table->string('kd_jenis_kloset', 2)->nullable();
            $table->string('kd_pembuangan_akhir_tinja', 2)->nullable();
            $table->string('kd_tabung_gas_3_kg', 2)->nullable();
            $table->string('kd_tabung_gas_5_5_kg', 2)->nullable();
            $table->string('kd_tabung_gas_12_kg', 2)->nullable();
            $table->string('kd_lemari_es', 2)->nullable();
            $table->string('kd_ac', 2)->nullable();
            $table->string('kd_pemanas_air', 2)->nullable();
            $table->string('kd_telepon_rumah', 2)->nullable();
            $table->string('kd_televisi', 2)->nullable();
            $table->string('kd_perhiasan_10_gr_emas', 2)->nullable();
            $table->string('kd_rek_aktif', 2)->nullable();
            $table->string('kd_komputer_laptop', 2)->nullable();
            $table->string('kd_sepeda_motor', 2)->nullable();
            $table->string('kd_mobil', 2)->nullable();
            $table->string('kd_perahu', 2)->nullable();
            $table->string('kd_kapal_perahu_motor', 2)->nullable();
            $table->string('kd_featured_phone', 2)->nullable();
            $table->string('kd_smartphone', 2)->nullable();
            $table->string('kd_sepeda', 2)->nullable();
            $table->string('kd_lahan', 2)->nullable();
            $table->integer('luas_lahan')->nullable();
            $table->string('kd_ada_sertiv_lahan', 2)->nullable();
            $table->string('kd_rumah_ditempat_lain', 2)->nullable();
            $table->integer('jumlah_sapi')->nullable();
            $table->integer('jumlah_kerbau')->nullable();
            $table->integer('jumlah_kuda')->nullable();
            $table->integer('jumlah_babi')->nullable();
            $table->integer('jumlah_kambing_domba')->nullable();
            $table->integer('jumlah_unggas')->nullable();
            $table->integer('jumlah_ikan')->nullable();
            $table->integer('jumlah_lainnya')->nullable();
            $table->string('kd_ada_art_usaha_sendiri_bersama', 2)->nullable();
            $table->string('kd_internet_sebulan', 2)->nullable();
            $table->string('kd_pengeluaran_pulsa_dan_data', 2)->nullable();
            $table->string('kd_ada_art_lanjut_usia', 2)->nullable();
            $table->string('kd_bss_bnpt', 2)->nullable();
            $table->string('bulan_bss_bnpt', 2)->nullable();
            $table->year('tahun_bss_bnpt')->nullable();
            $table->string('kd_pkh', 2)->nullable();
            $table->string('bulan_pkh', 2)->nullable();
            $table->year('tahun_pkh')->nullable();
            $table->string('kd_bst_covid19', 2)->nullable();
            $table->string('bulan_bst_covid19', 2)->nullable();
            $table->year('tahun_bst_covid19')->nullable();
            $table->string('kd_blt_dana_desa', 2)->nullable();
            $table->string('bulan_blt_dana_desa', 2)->nullable();
            $table->year('tahun_blt_dana_desa')->nullable();
            $table->string('kd_subsidi_listrik', 2)->nullable();
            $table->string('bulan_subsidi_listrik', 2)->nullable();
            $table->year('tahun_subsidi_listrik')->nullable();
            $table->string('kd_asuransi_lain', 2)->nullable();
            $table->string('bulan_asuransi_lain', 2)->nullable();
            $table->year('tahun_asuransi_lain')->nullable();
            $table->string('kd_bantuan_pemprov', 2)->nullable();
            $table->string('bulan_bantuan_pemprov', 2)->nullable();
            $table->year('tahun_bantuan_pemprov')->nullable();
            $table->string('kd_bantuan_pemkabkot', 2)->nullable();
            $table->string('bulan_bantuan_pemkabkot', 2)->nullable();
            $table->year('tahun_bantuan_pemkabkot')->nullable();
            $table->string('kd_bantuan_pemdes', 2)->nullable();
            $table->string('bulan_bantuan_pemdes', 2)->nullable();
            $table->year('tahun_bantuan_pemdes')->nullable();
            $table->string('kd_bantuan_pemda', 2)->nullable();
            $table->string('bulan_bantuan_pemda', 2)->nullable();
            $table->year('tahun_bantuan_pemda')->nullable();
            $table->string('kd_bantuan_masyarakat', 2)->nullable();
            $table->string('bulan_bantuan_masyarakat', 2)->nullable();
            $table->year('tahun_bantuan_masyarakat')->nullable();
            $table->string('kd_subsidi_pupuk', 2)->nullable();
            $table->string('bulan_subsidi_pupuk', 2)->nullable();
            $table->year('tahun_subsidi_pupuk')->nullable();
            $table->string('kd_subsidi_lpg', 2)->nullable();
            $table->string('bulan_subsidi_lpg', 2)->nullable();
            $table->year('tahun_subsidi_lpg')->nullable();
            $table->string('kd_konsumsi_daging', 2)->nullable();
            $table->string('kd_makan', 2)->nullable();
            $table->string('kd_beli_pakaian_baru', 2)->nullable();
            $table->string('kd_bayar_biaya_pengobatan', 2)->nullable();
            $table->string('kd_bahasa_wawancara', 2)->nullable();
            $table->string('tulis_bahasa_daerah', 100)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dtks');
    }
};
