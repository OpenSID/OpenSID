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

class Sinkronisasi_model extends CI_model
{
    private $zip_file = '';

    public function __construct()
    {
        parent::__construct();
        $this->load->library('upload');
        $this->load->helper('donjolib');
        $this->uploadConfig = [
            'upload_path'   => LOKASI_SINKRONISASI_ZIP,
            'allowed_types' => 'zip',
            'max_size'      => max_upload() * 1024,
        ];
    }

    // $file = nama file yg akan diproses
    private function extract_file($file)
    {
        $data  = get_csv($this->zip_file, $file);
        $count = count($data);

        for ($i = 0; $i < $count; $i++) {
            if (empty($data[$i]) || ! array_filter($data[$i])) {
                unset($data[$i]);
            }
        }

        return $data;
    }

    public function sinkronkan()
    {
        $hasil = true;

        // Kolom server berisi daftar jenis penggunaan server, misalanya: '4,5,6'
        // Tidak gunakan kolom jenis json, karena penerapan json berbeda antara MySQL dan MariaDB.
        $server       = $this->setting->penggunaan_server;
        $server_regex = "^{$server}$|,{$server}$|,{$server},|^{$server},";
        $list_tabel   = $this->db
            ->where("TRIM(server) REGEXP '{$server_regex}'")
            ->get('ref_sinkronisasi')->result_array();

        // Proses tabel yg berlaku untuk jenis penggunaan server
        $this->zip_file = $this->upload->upload_path . $this->upload->file_name;

        foreach ($list_tabel as $tabel) {
            $nama_tabel        = $tabel['tabel'];
            $update_dari_waktu = $this->db
                ->select('MAX(updated_at) as waktu_update')
                ->get($nama_tabel)
                ->row()->waktu_update;
            $update_dari_waktu = strtotime($update_dari_waktu);
            $data_tabel        = $this->extract_file($nama_tabel . '.csv');
            $data_tabel        = $this->hapus_kolom_tersamar($data_tabel, $tabel['tabel']);
            // Hanya ambil data yg telah berubah
            foreach ($data_tabel as $k => $v) {
                if (strtotime($v['updated_at']) <= $update_dari_waktu) {
                    unset($data_tabel[$k]);
                } else {
                    // Data CSV berisi string 'NULL' untuk kolom dengan nilai NULL
                    $data_tabel[$k] = array_map(static function ($a) {
                        return $a == 'NULL' ? null : $a;
                    }, $data_tabel[$k]);
                }
            }
            if (! empty($data_tabel)) {
                if (! $this->db->update_batch($tabel['tabel'], $data_tabel, 'id')) {
                    $_SESSION['success'] = -1;
                }
            }
        }

        return $hasil;
    }

    // Hapus kolom yang tidak akan diupdate
    private function hapus_kolom_tersamar($data_tabel, $tabel)
    {
        foreach ($data_tabel as &$item) {
            switch ($tabel) {
                case 'tweb_keluarga':
                    unset($item['no_kk']);
                    break;

                case 'tweb_penduduk':
                    unset($item['nama'], $item['nik']);

                    break;
            }
        }

        return $data_tabel;
    }
}
