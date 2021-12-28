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
 * Hak Cipta 2016 - 2021 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright Hak Cipta 2016 - 2021 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

defined('BASEPATH') || exit('No direct script access allowed');

class pengaduanmodelabckup extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function list_data($cari = '')
    {
        if ($cari) {
            $this->db->like('judul', $cari);
            $this->db->or_like('isi', $cari);
            $this->db->or_like('nama', $cari);
        }

        return $this->db
            ->select('p.*')
            ->select('(SELECT COUNT(pp.id_pengaduan) FROM pengaduan pp WHERE pp.id_pengaduan = p.id) AS jumlah')
            ->order_by('p.id', 'desc')
            ->get('pengaduan p')
            ->result_array();
    }

    public function get_pengaduan(string $search = '', $status = 0)
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

    protected function pengaduan()
    {
        $this->db
            ->select('p.*')
            ->select('(SELECT COUNT(pp.id_pengaduan) FROM pengaduan pp WHERE pp.id_pengaduan = p.id) AS jumlah')
            ->from('pengaduan p')
            ->order_by('p.id', 'desc');
    }

    public function paging_pengaduan($p = 1, $keyword = '', $status = '')
    {
        $this->load->library('paging');

        if ($keyword) {
            $jml           = $this->get_pengaduan($keyword, 1);
            $cfg['suffix'] = "?keyword={$keyword}&status={$status}";
        } else {
            $jml = $this->get_pengaduan('', 1);
        }

        if ($status != '') {
            $jml           = $jml->where('status', $status);
            $cfg['suffix'] = "?keyword={$keyword}&status={$status}";
        }

        $cfg['page']     = $p;
        $cfg['per_page'] = 10; // Default
        $cfg['num_rows'] = $jml->count_all_results();
        $this->paging->init($cfg);

        return $this->paging;
    }

    public function insert()
    {
        log_message('error', 'kirim');

        $this->load->library('upload');

        $config['upload_path']   = LOKASI_PENGADUAN;
        $config['allowed_types'] = 'jpg|jpeg|png';
        $config['max_size']      = max_upload() * 1024;
        $config['file_name']     = namafile($this->input->post('judul', true));

        $this->upload->initialize($config);

        if ($this->upload->do_upload('foto')) {
            $upload = $this->upload->data();
        } else {
            $upload['file_name'] = '';
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

    public function m_insert()
    {
        $updateinduk = $this->db->where('id', $this->input->post('id_pengaduan'))->
                update('pengaduan', ['status' => $this->input->post('status')]);

        $update = $this->db->where('id_pengaduan', $this->input->post('id_pengaduan'))->
                update('pengaduan', ['status' => $this->input->post('status')]);

        $post = $this->input->post();
        $data = $this->m_validasi($post);

        return $this->db->insert('pengaduan', $data) && $update && $updateinduk;
    }

    private function m_validasi($post)
    {
        return [
            'id_pengaduan' => $post['id_pengaduan'],
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
}
