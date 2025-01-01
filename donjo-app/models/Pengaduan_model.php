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

class Pengaduan_model extends MY_Model
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

        if ($search !== '' && $search !== '0') {
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

        if ($search !== '' && $search !== '0') {
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
        $this->config_id('p')
            ->select('p.*')
            ->select('(SELECT COUNT(pp.id_pengaduan) FROM pengaduan pp WHERE pp.id_pengaduan = p.id) AS jumlah')
            ->from("{$this->table} p")
            ->order_by('p.id', 'desc');
    }

    public function paging_pengaduan($p = 1, $keyword = '', $status = '')
    {
        $paging = new Paging();

        $jml = $this->get_pengaduan($keyword, $status);
        if ($keyword) {
            $cfg['suffix'] = "?keyword={$keyword}&status={$status}";
        }

        $this->db->where('id_pengaduan is NULL', null, true);

        $cfg['page']     = $p;
        $cfg['per_page'] = 10; // Default
        $cfg['num_rows'] = $jml->count_all_results();
        $paging->init($cfg);

        return $paging;
    }

    public function insert()
    {
        $upload['file_name'] = '';

        if ($_FILES['foto']['error'] == 0) {
            $this->load->library('upload');

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

        $data['foto']      = $upload['file_name'];
        $data['config_id'] = $this->config_id;

        return $this->db->insert('pengaduan', $data);
    }

    private function validasi($post)
    {
        return [
            'nik'        => bilangan($post['nik']),
            'nama'       => nama($post['nama']),
            'email'      => email($post['email']),
            'telepon'    => bilangan($post['telepon']),
            'judul'      => bersihkan_xss($post['judul']),
            'isi'        => bersihkan_xss($post['isi']),
            'ip_address' => $this->input->ip_address(),
        ];
    }
}
