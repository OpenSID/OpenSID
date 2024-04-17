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

defined('BASEPATH') || exit('No direct script access allowed');

class Web_menu_model extends MY_Model
{
    protected $table = 'menu';
    private Urut_Model $urut_model;

    public function __construct()
    {
        parent::__construct();
        require_once APPPATH . '/models/Urut_model.php';
        $this->urut_model = new Urut_Model($this->table);
    }

    public function autocomplete($cari = '')
    {
        if ($cari) {
            $this->db->like('nama', $cari);
        }

        $this->list_data_sql();
        $data = $this->db->select('nama')->get()->result_array();

        return autocomplete_data_ke_str($data);
    }

    private function search_sql(): void
    {
        if ($cari = $this->session->cari) {
            $this->db->like('nama', $cari);
        }
    }

    private function filter_sql(): void
    {
        if ($filter = $this->session->filter) {
            $this->db->where('enabled', $filter);
        }
    }

    public function paging($page_number = 1)
    {
        $this->list_data_sql();
        $jml_data = $this->db->select('id')->get()->num_rows();

        return $this->paginasi($page_number, $jml_data);
    }

    private function list_data_sql(): void
    {
        $this->config_id()
            ->from($this->table)
            ->where('parrent', $this->session->parrent);

        $this->filter_sql();
        $this->search_sql();
    }

    public function list_data($o = 0, $offset = 0, $limit = 0)
    {
        switch ($o) {
            case 1:
                $this->db->order_by('nama');
                break;

            case 2:
                $this->db->order_by('nama', 'desc');
                break;

            case 3:
                $this->db->order_by('enabled');

                // no break
            case 4:
                $this->db->order_by('enabled', 'desc');

                // no break
            default:
                $this->db->order_by('urut');
        }

        $this->list_data_sql();
        if ($limit > 0) {
            $this->db->limit($limit, $offset);
        }
        $data = $this->db
            ->get()
            ->result_array();

        $j       = $offset;
        $counter = count($data);

        for ($i = 0; $i < $counter; $i++) {
            $data[$i]['no'] = $j + 1;
            if ($data[$i]['link_tipe'] != 99) {
                $data[$i]['link'] = menu_slug($data[$i]['link']);
            }

            $j++;
        }

        return $data;
    }

    public function insert(): void
    {
        $data              = $this->validasi($this->input->post());
        $data['urut']      = $this->urut_model->urut_max(['parrent' => $this->session->parrent]) + 1;
        $data['config_id'] = identitas('id');

        $outp = $this->db->insert($this->table, $data);

        status_sukses($outp); //Tampilkan Pesan
    }

    public function update($id = 0): void
    {
        $data = $this->validasi($this->input->post());
        if ($data['link'] == '') {
            unset($data['link']);
        }

        $outp = $this->config_id()
            ->where('id', $id)
            ->update($this->table, $data);

        status_sukses($outp); //Tampilkan Pesan
    }

    public function delete($id = '', $semua = false): void
    {
        if (! $semua) {
            $this->session->success = 1;
        }

        $outp = $this->config_id()->where('id', $id)->or_where('parrent', $id)->delete($this->table);

        status_sukses($outp, $gagal_saja = true); //Tampilkan Pesan
    }

    public function delete_all(): void
    {
        $this->session->success = 1;

        $id_cb = $this->input->post('id_cb');

        foreach ($id_cb as $id) {
            $this->delete($id, $semua = true);
        }
    }

    public function menu_lock($id = '', $val = 1): void
    {
        $outp = $this->config_id()
            ->where('id', $id)
            ->or_where('parrent', $id)
            ->update($this->table, ['enabled' => $val]);

        status_sukses($outp); //Tampilkan Pesan
    }

    public function get_menu($id = 0)
    {
        $data = $this->config_id()
            ->get_where($this->table, ['id' => $id])
            ->row_array();

        $data['url'] = menu_slug($data['link']);

        return $data;
    }

    // $arah:
    //		1 - turun
    // 		2 - naik
    public function urut($id, $arah): void
    {
        $this->urut_model->urut($id, $arah, ['parrent' => $this->session->parrent]);
    }

    private function validasi($post)
    {
        $parrent = bilangan($post['parrent'] ?? 0);

        return [
            'nama'      => htmlentities($post['nama']),
            'link'      => $post['link'],
            'parrent'   => $parrent,
            'link_tipe' => $post['link_tipe'],
            'enabled'   => 1,
        ];
    }

    public function menu_aktif($link)
    {
        return $this->config_id()
            ->where('link', $link)
            ->where('enabled', 1)
            ->get($this->table)
            ->num_rows();
    }
}
