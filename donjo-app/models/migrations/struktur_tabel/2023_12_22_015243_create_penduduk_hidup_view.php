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
use Illuminate\Support\Facades\DB;

return new class () extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('CREATE VIEW `penduduk_hidup` AS select `tweb_penduduk`.`id` AS `id`,`tweb_penduduk`.`config_id` AS `config_id`,`tweb_penduduk`.`nama` AS `nama`,`tweb_penduduk`.`nik` AS `nik`,`tweb_penduduk`.`id_kk` AS `id_kk`,`tweb_penduduk`.`kk_level` AS `kk_level`,`tweb_penduduk`.`id_rtm` AS `id_rtm`,`tweb_penduduk`.`rtm_level` AS `rtm_level`,`tweb_penduduk`.`sex` AS `sex`,`tweb_penduduk`.`tempatlahir` AS `tempatlahir`,`tweb_penduduk`.`tanggallahir` AS `tanggallahir`,`tweb_penduduk`.`agama_id` AS `agama_id`,`tweb_penduduk`.`pendidikan_kk_id` AS `pendidikan_kk_id`,`tweb_penduduk`.`pendidikan_sedang_id` AS `pendidikan_sedang_id`,`tweb_penduduk`.`pekerjaan_id` AS `pekerjaan_id`,`tweb_penduduk`.`status_kawin` AS `status_kawin`,`tweb_penduduk`.`warganegara_id` AS `warganegara_id`,`tweb_penduduk`.`dokumen_pasport` AS `dokumen_pasport`,`tweb_penduduk`.`dokumen_kitas` AS `dokumen_kitas`,`tweb_penduduk`.`ayah_nik` AS `ayah_nik`,`tweb_penduduk`.`ibu_nik` AS `ibu_nik`,`tweb_penduduk`.`nama_ayah` AS `nama_ayah`,`tweb_penduduk`.`nama_ibu` AS `nama_ibu`,`tweb_penduduk`.`foto` AS `foto`,`tweb_penduduk`.`golongan_darah_id` AS `golongan_darah_id`,`tweb_penduduk`.`id_cluster` AS `id_cluster`,`tweb_penduduk`.`status` AS `status`,`tweb_penduduk`.`alamat_sebelumnya` AS `alamat_sebelumnya`,`tweb_penduduk`.`alamat_sekarang` AS `alamat_sekarang`,`tweb_penduduk`.`status_dasar` AS `status_dasar`,`tweb_penduduk`.`hamil` AS `hamil`,`tweb_penduduk`.`cacat_id` AS `cacat_id`,`tweb_penduduk`.`sakit_menahun_id` AS `sakit_menahun_id`,`tweb_penduduk`.`akta_lahir` AS `akta_lahir`,`tweb_penduduk`.`akta_perkawinan` AS `akta_perkawinan`,`tweb_penduduk`.`tanggalperkawinan` AS `tanggalperkawinan`,`tweb_penduduk`.`akta_perceraian` AS `akta_perceraian`,`tweb_penduduk`.`tanggalperceraian` AS `tanggalperceraian`,`tweb_penduduk`.`cara_kb_id` AS `cara_kb_id`,`tweb_penduduk`.`telepon` AS `telepon`,`tweb_penduduk`.`tanggal_akhir_paspor` AS `tanggal_akhir_paspor`,`tweb_penduduk`.`no_kk_sebelumnya` AS `no_kk_sebelumnya`,`tweb_penduduk`.`ktp_el` AS `ktp_el`,`tweb_penduduk`.`status_rekam` AS `status_rekam`,`tweb_penduduk`.`waktu_lahir` AS `waktu_lahir`,`tweb_penduduk`.`tempat_dilahirkan` AS `tempat_dilahirkan`,`tweb_penduduk`.`jenis_kelahiran` AS `jenis_kelahiran`,`tweb_penduduk`.`kelahiran_anak_ke` AS `kelahiran_anak_ke`,`tweb_penduduk`.`penolong_kelahiran` AS `penolong_kelahiran`,`tweb_penduduk`.`berat_lahir` AS `berat_lahir`,`tweb_penduduk`.`panjang_lahir` AS `panjang_lahir`,`tweb_penduduk`.`tag_id_card` AS `tag_id_card`,`tweb_penduduk`.`created_at` AS `created_at`,`tweb_penduduk`.`created_by` AS `created_by`,`tweb_penduduk`.`updated_at` AS `updated_at`,`tweb_penduduk`.`updated_by` AS `updated_by`,`tweb_penduduk`.`id_asuransi` AS `id_asuransi`,`tweb_penduduk`.`no_asuransi` AS `no_asuransi`,`tweb_penduduk`.`email` AS `email`,`tweb_penduduk`.`email_token` AS `email_token`,`tweb_penduduk`.`email_tgl_kadaluarsa` AS `email_tgl_kadaluarsa`,`tweb_penduduk`.`email_tgl_verifikasi` AS `email_tgl_verifikasi`,`tweb_penduduk`.`telegram` AS `telegram`,`tweb_penduduk`.`telegram_token` AS `telegram_token`,`tweb_penduduk`.`telegram_tgl_kadaluarsa` AS `telegram_tgl_kadaluarsa`,`tweb_penduduk`.`telegram_tgl_verifikasi` AS `telegram_tgl_verifikasi`,`tweb_penduduk`.`bahasa_id` AS `bahasa_id`,`tweb_penduduk`.`ket` AS `ket`,`tweb_penduduk`.`negara_asal` AS `negara_asal`,`tweb_penduduk`.`tempat_cetak_ktp` AS `tempat_cetak_ktp`,`tweb_penduduk`.`tanggal_cetak_ktp` AS `tanggal_cetak_ktp`,`tweb_penduduk`.`suku` AS `suku`,`tweb_penduduk`.`bpjs_ketenagakerjaan` AS `bpjs_ketenagakerjaan`,`tweb_penduduk`.`hubung_warga` AS `hubung_warga` from `tweb_penduduk` where `tweb_penduduk`.`status_dasar` = 1');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('DROP VIEW IF EXISTS `penduduk_hidup`');
    }
};
