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

class Analisis_kategori_model extends MY_Model
{
    public function autocomplete()
    {
        return $this->autocomplete_str('kategori', 'analisis_kategori_indikator');
    }

    private function search_sql(): void
    {
        if ($cari = $this->session->cari) {
            $this->db->like('u.kategori', $cari);
        }
    }

    private function master_sql(): void
    {
        if ($analisis_master = $this->session->analisis_master) {
            $this->db->like('u.id_master', $analisis_master);
        }
    }

    public function paging($p = 1, $o = 0)
    {
        $this->db->select("count('u.id') as id");

        $row      = $this->list_data_sql()->row_array();
        $jml_data = $row['id'];

        return $this->paginasi($p, $jml_data);
    }

    private function list_data_sql()
    {
        $this->config_id('u')
            ->from('analisis_kategori_indikator u');

        $this->search_sql();
        $this->master_sql();

        return $this->db->get();
    }

    public function list_data($o = 0, $offset = 0, $limit = 500)
    {
        switch ($o) {
            case 3:
                $this->db->order_by('u.kategori');
                break;

            case 4:
                $this->db->order_by('u.kategori DESC');
                break;

            default:
                $this->db->order_by('u.kategori');
        }
        if ($limit > 0) {
            $this->db->limit($limit, $offset);
        }

        $data = $this->list_data_sql()->result_array();

        $j       = $offset;
        $counter = count($data);

        for ($i = 0; $i < $counter; $i++) {
            $data[$i]['no'] = $j + 1;
            $j++;
        }

        return $data;
    }

    public function insert(): void
    {
        $data              = [];
        $data['id_master'] = $this->session->analisis_master;
        $data['kategori']  = htmlentities($this->input->post('kategori'));
        $data['config_id'] = identitas('id');
        $outp              = $this->db->insert('analisis_kategori_indikator', $data);

        status_sukses($outp); //Tampilkan Pesan
    }

    public function update($id = 0): void
    {
        $data              = [];
        $data['id_master'] = $this->session->analisis_master;
        $data['kategori']  = htmlentities($this->input->post('kategori'));
        $outp              = $this->config_id()->where('id', $id)->update('analisis_kategori_indikator', $data);
        status_sukses($outp); //Tampilkan Pesan
    }

    public function delete($id = '', $semua = false): void
    {
        if (! $semua) {
            $this->session->success = 1;
        }

        $outp = $this->config_id()->where('id', $id)->delete('analisis_kategori_indikator');

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

    public function get_analisis_kategori($id = 0)
    {
        return $this->config_id()
            ->where('id', $id)
            ->get('analisis_kategori_indikator')
            ->row_array();
    }
}
