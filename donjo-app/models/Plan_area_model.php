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

use App\Models\Area;

defined('BASEPATH') || exit('No direct script access allowed');

class Plan_area_model extends MY_Model
{
    public function autocomplete()
    {
        return $this->autocomplete_str('nama', 'area');
    }

    private function search_sql(): void
    {
        if ($cari = $this->session->cari) {
            $this->db->group_start()->like('l.nama', $cari)->group_end();
        }
    }

    private function filter_sql(): void
    {
        if ($filter = $this->session->filter) {
            $this->db->where('l.enabled', $filter);
        }
    }

    private function polygon_sql(): void
    {
        if ($polygon = $this->session->polygon) {
            $this->db->where('p.id', $polygon);
        }
    }

    private function subpolygon_sql(): void
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
        $this->config_id('l')
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

        $this->db->select('l.*, p.nama as kategori, m.nama as jenis, p.simbol as simbol, p.color as color')
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
        $data['nama']        = nomor_surat_keputusan($post['nama']);
        $data['ref_polygon'] = bilangan($post['ref_polygon']);
        $data['desk']        = htmlentities($post['desk']);
        $data['enabled']     = bilangan($post['enabled']);

        return $data;
    }

    public function insert(): void
    {
        $data              = $this->validasi($this->input->post());
        $data['config_id'] = identitas('id');
        $area_file         = $_FILES['foto']['tmp_name'];
        $nama_file         = $_FILES['foto']['name'];
        $nama_file         = time() . '-' . str_replace(' ', '-', $nama_file);      // normalkan nama file
        if (! empty($area_file)) {
            $data['foto'] = UploadPeta($nama_file, LOKASI_FOTO_AREA);
        } else {
            unset($data['foto']);
        }

        $outp = $this->db->insert('area', $data);

        status_sukses($outp); //Tampilkan Pesan
    }

    public function update($id = 0): void
    {
        $data      = $this->validasi($this->input->post());
        $old_foto  = $this->input->post('old_foto');
        $area_file = $_FILES['foto']['tmp_name'];
        $nama_file = $_FILES['foto']['name'];
        $nama_file = time() . '-' . str_replace(' ', '-', $nama_file);      // normalkan nama file
        if (! empty($area_file)) {
            $data['foto'] = UploadPeta($nama_file, LOKASI_FOTO_AREA, $old_foto);
        } else {
            unset($data['foto']);
        }

        $outp = $this->config_id()->where('id', $id)->update('area', $data);

        status_sukses($outp); //Tampilkan Pesan
    }

    public function delete($id = '', $semua = false): void
    {
        if (! $semua) {
            $this->session->success = 1;
        }

        $area = Area::findOrFail($id);
        $outp = $area->delete();

        if ($outp && ($area->foto_kecil || $area->foto_sedang)) {
            unlink(FCPATH . $area->foto_kecil);
            unlink(FCPATH . $area->foto_sedang);
        }

        status_sukses($outp, true); //Tampilkan Pesan
    }

    public function delete_all(): void
    {
        $this->session->success = 1;

        $id_cb = $this->input->post('id_cb');

        foreach ($id_cb as $id) {
            $this->delete($id, true);
        }
    }

    public function list_polygon()
    {
        if ($subpolygon = $this->session->subpolygon) {
            $this->db->where('parrent', $subpolygon);
        }

        return $this->config_id()
            ->where('tipe', 2)
            ->get('polygon')
            ->result_array();
    }

    public function list_subpolygon()
    {
        return $this->config_id()
            ->where('tipe', 0)
            ->get('polygon')
            ->result_array();
    }

    public function area_lock($id = '', $val = 0): void
    {
        $outp = $this->config_id()
            ->where('id', $id)
            ->update('area', ['enabled' => $val]);

        status_sukses($outp); //Tampilkan Pesan
    }

    public function get_area($id = 0)
    {
        return $this->config_id()
            ->where('id', $id)
            ->get('area')
            ->row_array();
    }

    public function update_position($id = 0): void
    {
        $data = $this->input->post();
        $this->db->where('id', $id);
        $outp = $data['path'] !== '[[]]' ? $this->config_id()->update('area', $data) : '';

        status_sukses($outp, $gagal_saja = false, $msg = 'titik koordinat area harus diisi'); //Tampilkan Pesan
    }

    public function list_area($status = null)
    {
        if (null !== $status) {
            $this->db
                ->where('l.enabled', $status)
                ->where('p.enabled', $status)
                ->where('m.enabled', $status);
        }

        return $this->config_id('l')
            ->select('l.*, p.nama AS kategori, m.nama AS jenis, p.simbol AS simbol, p.color AS color')
            ->from('area l')
            ->join('polygon p', 'l.ref_polygon = p.id', 'left')
            ->join('polygon m', 'p.parrent = m.id', 'left')
            ->where('l.ref_polygon !=', 0)
            ->get()
            ->result_array();
    }

    public function kosongkan_path($id): void
    {
        $this->config_id()
            ->set('path', null)
            ->where('id', $id)
            ->update('area');
    }
}
