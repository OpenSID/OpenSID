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
use App\Models\Config;
use Illuminate\Support\Facades\Schema;

class Config_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    // Digunakan dibanyak tempat
    public function get_data()
    {
        $this->db->reset_query(); // TODO: cari query yg menggantung terkait pemanggilan first/dpt

        return $this->db
            ->select('c.*, u.pamong_nip AS nip_kepala_desa')
            ->select('(case when p.nama is not null then p.nama else u.pamong_nama end) AS nama_kepala_desa')
            ->from('config c')
            ->join('tweb_desa_pamong u', 'c.pamong_id = u.pamong_id', 'left')
            ->join('tweb_penduduk p', 'u.id_pend = p.id', 'left')
            ->get()
            ->row_array();
    }

    // Kalau belum diisi, buat identitas desa jika kode_desa ada di file desa/config/config.php
    public function isi_config()
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
