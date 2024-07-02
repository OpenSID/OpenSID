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

use App\Enums\StatusEnum;

defined('BASEPATH') || exit('No direct script access allowed');

class Web_kategori_model extends MY_Model
{
    private Urut_Model $urut_model;

    public function __construct()
    {
        parent::__construct();
        require_once APPPATH . '/models/Urut_model.php';
        $this->urut_model = new Urut_Model('kategori');
    }

    public function autocomplete()
    {
        $data = $this->config_id(null, true)
            ->distinct()
            ->select('kategori')
            ->where('parrent', 0)
            ->order_by('kategori')
            ->get('kategori')
            ->result_array();

        return autocomplete_data_ke_str($data);
    }

    public function paging($p = 1, $o = 0)
    {
        $this->list_data_sql();
        $row      = $this->db->select('COUNT(*) AS jml')->get()->row_array();
        $jml_data = $row['jml'];

        $this->load->library('paging');
        $cfg['page']     = $p;
        $cfg['per_page'] = $this->session->per_page;
        $cfg['num_rows'] = $jml_data;
        $this->paging->init($cfg);

        return $this->paging;
    }

    private function list_data_sql(): void
    {
        $this->config_id('k', true)->from('kategori k')->where('parrent', 0);
    }

    public function list_data($o = 0, $offset = 0, $limit = 500)
    {
        switch ($o) {
            case 1: $order = 'kategori';
                break;

            case 2: $order = 'kategori DESC';
                break;

            case 3: $order = 'enabled';
                break;

            case 4: $order = 'enabled DESC';
                break;

            default:$order = 'urut';
        }

        $this->list_data_sql();
        $this->db->select('k.*, k.kategori AS kategori ')->order_by($order)->limit($limit, $offset);

        $data = $this->db
            ->select('k.*, k.kategori AS kategori ')
            ->order_by($order)
            ->limit($limit, $offset)
            ->get()
            ->result_array();

        $j       = $offset;
        $counter = count($data);

        for ($i = 0; $i < $counter; $i++) {
            $data[$i]['no'] = $j + 1;

            $data[$i]['aktif'] = $data[$i]['enabled'] == 1 ? 'Ya' : 'Tidak';

            $j++;
        }

        return $data;
    }

    public function insert(): void
    {
        $this->session->unset_userdata('error_msg');
        $this->session->set_userdata('success', 1);
        $data             = [];
        $data['kategori'] = $this->input->post('kategori');
        $this->sterilkan_kategori($data);
        if (! $this->cek_nama($data['kategori'])) {
            return;
        }
        $data['enabled']   = 1;
        $data['urut']      = $this->urut_model->urut_max(['parrent' => 0]) + 1;
        $data['slug']      = url_title($data['kategori'], 'dash', true);
        $data['config_id'] = identitas('id');
        $outp              = $this->db->insert('kategori', $data);

        status_sukses($outp); //Tampilkan Pesan
    }

    private function sterilkan_kategori(array &$data): void
    {
        unset($data['kategori_lama']);
        $data['kategori'] = htmlentities($data['kategori']);
    }

    private function cek_nama($kategori, $id = 0)
    {
        $ada_nama = $this->config_id(null, true)
            ->where('kategori', $kategori)
            ->where('id !=', $id)
            ->get('kategori')->num_rows();

        if ($ada_nama) {
            $_SESSION['error_msg'] .= ' -> Nama kategori tidak boleh sama';
            $_SESSION['success'] = -1;

            return false;
        }

        return true;
    }

    public function update($id = 0): void
    {
        $this->session->unset_userdata('error_msg');
        $this->session->set_userdata('success', 1);
        $data             = [];
        $data['kategori'] = $this->input->post('kategori');
        if ($data['kategori'] == $data['kategori_lama']) {
            return; // Tidak ada yg diubah
        }
        if (! $this->cek_nama($data['kategori'], $id)) {
            return;
        }

        $this->sterilkan_kategori($data);
        $outp = $this->config_id()->where('id', $id)->update('kategori', $data);

        status_sukses($outp); //Tampilkan Pesan
    }

    public function delete($id = '', $semua = false): void
    {
        if (! $semua) {
            $this->session->success = 1;
        }

        $this->config_id()->where('id', $id)->or_where('parrent', $id)->delete('kategori');

        status_sukses($this->db->affected_rows(), $gagal_saja = true); //Tampilkan Pesan
    }

