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

use App\Enums\StatusEnum;

defined('BASEPATH') || exit('No direct script access allowed');

class Web_kategori_model extends MY_Model
{
    private $urut_model;

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

    private function search_sql()
    {
        if ($cari = $this->session->cari) {
            $this->db->like('kategori', $cari);
        }
    }

    private function filter_sql()
    {
        if ($cari = $this->session->filter) {
            $this->db->where('enabled', $cari);
        }
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

    private function list_data_sql()
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

        $j = $offset;

        for ($i = 0; $i < count($data); $i++) {
            $data[$i]['no'] = $j + 1;

            if ($data[$i]['enabled'] == 1) {
                $data[$i]['aktif'] = 'Ya';
            } else {
                $data[$i]['aktif'] = 'Tidak';
            }

            $j++;
        }

        return $data;
    }

    public function insert()
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

    private function sterilkan_kategori(&$data)
    {
        unset($data['kategori_lama']);
        $data['kategori'] = htmlentities($data['kategori']);
    }

    private function cek_nama($kategori)
    {
        $ada_nama = $this->config_id(null, true)->where('kategori', $kategori)
            ->get('kategori')->num_rows();
        if ($ada_nama) {
            $_SESSION['error_msg'] .= ' -> Nama kategori tidak boleh sama';
            $_SESSION['success'] = -1;

            return false;
        }

        return true;
    }

    public function update($id = 0)
    {
        $this->session->unset_userdata('error_msg');
        $this->session->set_userdata('success', 1);
        $data             = [];
        $data['kategori'] = $this->input->post('kategori');
        if ($data['kategori'] == $data['kategori_lama']) {
            return; // Tidak ada yg diubah
        }

        if (! $this->cek_nama($data['kategori'])) {
            return;
        }

        $this->sterilkan_kategori($data);
        $outp = $this->config_id()->where('id', $id)->update('kategori', $data);

        status_sukses($outp); //Tampilkan Pesan
    }

    public function delete($id = '', $semua = false)
    {
        if (! $semua) {
            $this->session->success = 1;
        }

        $outp = $this->config_id()->where('id', $id)->or_where('parrent', $id)->delete('kategori');

        status_sukses($this->db->affected_rows(), $gagal_saja = true); //Tampilkan Pesan
    }

    public function delete_sub($id = '', $semua = false)
    {
        if (! $semua) {
            $this->session->success = 1;
        }

        $outp = $this->config_id()->where('id', $id)->delete('kategori');

        status_sukses($this->db->affected_rows(), $gagal_saja = true); //Tampilkan Pesan
    }

    public function delete_all()
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
                for ($i = 0; $i < count($data); $i++) {
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

        for ($i = 0; $i < count($data); $i++) {
            $data[$i]['no'] = $i + 1;
        }

        return $data;
    }

    public function list_kategori($o = '')
    {
        if (empty($o)) {
            $urut = 'urut';
        } else {
            $urut = $o;
        }

        $data = $this->config_id('k', true)
            ->select('k.*')
            ->where('enabled', 1)
            ->order_by($urut)
            ->get('kategori k')
            ->result_array();

        for ($i = 0; $i < count($data); $i++) {
            $data[$i]['no']    = $i + 1;
            $data[$i]['judul'] = $data[$i]['kategori'];
        }

        return $data;
    }

    public function insert_sub_kategori($kategori = 0)
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

    public function update_sub_kategori($id = 0)
    {
        $data             = [];
        $data['kategori'] = $this->input->post('kategori');
        $this->sterilkan_kategori($data);
        $outp = $this->config_id()->where('id', $id)->update('kategori', $data);

        status_sukses($outp); //Tampilkan Pesan
    }

    public function delete_sub_kategori($id = '', $semua = false)
    {
        if (! $semua) {
            $this->session->success = 1;
        }

        $outp = $this->config_id()->where('id', $id)->delete('kategori');

        status_sukses($outp); //Tampilkan Pesan
    }

    public function delete_all_sub_kategori()
    {
        $this->session->success = 1;

        $id_cb = $_POST['id_cb'];

        foreach ($id_cb as $id) {
            $this->delete_sub_kategori($id, $semua = true);
        }
    }

    public function kategori_lock($id = '', $val = 0)
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
    public function urut($id, $arah, $kategori = '')
    {
        $subset = ! empty($kategori) ? ['parrent' => $kategori] : ['parrent' => 0];
        $this->urut_model->urut($id, $arah, $subset);
    }
}
