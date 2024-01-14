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

use App\Models\Lokasi;

defined('BASEPATH') || exit('No direct script access allowed');

class Plan_lokasi_model extends MY_Model
{
    public function autocomplete()
    {
        return $this->autocomplete_str('nama', 'lokasi');
    }

    private function search_sql()
    {
        if ($cari = $this->session->cari) {
            $this->db->like('l.nama', $cari);
        }
    }

    private function filter_sql()
    {
        if ($filter = $this->session->filter) {
            $this->db->where('l.enabled', $filter);
        }
    }

    private function point_sql()
    {
        if ($point = $this->session->point) {
            $this->db->where('p.id', $point);
        }
    }

    private function subpoint_sql()
    {
        if ($subpoint = $this->session->subpoint) {
            $this->db->where('m.id', $subpoint);
        }
    }

    public function paging($p = 1, $o = 0)
    {
        $this->list_data_sql();
        $row      = $this->db->select('count(l.id) as id')->get()->row_array();
        $jml_data = $row['id'];

        $this->load->library('paging');
        $cfg['page']     = $p;
        $cfg['per_page'] = $this->session->per_page;
        $cfg['num_rows'] = $jml_data;
        $this->paging->init($cfg);

        return $this->paging;
    }

    private function list_data_sql()
    {
        $this->config_id('l')
            ->from('lokasi l')
            ->join('point p', 'l.ref_point = p.id', 'left')
            ->join('point m', 'p.parrent = m.id', 'left');

        $this->search_sql();
        $this->filter_sql();
        $this->point_sql();
        $this->subpoint_sql();
    }

    public function list_data($o = 0, $offset = 0, $limit = 1000)
    {
        switch ($o) {
            case 1: $this->db->order_by('nama');
                break;

            case 2: $this->db->order_by('nama', 'desc');
                break;

            case 3: $this->db->order_by('enabled');
                break;

            case 4: $this->db->order_by('enabled', 'desc');
                break;

            default:$this->db->order_by('id');
        }

        $this->list_data_sql();

        $data = $this->db
            ->select('l.*, p.nama as kategori, m.nama as jenis, p.simbol as simbol')
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

    private function validasi($post)
    {
        $data['nama']      = nomor_surat_keputusan($post['nama']);
        $data['ref_point'] = $post['ref_point'];
        $data['desk']      = htmlentities($post['desk']);
        $data['enabled']   = $post['enabled'];

        return $data;
    }

    public function insert()
    {
        $data              = $this->validasi($this->input->post());
        $data['config_id'] = identitas('id');
        $garis_file        = $_FILES['foto']['tmp_name'];
        $nama_file         = $_FILES['foto']['name'];
        $nama_file         = time() . '-' . str_replace(' ', '-', $nama_file);
        if (! empty($garis_file)) {
            $data['foto'] = UploadPeta($nama_file, LOKASI_FOTO_LOKASI);
        } else {
            unset($data['foto']);
        }

        $outp = $this->db->insert('lokasi', $data);

        status_sukses($outp);
    }

    public function update($id = 0)
    {
        $data       = $this->validasi($this->input->post());
        $old_foto   = $this->input->post('old_foto');
        $garis_file = $_FILES['foto']['tmp_name'];
        $nama_file  = $_FILES['foto']['name'];
        $nama_file  = time() . '-' . str_replace(' ', '-', $nama_file);      // normalkan nama file
        if (! empty($garis_file)) {
            $data['foto'] = UploadPeta($nama_file, LOKASI_FOTO_LOKASI, $old_foto);
        } else {
            unset($data['foto']);
        }

        $outp = $this->config_id()->where('id', $id)->update('lokasi', $data);

        status_sukses($outp);
    }

    public function delete($id = '', $semua = false)
    {
        if (! $semua) {
            $this->session->success = 1;
        }

        $garis = Lokasi::findOrFail($id);
        $outp  = $garis->delete();

        if ($outp) {
            if ($garis->foto_kecil || $garis->foto_sedang) {
                unlink(FCPATH . $garis->foto_kecil);
                unlink(FCPATH . $garis->foto_sedang);
            }
        }

        status_sukses($outp, true);
    }

    public function delete_all()
    {
        $this->session->success = 1;

        $id_cb = $this->input->post('id_cb');

        foreach ($id_cb as $id) {
            $this->delete($id, true);
        }
    }

    public function list_point()
    {
        if ($subpoint = $this->session->subpoint) {
            $this->db->where('parrent', $subpoint);
        }

        return $this->config_id()
            ->from('point')
            ->where('enabled', 1)
            ->where('tipe', 2)
            ->get()
            ->result_array();
    }

    public function list_subpoint()
    {
        if ($point = $this->session->point) {
            $this->db->where('id', $point);
        }

        return $this->config_id()
            ->from('point')
            ->where('enabled', 1)
            ->where('tipe', 0)
            ->get()
            ->result_array();
    }

    public function lokasi_lock($id = '', $val = 0)
    {
        $outp = $this->config_id()
            ->where('id', $id)
            ->update('lokasi', ['enabled' => $val]);

        status_sukses($outp);
    }

    public function get_lokasi($id = 0)
    {
        return $this->config_id()
            ->where('id', $id)
            ->get('lokasi')
            ->row_array();
    }

    public function update_position($id = 0)
    {
        $data['lat'] = koordinat($this->input->post('lat'));
        $data['lng'] = koordinat($this->input->post('lng'));

        $outp = $this->config_id()
            ->where('id', $id)
            ->update('lokasi', $data);

        status_sukses($outp);
    }

    /**
     * @param mixed|null $status
     */
    public function list_lokasi($status = null)
    {
        if (null !== $status) {
            $this->db
                ->where('l.enabled', $status)
                ->where('p.enabled', $status)
                ->where('m.enabled', $status);
        }

        return $this->config_id('l')
            ->select('l.*, p.nama AS kategori, m.nama AS jenis, p.simbol AS simbol')
            ->from('lokasi l')
            ->join('point p', 'l.ref_point = p.id', 'left')
            ->join('point m', 'p.parrent = m.id', 'left')
            ->where('l.ref_point !=', 0)
            ->get()
            ->result_array();
    }
}
