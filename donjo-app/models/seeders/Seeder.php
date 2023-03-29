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
 * Hak Cipta 2016 - 2023 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright Hak Cipta 2016 - 2023 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

use App\Models\Config;
use Illuminate\Support\Facades\Schema;

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

    public function run()
    {
        $this->load->model('seeders/data_awal_seeder', 'data_awal');
        $this->data_awal->run();

        // Database perlu dibuka ulang supaya cachenya berfungsi benar setelah diubah
        $this->db->close();
        $this->load->database();
        $this->load->model('database_model');
        $this->database_model->impor_data_awal_analisis();
        $this->database_model->cek_migrasi(true);
        $this->isi_config();
    }

    // Kalau belum diisi, buat identitas desa jika kode_desa ada di file desa/config/config.php
    private function isi_config()
    {
        if (! Schema::hasTable('config') || Config::first() || empty($kode_desa = config_item('kode_desa')) || ! cek_koneksi_internet()) {
            return;
        }

        // Ambil data desa dari tracksid
        $this->load->library('data_publik');
        $this->data_publik->set_api_url(config_item('server_pantau') . '/index.php/api/wilayah/kodedesa?token=' . config_item('token_pantau') . '&kode=' . $kode_desa, 'kode_desa');
        $data_desa = $this->data_publik->get_url_content(true);

        if ($data_desa->header->http_code != 200 || empty($data_desa->body)) {
            set_session('error', "Kode desa {$kode_desa} di desa/config/config.php tidak ditemukan di " . config_item('server_pantau'));
        } else {
            $desa = $data_desa->body;
            $data = [
                'nama_desa'         => $desa->nama_desa,
                'kode_desa'         => $kode_desa,
                'nama_kecamatan'    => $desa->nama_kec,
                'kode_kecamatan'    => $desa->kode_kec,
                'nama_kabupaten'    => $desa->nama_kab,
                'kode_kabupaten'    => $desa->kode_kab,
                'nama_propinsi'     => $desa->nama_prov,
                'kode_propinsi'     => $desa->kode_prov,
                'nama_kepala_camat' => '',
                'nip_kepala_camat'  => '',
            ];
            if (Config::insert($data)) {
                set_session('success', "Kode desa {$kode_desa} diambil dari desa/config/config.php");
            }
        }
    }
}
