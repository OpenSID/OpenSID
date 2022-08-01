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
 * Hak Cipta 2016 - 2022 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright Hak Cipta 2016 - 2022 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

defined('BASEPATH') || exit('No direct script access allowed');

class Migrasi_default_value extends CI_model
{
    public function up()
    {
        $this->dbforge->modify_column('tweb_penduduk', ['id_rtm' => ['id_rtm', 'type' => 'VARCHAR(30)', 'null' => true, 'default' => null]]);
        $this->dbforge->modify_column('tweb_penduduk', ['rtm_level' => ['rtm_level', 'type' => 'INT(11)', 'null' => true, 'default' => null]]);
        $this->dbforge->modify_column('tweb_penduduk', ['tempatlahir' => ['tempatlahir', 'type' => 'VARCHAR(100)', 'null' => true, 'default' => null]]);
        $this->dbforge->modify_column('tweb_penduduk', ['agama_id' => ['agama_id', 'type' => 'INT(1)', 'null' => true, 'default' => null]]);
        $this->dbforge->modify_column('tweb_penduduk', ['pendidikan_kk_id' => ['pendidikan_kk_id', 'type' => 'INT(1)', 'null' => true, 'default' => null]]);
        $this->dbforge->modify_column('tweb_penduduk', ['pendidikan_sedang_id' => ['pendidikan_sedang_id', 'type' => 'INT(1)', 'null' => true, 'default' => null]]);
        $this->dbforge->modify_column('tweb_penduduk', ['pekerjaan_id' => ['pekerjaan_id', 'type' => 'INT(1)', 'null' => true, 'default' => null]]);
        $this->dbforge->modify_column('tweb_penduduk', ['status_kawin' => ['status_kawin', 'type' => 'TINYINT', 'null' => true]]);
        $this->dbforge->modify_column('tweb_penduduk', ['ayah_nik' => ['ayah_nik', 'type' => 'VARCHAR(16)', 'null' => true, 'default' => null]]);
        $this->dbforge->modify_column('tweb_penduduk', ['ibu_nik' => ['ibu_nik', 'type' => 'VARCHAR(16)', 'null' => true, 'default' => null]]);
        $this->dbforge->modify_column('tweb_penduduk', ['nama_ayah' => ['nama_ayah', 'type' => 'VARCHAR(100)', 'null' => true, 'default' => null]]);
        $this->dbforge->modify_column('tweb_penduduk', ['nama_ibu' => ['nama_ibu', 'type' => 'VARCHAR(100)', 'null' => true, 'default' => null]]);
        $this->dbforge->modify_column('tweb_penduduk', ['foto' => ['foto', 'type' => 'VARCHAR(100)', 'null' => true, 'default' => null]]);
        $this->dbforge->modify_column('tweb_penduduk', ['golongan_darah_id' => ['golongan_darah_id', 'type' => 'INT(11)', 'null' => true, 'default' => null]]);
        $this->dbforge->modify_column('tweb_penduduk', ['alamat_sebelumnya' => ['alamat_sebelumnya', 'type' => 'VARCHAR(200)', 'null' => true, 'default' => null]]);
        $this->dbforge->modify_column('tweb_penduduk', ['alamat_sekarang' => ['alamat_sekarang', 'type' => 'VARCHAR(200)', 'null' => true, 'default' => null]]);
        $this->dbforge->modify_column('tweb_penduduk', ['akta_lahir' => ['akta_lahir', 'type' => 'VARCHAR(40)', 'null' => true, 'default' => null]]);
        $this->dbforge->modify_column('tweb_penduduk', ['akta_perkawinan' => ['akta_perkawinan', 'type' => 'VARCHAR(40)', 'null' => true, 'default' => null]]);
        $this->dbforge->modify_column('tweb_penduduk', ['akta_perceraian' => ['akta_perceraian', 'type' => 'VARCHAR(40)', 'null' => true, 'default' => null]]);
        $this->dbforge->modify_column('tweb_penduduk', ['waktu_lahir' => ['waktu_lahir', 'type' => 'VARCHAR(5)', 'null' => true, 'default' => null]]);
        $this->dbforge->modify_column('tweb_penduduk_agama', ['nama' => ['nama', 'type' => 'VARCHAR(100)', 'null' => false]]);
        $this->dbforge->modify_column('tweb_penduduk_asuransi', ['nama' => ['nama', 'type' => 'VARCHAR(50)', 'null' => false]]);
        $this->dbforge->modify_column('tweb_penduduk_hubungan', ['nama' => ['nama', 'type' => 'VARCHAR(100)', 'null' => false]]);
        $this->dbforge->modify_column('tweb_penduduk_kawin', ['nama' => ['nama', 'type' => 'VARCHAR(100)', 'null' => false]]);
        $this->dbforge->modify_column('tweb_penduduk_mandiri', ['pin' => ['pin', 'type' => 'CHAR(32)', 'null' => false]]);
        $this->dbforge->modify_column('tweb_penduduk_map', ['id' => ['id', 'type' => 'INT(11)', 'null' => false]]);
        $this->dbforge->modify_column('tweb_penduduk_map', ['lat' => ['lat', 'type' => 'VARCHAR(24)', 'null' => true, 'default' => null]]);
        $this->dbforge->modify_column('tweb_penduduk_map', ['lng' => ['lng', 'type' => 'VARCHAR(24)', 'null' => true, 'default' => null]]);
        $this->dbforge->modify_column('tweb_penduduk_pendidikan', ['nama' => ['nama', 'type' => 'VARCHAR(50)', 'null' => false]]);
        $this->dbforge->modify_column('tweb_penduduk_pendidikan_kk', ['nama' => ['nama', 'type' => 'VARCHAR(50)', 'null' => false]]);
        $this->dbforge->modify_column('tweb_rtm', ['kelas_sosial' => ['kelas_sosial', 'type' => 'INT(11)', 'null' => true, 'default' => null]]);
    }
}
