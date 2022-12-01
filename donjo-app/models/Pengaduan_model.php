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

class Pengaduan_model extends CI_Model
{
    public const ORDER_ABLE_PENGADUAN = [
        3 => 'nama',
        4 => 'judul',
        5 => 'created_at',
        6 => 'status',
    ];

    protected $table = 'pengaduan';

    public function get_pengaduan(string $search = '', $status = '')
    {
        $this->pengaduan();

        if ($search) {
            $this->db
                ->group_start()
                ->like('judul', $search)
                ->or_like('isi', $search)
                ->or_like('nama', $search)
                ->group_end();
        }

        if ($status) {
            $this->db->where('status', $status);
        }

        $this->db->where('id_pengaduan is NULL', null, true);

        return $this->db;
    }

    public function get_pengaduan_balas(string $search = '', $status = '')
    {
        $this->pengaduan();

        if ($search) {
            $this->db
                ->group_start()
                ->like('judul', $search)
                ->or_like('isi', $search)
                ->or_like('nama', $search)
                ->group_end();
        }

        if ($status) {
            $this->db->where('status', $status);
        }

        return $this->db;
    }

    public function get_pengaduan_a(string $search = '', $status = '')
    {
        $this->pengaduan();

        if ($search) {
            $this->db
                ->group_start()
                ->like('judul', $search)
                ->or_like('isi', $search)
                ->or_like('nama', $search)
                ->group_end();
        }

        if ($status) {
            $this->db->where('status', $status);
        }

        $this->db->where('id_pengaduan is NULL', null, true);

        return $this->db;
    }

    protected function pengaduan()
    {
        $this->db
            ->select('p.*')
            ->select('(SELECT COUNT(pp.id_pengaduan) FROM pengaduan pp WHERE pp.id_pengaduan = p.id) AS jumlah')
            ->from("{$this->table} p")
            ->order_by('p.id', 'desc');
    }

    public function paging_pengaduan($p = 1, $keyword = '', $status = '')
    {
        $this->load->library('paging');

        $jml = $this->get_pengaduan($keyword, $status);
        if ($keyword) {
            $cfg['suffix'] = "?keyword={$keyword}&status={$status}";
        }

        $this->db->where('id_pengaduan is NULL', null, true);

        $cfg['page']     = $p;
        $cfg['per_page'] = 10; // Default
        $cfg['num_rows'] = $jml->count_all_results();
        $this->paging->init($cfg);

        return $this->paging;
    }

    public function insert()
    {
        $upload['file_name'] = '';

        if ($_FILES['foto']['error'] == 0) {
            $this->load->library('MY_Upload', null, 'upload');

            $config['upload_path']   = LOKASI_PENGADUAN;
            $config['allowed_types'] = 'jpg|jpeg|png';
            $config['max_size']      = max_upload() * 1024;
            $config['file_name']     = namafile($this->input->post('judul', true));

            $this->upload->initialize($config);

            if ($this->upload->do_upload('foto')) {
                $upload = $this->upload->data();
            } else {
                return false;
            }
        }

        $data = $this->validasi($this->input->post());

        $data['foto'] = $upload['file_name'];

        return $this->db->insert('pengaduan', $data);
    }

    private function validasi($post)
    {
        return [
            'nik'     => bilangan($post['nik']),
            'nama'    => alfanumerik_kolon($post['nama']),
            'email'   => htmlentities($post['email']),
            'telepon' => bilangan($post['telepon']),
            'judul'   => htmlentities($post['judul']),
            'isi'     => htmlentities($post['isi']),
        ];
    }

    public function m_insert($id = '')
    {
        $updateinduk = $this->db->where('id', $id)->
                update('pengaduan', ['status' => $this->input->post('status')]);

        $update = $this->db->where('id_pengaduan', $id)->
                update('pengaduan', ['status' => $this->input->post('status')]);

        $post = $this->input->post();
        $data = $this->m_validasi($post, $id);

        return $this->db->insert('pengaduan', $data) && $update && $updateinduk;
    }

    private function m_validasi($post, $id)
    {
        return [
            'id_pengaduan' => $id,
            'nama'         => $this->session->nama,
            'isi'          => $post['isi'],
            'status'       => $post['status'],
        ];
    }

    public function get_data($search = '')
    {
        $builder = $this->db
            ->select('*')
            ->from('pengaduan')
            ->where('id_pengaduan', null);

        if (empty($search)) {
            $search = $builder;
        } else {
            $search = $builder
                ->where('status', $search);
        }

        return $search;
    }

    public function get_data_month($search = '')
    {
        $builder = $this->db
            ->select('*')
            ->from('pengaduan')
            ->where('id_pengaduan', null)
            ->like('created_at', date('Y-m'));

        if (empty($search)) {
            $search = $builder;
        } else {
            $search = $builder
                ->where('status', $search);
        }

        return $search;
    }

    // PENGADUAN ADMIN
    public function pengaduan_detail($id = 0)
    {
        $this->pengaduan();

        return $this->db->where('p.id', $id)->get()->row();
    }

    public function pengaduan_detailna($id = 0)
    {
        return $this->db
            ->select('p.*')
            ->select('(SELECT COUNT(pp.id_pengaduan) FROM pengaduan pp WHERE pp.id_pengaduan = p.id) AS jumlah')
            ->where('p.id', $id)
            ->or_where('p.id_pengaduan', $id)
            ->get("{$this->table} p")
            ->result_array();
    }

    public function pengaduan_delete($id = 0)
    {
        $outp = $this->db->where('id', $id)->delete('pengaduan');
        $outp = $this->db->where('id_pengaduan', $id)->delete('pengaduan');

        status_sukses($outp);
    }

    public function pengaduan_delete_all()
    {
        $id_cb = $_POST['id_cb'];

        foreach ($id_cb as $id) {
            $this->pengaduan_delete($id);
        }
    }

    public function status($table, $id, $status = 1)
    {
        $outp = $this->db
            ->where('id', $id)
            ->update($table, ['status' => $status]);

        status_sukses($outp);
    }
}