    public function delete_sub($id = '', $semua = false): void
    {
        if (! $semua) {
            $this->session->success = 1;
        }

        $this->config_id()->where('id', $id)->delete('kategori');

        status_sukses($this->db->affected_rows(), $gagal_saja = true); //Tampilkan Pesan
    }

    public function delete_all(): void
    {
        $this->session->success = 1;

        $id_cb = $_POST['id_cb'];

        foreach ($id_cb as $id) {
            $this->delete($id, $semua = true);
        }
    }

    public function list_sub_kategori($kategori = 1)
    {
        // cek apakah parrent ada
        $ada = $this->config_id(null, true)->where('id', $kategori)->get('kategori')->num_rows();

        if ($ada > 0) {
            $data = $this->config_id(null, true)
                ->where('parrent', $kategori)
                ->order_by('urut')
                ->get('kategori')
                ->result_array();

            if (count($data) > 0) {
                $counter = count($data);

                for ($i = 0; $i < $counter; $i++) {
                    $data[$i]['no']    = $i + 1;
                    $data[$i]['aktif'] = StatusEnum::valueOf($data[$i]['enabled']);
                }

                return $data;
            }

            return [];
        }

        return null;
    }

    public function list_link()
    {
        $data = $this->config_id('a')
            ->select('a.*')
            ->join('kategori k', 'a.id_kategori = k.id')
            ->where('tipe', 2)
            ->get('artikel a')
            ->result_array();
        $counter = count($data);

        for ($i = 0; $i < $counter; $i++) {
            $data[$i]['no'] = $i + 1;
        }

        return $data;
    }

    public function list_kategori($o = '')
    {
        $urut = empty($o) ? 'urut' : $o;

        $data = $this->config_id('k', true)
            ->select('k.*')
            ->where('enabled', 1)
            ->order_by($urut)
            ->get('kategori k')
            ->result_array();
        $counter = count($data);

        for ($i = 0; $i < $counter; $i++) {
            $data[$i]['no']    = $i + 1;
            $data[$i]['judul'] = $data[$i]['kategori'];
        }

        return $data;
    }

    public function insert_sub_kategori($kategori = 0): void
    {
        $data             = [];
        $data['kategori'] = $this->input->post('kategori');
        $this->sterilkan_kategori($data);
        $data['parrent']   = $kategori;
        $data['urut']      = $this->urut_model->urut_max(['parrent' => $kategori]) + 1;
        $data['slug']      = url_title($data['kategori'], 'dash', true);
        $data['enabled']   = 1;
        $data['config_id'] = $this->config_id;
        $outp              = $this->db->insert('kategori', $data);

        status_sukses($outp); //Tampilkan Pesan
    }

    public function update_sub_kategori($id = 0): void
    {
        $data             = [];
        $data['kategori'] = $this->input->post('kategori');
        $this->sterilkan_kategori($data);
        $outp = $this->config_id()->where('id', $id)->update('kategori', $data);

        status_sukses($outp); //Tampilkan Pesan
    }

    public function delete_sub_kategori($id = '', $semua = false): void
    {
        if (! $semua) {
            $this->session->success = 1;
        }

        $outp = $this->config_id()->where('id', $id)->delete('kategori');

        status_sukses($outp); //Tampilkan Pesan
    }

    public function delete_all_sub_kategori(): void
    {
        $this->session->success = 1;

        $id_cb = $_POST['id_cb'];

        foreach ($id_cb as $id) {
            $this->delete_sub_kategori($id, $semua = true);
        }
    }

    public function kategori_lock($id = '', $val = 0): void
    {
        $this->config_id()->where('id', $id)->update('kategori', ['enabled' => $val]);
        status_sukses($outp); //Tampilkan Pesan
    }

    public function get_kategori($id = 0)
    {
        return $this->config_id()->where('id', $id)->or_where('slug', $id)->get('kategori')->row_array();
    }

    // $arah:
    //		1 - turun
    // 		2 - naik
    public function urut($id, $arah, $kategori = ''): void
    {
        $subset = empty($kategori) ? ['parrent' => 0] : ['parrent' => $kategori];
        $this->urut_model->urut($id, $arah, $subset);
    }
}
