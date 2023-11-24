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

defined('BASEPATH') || exit('No direct script access allowed');

class Plan_polygon_model extends MY_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function autocomplete()
    {
        return $this->autocomplete_str('nama', 'polygon');
    }

    private function search_sql(): void
    {
        if ($cari = $this->session->cari) {
            $this->db->group_start()->like('nama', $cari)->group_end();
        }
    }

    private function filter_sql(): void
    {
        if ($filter = $this->session->filter) {
            $this->db->where('enabled', $filter);
        }
    }

    public function paging($p = 1, $o = 0)
    {
        $this->db->select('count(*) as jml');

        $row      = $this->list_data_sql()->row_array();
        $jml_data = $row['jml'];

        return $this->paginasi($p, $jml_data);
    }

    private function list_data_sql()
    {
        $this->config_id()
            ->from('polygon')
            ->where('tipe', '0');

        $this->search_sql();
        $this->filter_sql();

        return $this->db->get();
    }

    public function list_data($o = 0, $offset = 0, $limit = 1000)
    {
        switch ($o) {
            case 1:
                $this->db->order_by('nama');
                break;

            case 2:
                $this->db->order_by('nama', 'DESC');
                break;

            case 3:
                $this->db->order_by('enabled');
                break;

            case 4:
                $this->db->order_by('enabled', 'DESC');
                break;

            default:
                $this->db->order_by('id');
        }

        $this->db->select('*')
            ->limit($limit, $offset);

        $data = $this->list_data_sql()->result_array();

        $j       = $offset;
        $counter = count($data);

        for ($i = 0; $i < $counter; $i++) {
            $data[$i]['no'] = $j + 1;

            $data[$i]['aktif'] = $data[$i]['enabled'] == 1 ? 'Ya' : 'Tidak';

            $j++;
        }

        return $data;
    }

    private function validasi($post)
    {
        $data['nama']  = nomor_surat_keputusan($post['nama']);
        $data['color'] = warna($post['color']);

        return $data;
    }

    public function insert(): void
    {
        $data              = $this->validasi($this->input->post());
        $data['config_id'] = identitas('id');
        $lokasi_file       = $_FILES['simbol']['tmp_name'];
        $tipe_file         = $_FILES['simbol']['type'];
        $nama_file         = $_FILES['simbol']['name'];
        $nama_file         = str_replace(' ', '-', $nama_file); 	 // normalkan nama file
        if (! empty($lokasi_file)) {
            if ($tipe_file == 'image/png' || $tipe_file == 'image/gif') {
                UploadSimbol($nama_file);
                $data['simbol'] = $nama_file;
                $outp           = $this->db->insert('polygon', $data);
            }
        } else {
            unset($data['simbol']);
            $outp = $this->db->insert('polygon', $data);
        }

        status_sukses($outp); //Tampilkan Pesan
    }

    public function update($id = 0): void
    {
        $data        = $this->validasi($this->input->post());
        $lokasi_file = $_FILES['simbol']['tmp_name'];
        $tipe_file   = $_FILES['simbol']['type'];
        $nama_file   = $_FILES['simbol']['name'];
        $nama_file   = str_replace(' ', '-', $nama_file); 	 // normalkan nama file
        if (! empty($lokasi_file)) {
            if ($tipe_file == 'image/png' || $tipe_file == 'image/gif') {
                UploadSimbol($nama_file);
                $data['simbol'] = $nama_file;
                $this->db->where('id', $id);
                $outp = $this->db->update('polygon', $data);
            }
            $_SESSION['success'] = 1;
        }

        unset($data['simbol']);
        $this->db->where('id', $id);
        $outp = $this->config_id()->update('polygon', $data);

        $_SESSION['success'] = $outp ? 1 : -1;
    }

    public function delete($id = '', $semua = false): void
    {
        if (! $semua) {
            $this->session->success = 1;
        }

        $outp = $this->config_id()->where('id', $id)->delete('polygon');

        status_sukses($outp, $gagal_saja = true); //Tampilkan Pesan
    }

    public function delete_all(): void
    {
        $this->session->success = 1;

        $id_cb = $_POST['id_cb'];

        foreach ($id_cb as $id) {
            $this->delete($id, $semua = true);
        }
    }

    public function list_sub_polygon($polygon = 1)
    {
        $data = $this->config_id()
            ->where('parrent', $polygon)
            ->where('tipe', 2)
            ->get('polygon')
            ->result_array();
        $counter = count($data);

        for ($i = 0; $i < $counter; $i++) {
            $data[$i]['no'] = $i + 1;

            $data[$i]['aktif'] = $data[$i]['enabled'] == 1 ? 'Ya' : 'Tidak';
        }

        return $data;
    }

    public function insert_sub_polygon($parrent = 0): void
    {
        $data              = $this->validasi($this->input->post());
        $data['config_id'] = identitas('id');
        $lokasi_file       = $_FILES['simbol']['tmp_name'];
        $tipe_file         = $_FILES['simbol']['type'];
        $nama_file         = $_FILES['simbol']['name'];
        $nama_file         = str_replace(' ', '-', $nama_file); 	 // normalkan nama file
        if (! empty($lokasi_file)) {
            if ($tipe_file == 'image/png' || $tipe_file == 'image/gif') {
                UploadSimbol($nama_file);
                $data['simbol']  = $nama_file;
                $data['parrent'] = $parrent;
                $data['tipe']    = 2;
                $outp            = $this->db->insert('polygon', $data);
                if ($outp) {
                    $_SESSION['success'] = 1;
                }
            } else {
                $_SESSION['success'] = -1;
            }
        } else {
            unset($data['simbol']);
            $data['parrent'] = $parrent;
            $data['tipe']    = 2;
            $outp            = $this->db->insert('polygon', $data);
        }
        $_SESSION['success'] = $outp ? 1 : -1;
    }

    public function update_sub_polygon($id = 0): void
    {
        $data        = $this->validasi($this->input->post());
        $lokasi_file = $_FILES['simbol']['tmp_name'];
        $tipe_file   = $_FILES['simbol']['type'];
        $nama_file   = $_FILES['simbol']['name'];
        $nama_file   = str_replace(' ', '-', $nama_file); 	 // normalkan nama file
        if (! empty($lokasi_file)) {
            if ($tipe_file == 'image/png' || $tipe_file == 'image/gif') {
                UploadSimbol($nama_file);
                $data['simbol'] = $nama_file;
                $this->db->where('id', $id);
                $outp = $this->config_id()->update('polygon', $data);
            }
            $_SESSION['success'] = 1;
        } else {
            unset($data['simbol']);
            $this->db->where('id', $id);
            $outp = $this->config_id()->update('polygon', $data);
        }
        $_SESSION['success'] = $outp ? 1 : -1;
    }

    public function delete_sub_polygon($id = '', $semua = false): void
    {
        if (! $semua) {
            $this->session->success = 1;
        }

        $outp = $this->config_id()->where('id', $id)->delete('polygon');

        status_sukses($outp, $gagal_saja = true); //Tampilkan Pesan
    }

    public function delete_all_sub_polygon(): void
    {
        $this->session->success = 1;

        $id_cb = $this->input->post('id_cb');

        foreach ($id_cb as $id) {
            $this->delete_sub_polygon($id, $semua = true);
        }
    }

    public function polygon_lock($id = '', $val = 0): void
    {
        $outp = $this->config_id()
            ->where('id', $id)
            ->update('polygon', ['enabled' => $val]);

        status_sukses($outp); //Tampilkan Pesan
    }

    public function get_polygon($id = 0)
    {
        return $this->config_id()
            ->where('id', $id)
            ->get('polygon')
            ->row_array();
    }
}
