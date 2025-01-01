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

defined('BASEPATH') || exit('No direct script access allowed');

class Laporan_sinkronisasi_model extends MY_Model
{
    public const ORDER = [
        2 => 'judul',
        3 => 'semester', // atau bulan
        4 => 'tahun',
        5 => 'updated_at',
        6 => 'kirim',
    ];

    private string $table = 'laporan_sinkronisasi';
    protected $tipe       = 'laporan_apbdes';

    public function set_tipe(string $tipe)
    {
        $this->tipe = $tipe;

        return $this;
    }

    public function get_data(string $search = '', $tahun = null)
    {
        $this->config_id()->from($this->table);

        if ($search !== '' && $search !== '0') {
            $this->db
                ->group_start()
                ->like('judul', $search)
                ->or_like('tahun', $search)
                ->or_like('semester', $search)
                ->or_like('nama_file', $search)
                ->group_end();
        }

        if ($tahun) {
            $this->db->where('tahun', $tahun);
        }

        return $this->db->where('tipe', $this->tipe);
    }

    public function find($id)
    {
        return $this->config_id()
            ->where('tipe', $this->tipe)
            ->where('id', $id)
            ->get($this->table)
            ->row();
    }

    public function get_tahun()
    {
        return $this->config_id()
            ->distinct()
            ->select('tahun')
            ->where('tipe', $this->tipe)
            ->get($this->table)
            ->result();
    }

    public function insert($data = null): void
    {
        // $data bisa dikirim dari laporan yg dibuat otomatis; kalau kosong ambil dari form
        $data              = $data ?: $this->validasi();
        $data['config_id'] = identitas('id');
        $outp              = $this->db->insert($this->table, $data);

        status_sukses($outp);
    }

    public function update($id, $data = null): void
    {
        $data               = $data ?: $this->validasi();
        $data['updated_at'] = date('Y-m-d H:i:s');
        $data['kirim']      = null;
        $outp               = $this->config_id()->where('id', $id)->update($this->table, $data);

        status_sukses($outp);
    }

    public function insert_or_update($where = null, $data = null): void
    {
        $id = $this->config_id()->select('id')->get_where($this->table, $where)->row()->id;

        $outp = ($id) ? $this->update($id, $data) : $this->insert($data);

        status_sukses($outp);
    }

    public function delete($id): void
    {
        $outp = $this->config_id()->where('id', $id)->where('kirim', null)->delete($this->table);

        if ($outp && ($nama_file = $this->find($id)->nama_file)) {
            unlink(LOKASI_DOKUMEN . $nama_file);
        }

        status_sukses($outp);
    }

    public function delete_all(): void
    {
        foreach ($this->input->post('id_cb') as $id) {
            $this->delete($id);
        }
    }

    private function validasi()
    {
        $post = $this->input->post();

        $data['judul']    = alfanumerik_spasi($post['judul']);
        $data['semester'] = bilangan(($this->tipe == 'laporan_apbdes') ? $post['semester'] : $post['bulan']);
        $data['tahun']    = bilangan($post['tahun']);
        $data['tipe']     = $this->tipe;

        if (! empty($_FILES['nama_file']['name'])) {
            $data['nama_file'] = $this->upload($post['judul']);
        }

        return $data;
    }

    private function upload($nama_file)
    {
        $this->load->library('upload');

        $config['upload_path']   = LOKASI_DOKUMEN;
        $config['allowed_types'] = 'pdf';
        $config['max_size']      = max_upload() * 1024;
        $config['file_name']     = namafile($nama_file);
        $config['overwrite']     = true;

        $this->upload->initialize($config);

        try {
            $upload = $this->upload->do_upload('nama_file');

            if (! $upload) {
                session_error($this->upload->display_errors());

                redirect($this->controller);
            }

            $uploadData = $this->upload->data();

            return $uploadData['file_name'];
        } catch (Exception $e) {
            session_error($this->upload->display_errors());

            redirect($this->controller);
        }
    }

    public function opendk($id)
    {
        $list_data = $this->config_id()
            ->where_in('id', $id)
            ->get($this->table)
            ->result_array();

        $kirim = [];

        foreach ($list_data as $key => $data) {
            $kirim[$key]['id']    = $data['id'];
            $kirim[$key]['judul'] = $data['judul'];
            if ($this->tipe == 'laporan_apbdes') {
                $kirim[$key]['semester'] = $data['semester'];
            } else {
                $kirim[$key]['bulan'] = $data['semester'];
            }
            $kirim[$key]['nama_file']  = $data['nama_file'];
            $kirim[$key]['tahun']      = $data['tahun'];
            $kirim[$key]['created_at'] = $data['created_at'];
            $kirim[$key]['updated_at'] = $data['updated_at'];
            $kirim[$key]['file']       = $this->file($data['nama_file']);
        }

        return $kirim;
    }

    public function file($nama_file)
    {
        return base64_encode(file_get_contents(LOKASI_DOKUMEN . $nama_file));
    }

    public function kirim($id): void
    {
        $data['kirim'] = date('Y-m-d H:i:s');
        $outp          = $this->config_id()->where_in('id', $id)->update($this->table, $data);

        status_sukses($outp);
    }
}
