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

use App\Models\Garis;

defined('BASEPATH') || exit('No direct script access allowed');

class Plan_garis_model extends MY_Model
{
    protected $table = 'garis';

    public function autocomplete()
    {
        return $this->autocomplete_str('nama', $this->table);
    }

    private function search_sql(): void
    {
        if ($cari = $this->session->cari) {
            $this->db->like('l.nama', $cari);
        }
    }

    private function filter_sql(): void
    {
        if ($filter = $this->session->filter) {
            $this->db->where('l.enabled', $filter);
        }
    }

    private function line_sql(): void
    {
        if ($line = $this->session->line) {
            $this->db->where('m.id', $line);
        }
    }

    private function subline_sql(): void
    {
        if ($subline = $this->session->subline) {
            $this->db->where('p.id', $subline);
        }
    }

    public function paging($p = 1, $o = 0)
    {
        $this->list_data_sql();
        $row      = $this->db->select('count(l.id) as jml')->get()->row_array();
        $jml_data = $row['jml'];

        $this->load->library('paging');
        $cfg['page']     = $p;
        $cfg['per_page'] = $this->session->per_page;
        $cfg['num_rows'] = $jml_data;
        $this->paging->init($cfg);

        return $this->paging;
    }

    // Pastikan paging dan pencarian data berdasarkan filter yg sama
    private function list_data_sql(): void
    {
        $this->config_id('l')
            ->from("{$this->table} l")
            ->join('line p', 'l.ref_line = p.id', 'left')
            ->join('line m', 'p.parrent = m.id', 'left');

        $this->search_sql();
        $this->filter_sql();
        $this->line_sql();
        $this->subline_sql();
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
            ->select('l.*, p.nama as kategori, m.nama as jenis, p.simbol, p.color, p.tebal, p.jenis as jenis_garis')
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

    private function validasi($post)
    {
        return [
            'nama'     => nomor_surat_keputusan($post['nama']),
            'ref_line' => $post['ref_line'],
            'desk'     => htmlentities($post['desk']),
            'enabled'  => bilangan($post['enabled']),
        ];
    }

    public function insert(): void
    {
        $data              = $this->validasi($this->input->post());
        $data['config_id'] = identitas('id');
        $garis_file        = $_FILES['foto']['tmp_name'];
        $nama_file         = $_FILES['foto']['name'];
        $nama_file         = time() . '-' . str_replace(' ', '-', $nama_file);      // normalkan nama file
        if (! empty($garis_file)) {
            $data['foto'] = UploadPeta($nama_file, LOKASI_FOTO_GARIS);
        } else {
            unset($data['foto']);
        }

        $outp = $this->db->insert($this->table, $data);

        status_sukses($outp); //Tampilkan Pesan
    }

    public function update($id = 0): void
    {
        $data       = $this->validasi($this->input->post());
        $old_foto   = $this->input->post('old_foto');
        $garis_file = $_FILES['foto']['tmp_name'];
        $nama_file  = $_FILES['foto']['name'];
        $nama_file  = time() . '-' . str_replace(' ', '-', $nama_file);      // normalkan nama file
        if (! empty($garis_file)) {
            $data['foto'] = UploadPeta($nama_file, LOKASI_FOTO_GARIS, $old_foto);
        } else {
            unset($data['foto']);
        }

        $outp = $this->config_id()->where('id', $id)->update($this->table, $data);

        status_sukses($outp); //Tampilkan Pesan
    }

    public function delete($id = '', $semua = false): void
    {
        if (! $semua) {
            $this->session->success = 1;
        }

        $garis = Garis::findOrFail($id);
        $outp  = $garis->delete();

        if ($outp && ($garis->foto_kecil || $garis->foto_sedang)) {
            unlink(FCPATH . $garis->foto_kecil);
            unlink(FCPATH . $garis->foto_sedang);
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

    public function list_line()
    {
        if ($subline = $this->session->subline) {
            $this->db->where('parrent', $subline);
        }

        return $this->config_id()
            ->from('line')
            ->where('tipe', 0)
            ->get()
            ->result_array();
    }

    public function list_subline()
    {
        if ($line = $this->session->line) {
            $this->db->where('id', $line);
        }

        return $this->config_id()
            ->from('line')
            ->where('tipe', 2)
            ->get()
            ->result_array();
    }

    public function garis_lock($id = '', $val = 0): void
    {
        $outp = $this->config_id()
            ->where('id', $id)
            ->update($this->table, ['enabled' => $val]);

        status_sukses($outp);
    }

    public function get_garis($id = 0)
    {
        return $this->config_id('l')
            ->select('l.*, p.nama AS kategori, m.nama AS jenis, p.simbol, p.color, p.tebal, p.jenis AS jenis_garis')
            ->from("{$this->table} l")
            ->join('line p', 'l.ref_line = p.id', 'left')
            ->join('line m', ' p.parrent = m.id')
            ->where('l.id', $id)
            ->get()
            ->row_array();
    }

    public function update_position($id = 0): void
    {
        $data = $_POST;
        $this->db->where('id', $id);
        $outp = $data['path'] !== '[]' ? $this->config_id()->update($this->table, $data) : '';

        status_sukses($outp, $gagal_saja = false, $msg = 'titik koordinat garis harus diisi'); //Tampilkan Pesan
    }

    public function list_garis($status = null)
    {
        if (null !== $status) {
            $this->db
                ->where('l.enabled', $status)
                ->where('p.enabled', $status)
                ->where('m.enabled', $status);
        }

        return $this->config_id('l')
            ->select('l.*, p.nama AS kategori, m.nama AS jenis, p.simbol AS simbol, p.color AS color, p.tebal AS tebal, p.jenis AS jenis_garis')
            ->from("{$this->table} l")
            ->join('line p', 'l.ref_line = p.id', 'left')
            ->join('line m', ' p.parrent = m.id', 'left')
            ->where('l.ref_line !=', 0)
            ->get()
            ->result_array();
    }

    public function kosongkan_path($id): void
    {
        $this->config_id()
            ->set('path', null)
            ->where('id', $id)
            ->update($this->table);
    }
}
