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

use App\Models\Config;

defined('BASEPATH') || exit('No direct script access allowed');

/*
| Class ini digunakan untuk mengisi data awal pada waktu install.
| Jika koneksi database gagal akan diarahkan ke donjo-app/controllers/Periksa.php/koneksi_database
| melalui exception yang diproses oleh show_error() di donjo-app/core/MY_Exceptions.php.
*/

class Seeder extends CI_Model
{
    public function __construct()
    {
        parent::__construct();

        // Buat folder desa
        folder_desa();

        // Untuk kasus koneksi database gagal
        if ($this->db) {
            $this->session->unset_userdata(['db_error', 'message', 'heading', 'message_query', 'message_exception', 'sudah_mulai']);
        } elseif (! $this->session->sudah_mulai) {
            $this->session->unset_userdata(['db_error', 'message', 'heading', 'message_query', 'message_exception']);
            $this->session->sudah_mulai = true;
        }

        if ($this->session->db_error) {
            return;
        }

        $this->load->database();
        if (empty($this->db->list_tables())) {
            $this->run();
        }
    }

    public function run(): void
    {
        $this->load->helper('directory');

        log_message('notice', 'Mulai memasang data awal');

        // Hapus isi folder desa/cache
        $dir = config_item('cache_path');

        foreach (directory_map($dir) as $file) {
            if ($file !== 'index.html') {
                unlink($dir . DIRECTORY_SEPARATOR . $file);
            }
        }

        // Hapus file app_key
        $file = DESAPATH . 'app_key';
        if (file_exists($file)) {
            unlink($file);
        }

        $this->load->model('seeders/data_awal_seeder', 'data_awal_seeder');
        $this->data_awal_seeder->run();

        // Database perlu dibuka ulang supaya cachenya berfungsi benar setelah diubah
        $this->db->close();
        $this->load->database();
        $this->load->model('database_model');
        $this->isi_config();
        // Migrasi::latest('id')->first()->delete();
        // Tetap jalankan Data awal
        $this->load->model('migrations/data_awal', 'data_awal');
        $this->data_awal->up();

        $this->database_model->cek_migrasi(true);
        session_destroy();
        log_message('notice', 'Selesai memasang data awal');
    }

    // Kalau belum diisi, buat identitas desa jika kode_desa ada di file desa/config/config.php
    private function isi_config(): void
    {
        $kode_desa = config_item('kode_desa');
        if ($kode_desa) {
            if (identitas() || ! cek_koneksi_internet()) {
                return;
            }
            // Ambil data desa dari tracksid
            $data_desa = get_data_desa($kode_desa);
            if (null === $data_desa) {
                set_session('error', "Kode desa {$kode_desa} di desa/config/config.php tidak ditemukan di " . config_item('server_pantau'));
            } else {
                $desa = $data_desa;
                $data = [
                    'nama_desa'         => nama_desa($desa->nama_desa),
                    'kode_desa'         => bilangan($kode_desa),
                    'nama_kecamatan'    => nama_terbatas($desa->nama_kec),
                    'kode_kecamatan'    => bilangan($desa->kode_kec),
                    'nama_kabupaten'    => ucwords(hapus_kab_kota(nama_terbatas($desa->nama_kab))),
                    'kode_kabupaten'    => bilangan($desa->kode_kab),
                    'nama_propinsi'     => ucwords(nama_terbatas($desa->nama_prov)),
                    'kode_propinsi'     => bilangan($desa->kode_prov),
                    'nama_kepala_camat' => '',
                    'nip_kepala_camat'  => '',
                ];
                // tabel config selalu terisi dari data_awal_seeder
                if (Config::appKey()->update($data)) {
                    (new Config())->flushQueryCache();
                    set_session('success', "Kode desa {$kode_desa} diambil dari desa/config/config.php");
                }
            }
        }
    }
}
