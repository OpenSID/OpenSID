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

class Plan_area_model extends MY_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function autocomplete()
    {
        return $this->autocomplete_str('nama', 'area');
    }

    private function search_sql()
    {
        if ($cari = $this->session->cari) {
            $this->db->group_start()->like('l.nama', $cari)->group_end();
        }
    }

    private function filter_sql()
    {
        if ($filter = $this->session->filter) {
            $this->db->where('l.enabled', $filter);
        }
    }

    private function polygon_sql()
    {
        if ($polygon = $this->session->polygon) {
            $this->db->where('p.id', $polygon);
        }
    }

    private function subpolygon_sql()
    {
        if ($subpolygon = $this->session->subpolygon) {
            $this->db->where('m.id', $subpolygon);
        }
    }

    public function paging($p = 1, $o = 0)
    {
        $this->db->select("count('l.id') as id");

        $row      = $this->list_data_sql()->row_array();
        $jml_data = $row['id'];

        return $this->paginasi($p, $jml_data);
    }

    private function list_data_sql()
    {
        $this->db
            ->join('polygon p', 'l.ref_polygon = p.id', 'left')
            ->join('polygon m', 'p.parrent = m.id', 'left');

        $this->search_sql();
        $this->filter_sql();
        $this->polygon_sql();
        $this->subpolygon_sql();

        return $this->db->get('area l');
    }

    public function list_data($o = 0, $offset = 0, $limit = null)
    {
        switch ($o) {
            case 1: $this->db->order_by('nama');
                break;

            case 2: $this->db->order_by('nama', 'DESC');
                break;

            case 3: $this->db->order_by('enabled');
                break;

            case 4: $this->db->order_by('enabled', 'DESC');
                break;

            default: $this->db->order_by('id');
        }

        $this->db->select('l.*, p.nama as kategori, m.nama as jenis, p.simbol as simbol, p.color as color')
            ->limit($limit, $offset);

        $data = $this->list_data_sql()->result_array();

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

    private function validasi($post)
    {
        $data['nama']        = nomor_surat_keputusan($post['nama']);
        $data['ref_polygon'] = bilangan($post['ref_polygon']);
        $data['desk']        = htmlentities($post['desk']);
        $data['enabled']     = bilangan($post['enabled']);

        return $data;
    }

    public function insert()
    {
        $data      = $this->validasi($this->input->post());
        $area_file = $_FILES['foto']['tmp_name'];
        $tipe_file = $_FILES['foto']['type'];
        $nama_file = $_FILES['foto']['name'];
        $nama_file = str_replace(' ', '-', $nama_file); 	 // normalkan nama file
        if (! empty($area_file)) {
            if ($tipe_file == 'image/jpg' || $tipe_file == 'image/jpeg') {
                Uploadarea($nama_file);
                $data['foto'] = $nama_file;
                $outp         = $this->db->insert('area', $data);
            }
        } else {
            unset($data['foto']);
            $outp = $this->db->insert('area', $data);
        }

        status_sukses($outp); //Tampilkan Pesan
    }

    public function update($id = 0)
    {
        $data      = $this->validasi($this->input->post());
        $area_file = $_FILES['foto']['tmp_name'];
        $tipe_file = $_FILES['foto']['type'];
        $nama_file = $_FILES['foto']['name'];
        $nama_file = str_replace(' ', '-', $nama_file); 	 // normalkan nama file
        if (! empty($area_file)) {
            if ($tipe_file == 'image/jpg' || $tipe_file == 'image/jpeg') {
                Uploadarea($nama_file);
                $data['foto'] = $nama_file;
                $this->db->where('id', $id);
                $outp = $this->db->update('area', $data);
            }
        } else {
            unset($data['foto']);
            $this->db->where('id', $id);
            $outp = $this->db->update('area', $data);
        }
        status_sukses($outp); //Tampilkan Pesan
    }

    public function delete($id = '', $semua = false)
    {
        if (! $semua) {
            $this->session->success = 1;
        }

        $outp = $this->db->where('id', $id)->delete('area');

        status_sukses($outp, $gagal_saja = true); //Tampilkan Pesan
    }

    public function delete_all()
    {
        $this->session->success = 1;

        $id_cb = $this->input->post('id_cb');

        foreach ($id_cb as $id) {
            $this->delete($id, $semua = true);
        }
    }

    public function list_polygon()
    {
        if ($subpolygon = $this->session->subpolygon) {
            $this->db->where('parrent', $subpolygon);
        }

        return $this->db->where('tipe', 2)
            ->get('polygon')
            ->result_array();
    }

    public function list_subpolygon()
    {
        return $this->db->where('tipe', 0)
            ->get('polygon')
            ->result_array();
    }

    public function area_lock($id = '', $val = 0)
    {
        $outp = $this->db->where('id', $id)
            ->update('area', ['enabled' => $val]);

        status_sukses($outp); //Tampilkan Pesan
    }

    public function get_area($id = 0)
    {
        return $this->db->where('id', $id)
            ->get('area')
            ->row_array();
    }

    public function update_position($id = 0)
    {
        $data = $this->input->post();
        $this->db->where('id', $id);
        if ($data['path'] !== '[[]]') {
            $outp = $this->db->update('area', $data);
        } else {
            $outp = '';
        }

        status_sukses($outp, $gagal_saja = false, $msg = 'titik koordinat area harus diisi'); //Tampilkan Pesan
    }

    public function list_area()
    {
        return $this->db
            ->select('l.*, p.nama AS kategori, m.nama AS jenis, p.simbol AS simbol, p.color AS color')
            ->from('area l')
            ->join('polygon p', 'l.ref_polygon = p.id', 'left')
            ->join('polygon m', 'p.parrent = m.id', 'left')
            ->where('l.enabled', 1)
            ->where('p.enabled', 1)
            ->where('m.enabled', 1)
            ->get()->result_array();
    }

    public function kosongkan_path($id)
    {
        $this->db
            ->set('path', null)
            ->where('id', $id)
            ->update('area');
    }
}
