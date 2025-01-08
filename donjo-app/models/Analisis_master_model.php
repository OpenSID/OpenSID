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

use App\Libraries\Paging;

defined('BASEPATH') || exit('No direct script access allowed');

class Analisis_master_model extends MY_Model
{
    public $analisis_master;
    public $periode;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('referensi_model');
        $this->analisis_master = $this->get_analisis_master($this->session->analisis_master);
        $this->periode         = $this->get_periode();
    }

    public function autocomplete()
    {
        return $this->autocomplete_str('nama', 'analisis_master');
    }

    private function search_sql(): void
    {
        if (empty($cari = $this->session->cari)) {
            return;
        }

        $this->db
            ->like('u.nama', $cari);
    }

    private function filter_sql(): void
    {
        if (empty($kf = $this->session->filter)) {
            return;
        }

        $this->db
            ->where('u.subjek_tipe', $kf);
    }

    private function state_sql(): void
    {
        if (empty($kf = $this->session->state)) {
            return;
        }

        $this->db
            ->where('u.lock', $kf);
    }

    public function paging($p = 1, $o = 0)
    {
        $this->list_data_query();
        $jml_data = $this->db
            ->select('COUNT(u.id) as jml_data')
            ->get()
            ->row()
            ->jml_data;

        $paging          = new Paging();
        $cfg['page']     = $p;
        $cfg['per_page'] = $_SESSION['per_page'];
        $cfg['num_rows'] = $jml_data;
        $paging->init($cfg);

        return $paging;
    }

    private function list_data_query(): void
    {
        $this->config_id('u')
            ->from('analisis_master u')
            ->join('analisis_ref_subjek s', 'u.subjek_tipe = s.id', 'left');
        $this->search_sql();
        $this->filter_sql();
        $this->state_sql();
    }

    public function list_data($o = 0, $offset = 0, $limit = 500)
    {
        $desa = ucwords(setting('sebutan_desa'));
        $this->db
            ->select('u.*')
            ->select("(case when u.subjek_tipe = 5 then '{$desa}' else s.subjek end) as subjek");
        $this->list_data_query();

        switch ($o) {
            case 1:
                $this->db->order_by('u.lock');
                break;

            case 2:
                $this->db->order_by('u.lock DESC');
                break;

            case 3:
                $this->db->order_by('u.nama');
                break;

            case 4:
                $this->db->order_by('u.nama DESC');
                break;

            case 5:
                $this->db->order_by('s.subjek');
                break;

            case 6:
                $this->db->order_by('s.subjek DESC');
                break;

            default:
                $this->db->order_by('u.id');
        }
        if ($limit > 0) {
            $this->db->limit($limit, $offset);
        }

        $data = $this->db->get()->result_array();

        $j       = $offset;
        $counter = count($data);

        for ($i = 0; $i < $counter; $i++) {
            $data[$i]['no'] = $j + 1;
            if ($data[$i]['lock'] == 1) {
                $data[$i]['lock'] = '<img src="' . base_url('assets/images/icon/unlock.png') . '">';
            } else {
                $data[$i]['lock'] = '<img src="' . base_url('assets/images/icon/lock.png') . '">';
            }
            $j++;
        }

        return $data;
    }

    private function sterilkan_data($post)
    {
        return [
            'nama'         => judul($post['nama']),
            'subjek_tipe'  => $post['subjek_tipe'],
            'id_kelompok'  => $post['id_kelompok'] ?: null,
            'lock'         => $post['lock'] ?: null,
            'format_impor' => $post['format_impor'] ?: null,
            'pembagi'      => bilangan_titik($post['pembagi']),
            'id_child'     => $post['id_child'] ?: null,
            'deskripsi'    => htmlentities($post['deskripsi']),
        ];
    }

    public function insert(): void
    {
        $data              = $this->sterilkan_data($this->input->post());
        $data['config_id'] = $this->config_id;
        $outp              = $this->db->insert('analisis_master', $data);
        status_sukses($outp);
    }

    public function update($id = 0): void
    {
        $data = $this->sterilkan_data($this->input->post());
        // Kolom yang tidak boleh diubah untuk analisis sistem
        if ($this->is_analisis_sistem($id)) {
            unset($data['subjek_tipe'], $data['lock'], $data['format_impor']);
        }
        $outp = $this->config_id()->where('id', $id)->update('analisis_master', $data);
        status_sukses($outp);
    }

    public function is_analisis_sistem($id)
    {
        $jenis = $this->config_id()
            ->select('jenis')
            ->where('id', $id)
            ->get('analisis_master')
            ->row()
            ->jenis;

        return $jenis == 1;
    }

    public function delete($id = '', $semua = false): void
    {
        if ($this->is_analisis_sistem($id)) {
            return;
        } // Jangan hapus analisis sistem

        if (! $semua) {
            $this->session->success = 1;
        }
        $this->sub_delete($id);

        $outp = $this->config_id()->where('id', $id)->delete('analisis_master');

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

    // TODO: OpenKAB - tambahkan migrasi relational constraint supaya data analisis terhapus secara otomatis oleh DB
    private function sub_delete($id = ''): void
    {
        $this->config_id()
            ->where("id_indikator IN(SELECT id FROM analisis_indikator WHERE id_master = {$id} AND config_id = {$this->config_id})")
            ->delete('analisis_parameter');

        $this->db
            ->where("id_periode IN(SELECT id FROM analisis_periode WHERE id_master = {$id})")
            ->delete('analisis_respon');

        $this->config_id()->where('id_master', $id)->delete('analisis_kategori_indikator');

        $this->config_id()->where('id_master', $id)->delete('analisis_klasifikasi');

        $this->config_id()->where('id_master', $id)->delete('analisis_respon_hasil');

        $this->db->where('id_master', $id)->delete('analisis_partisipasi');

        $this->config_id()->where('id_master', $id)->delete('analisis_periode');

        $this->config_id()->where('id_master', $id)->delete('analisis_indikator');
    }

    public function get_analisis_master($id = 0)
    {
        return $this->config_id('u')
            ->select('u.*, s.subjek as subjek_nama')
            ->from('analisis_master u')
            ->join('analisis_ref_subjek s', 'u.subjek_tipe = s.id', 'left')
            ->where('u.id', $id)
            ->get()
            ->row_array();
    }

    // periode aktif
    public function get_periode($id = null)
    {
        $id = $id ?: $this->session->analisis_master;

        return $this->db
            ->select('*')
            ->from('analisis_periode')
            ->where('aktif', 1)
            ->where('id_master', $id)
            ->get()
            ->row();
    }

    // id dari periode aktif
    public function get_aktif_periode()
    {
        return $this->periode->id;
    }

    public function list_subjek()
    {
        $subjek                  = $this->referensi_model->list_data('analisis_ref_subjek');
        $desa                    = array_search('5', array_column($subjek, 'id'), true);
        $subjek[$desa]['subjek'] = ucwords(setting('sebutan_desa'));

        return $subjek;
    }

    public function list_kelompok()
    {
        return $this->config_id()
            ->get('kelompok_master')
            ->result_array();
    }

    public function list_analisis_child()
    {
        return $this->config_id()
            ->where('subjek_tipe', 1)
            ->get('analisis_master')
            ->result_array();
    }
}
