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

class Web_gallery_model extends MY_Model
{
    private Urut_Model $urut_model;

    public function __construct()
    {
        parent::__construct();
        require_once APPPATH . '/models/Urut_model.php';
        $this->urut_model = new Urut_Model('gambar_gallery');
    }

    public function autocomplete()
    {
        return $this->autocomplete_str('nama', 'gambar_gallery');
    }

    private function search_sql(): void
    {
        if ($cari = $this->session->cari) {
            $this->db->like('gambar', $cari, 'BOTH')->or_like('nama', $cari, 'BOTH');
        }
    }

    private function filter_sql(): void
    {
        if ($kf = $this->session->filter) {
            $this->db->where('enabled', $kf);
        }
    }

    public function paging($p = 1, $o = 0)
    {
        $this->db->select('COUNT(*) as jml');
        $this->list_data_sql();
        $row      = $this->db->get()->row_array();
        $jml_data = $row['jml'];

        $paging          = new Paging();
        $cfg['page']     = $p;
        $cfg['per_page'] = $this->session->per_page;
        $cfg['num_rows'] = $jml_data;
        $paging->init($cfg);

        return $paging;
    }

    private function list_data_sql(): void
    {
        $this->config_id()
            ->from('gambar_gallery')
            ->where('tipe', 0);
        $this->search_sql();
        $this->filter_sql();
    }

    public function list_data($o = 0, $offset = 0, $limit = 500)
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

            case 5: $this->db->order_by('tgl_upload');
                break;

            case 6: $this->db->order_by('tgl_upload', 'DESC');
                break;

            default: $this->db->order_by('urut', 'DESC');
        }

        $this->db->limit($limit, $offset);
        $this->list_data_sql();

        $data = $this->db->get()->result_array();

        $j       = $offset;
        $counter = count($data);

        for ($i = 0; $i < $counter; $i++) {
            $data[$i]['no'] = $j + 1;

            $data[$i]['aktif'] = $data[$i]['enabled'] == 1 ? 'Ya' : 'Tidak';

            $j++;
        }

        return $data;
    }

    public function insert(): void
    {
        session_success();

        $data              = [];
        $data['nama']      = nomor_surat_keputusan($this->input->post('nama')); //pastikan nama album hanya berisi karakter yg diizinkan seperti pada nomor sk
        $data['urut']      = $this->urut_model->urut_max(['parrent' => 0]) + 1;
        $data['config_id'] = $this->config_id;
        $data['jenis']     = $this->input->post('jenis');
        if ($this->input->post('jenis') == 2) {
            $data['gambar'] = $this->input->post('url');
        } else {
            if (UploadError($_FILES['gambar'])) {
                $_SESSION['success'] = -1;

                return;
            }

            $lokasi_file = $_FILES['gambar']['tmp_name'];
            $tipe_file   = TipeFile($_FILES['gambar']);
            // Bolehkan album tidak ada gambar cover
            if (! empty($lokasi_file)) {
                if (! CekGambar($_FILES['gambar'], $tipe_file)) {
                    $_SESSION['success'] = -1;

                    return;
                }
                $nama_file = urldecode(generator(6) . '_' . $_FILES['gambar']['name']);
                $nama_file = strtolower(str_replace(' ', '_', $nama_file));
                UploadGallery($nama_file, '', $tipe_file);
                $data['gambar'] = $nama_file;
            }
        }

        if ($this->session->grup == 4) {
            $data['enabled'] = 2;
        }

        $outp = $this->db->insert('gambar_gallery', $data);
        if (! $outp) {
            session_error();
        }
    }

    public function update($id = 0): void
    {
        session_success();

        $data          = [];
        $data['nama']  = nomor_surat_keputusan($this->input->post('nama')); //pastikan nama album hanya berisi karakter yg diizinkan seperti pada nomor sk
        $data['jenis'] = $this->input->post('jenis');
        if ($this->input->post('jenis') == 2) {
            $data['gambar'] = $this->input->post('url');
        } else {
            if (UploadError($_FILES['gambar'])) {
                $_SESSION['success'] = -1;

                return;
            }

            $lokasi_file = $_FILES['gambar']['tmp_name'];
            $tipe_file   = TipeFile($_FILES['gambar']);
            // Bolehkan album tidak ada gambar cover
            if (! empty($lokasi_file)) {
                if (! CekGambar($_FILES['gambar'], $tipe_file)) {
                    $_SESSION['success'] = -1;

                    return;
                }
                $nama_file = urldecode(generator(6) . '_' . $_FILES['gambar']['name']);
                $nama_file = strtolower(str_replace(' ', '_', $nama_file));
                UploadGallery($nama_file, '', $tipe_file);
                $data['gambar'] = $nama_file;
            }
        }

        if ($this->session->grup == 4) {
            $data['enabled'] = 2;
        }

        $outp = $this->config_id()
            ->where('id', $id)
            ->update('gambar_gallery', $data);

        if (! $outp && $this->db->affected_rows() == '0') {
            session_error();

            return;
        }

        unset($data['old_gambar']);
    }

    public function delete_gallery($id = '', $semua = false): void
    {
        if (! $semua) {
            $this->session->success = 1;
        }

        $this->delete($id);
        $sub_gallery = $this->config_id()
            ->select('id')
            ->where('parrent', $id)
            ->get('gambar_gallery')
            ->result_array();

        foreach ($sub_gallery as $gallery) {
            $this->delete($gallery['id']);
        }
    }

    public function delete_all_gallery(): void
    {
        $this->session->success = 1;

        $id_cb = $this->input->post('id_cb');

        foreach ($id_cb as $id) {
            $this->delete_gallery($id, $semua = true);
        }
    }

    public function delete($id = '', $semua = false): void
    {
        if (! $semua) {
            $this->session->success = 1;
        }
        // Note:
        // Gambar yang dihapus ada kemungkinan dipakai
        // oleh gallery lain, karena ketika mengupload
        // nama file nya belum diubah sesuai dengan
        // judul gallery
        $this->delete_gallery_image($id);

        $outp = $this->config_id()
            ->where('id', $id)
            ->delete('gambar_gallery');

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

    public function delete_gallery_image($id): void
    {
        $image = $this->config_id()
            ->select('gambar')
            ->get_where('gambar_gallery', ['id' => $id])
            ->row()
            ->gambar;
        $prefix = ['kecil_', 'sedang_'];

        foreach ($prefix as $pref) {
            if (is_file(FCPATH . LOKASI_GALERI . $pref . $image)) {
                unlink(FCPATH . LOKASI_GALERI . $pref . $image);
            }
        }
    }

    public function gallery_lock($id = '', $val = 0): void
    {
        // Jangan kunci jika digunakan untuk slider
        if ($val == 1) {
            $this->db
                ->group_start()
                ->where('slider <>', 1)
                ->or_where('slider IS NULL')
                ->group_end();
        }
        $outp = $this->config_id()
            ->where('id', $id)
            ->set('enabled', $val)
            ->update('gambar_gallery');
        status_sukses($outp); //Tampilkan Pesan
    }

    public function gallery_slider($id = '', $val = 0): void
    {
        if ($val == 1) {
            // Hanya satu gallery yang boleh tampil di slider
            $this->config_id()
                ->where('slider', 1)
                ->set('slider', 0)
                ->update('gambar_gallery');
            // Aktifkan galeri kalau digunakan untuk slider
            $this->db->set('enabled', 1);
        }
        $this->config_id()
            ->where('id', $id)
            ->set('slider', $val)
            ->update('gambar_gallery');
    }

    public function get_gallery($id = 0)
    {
        return $this->config_id()
            ->where('id', $id)
            ->get('gambar_gallery')
            ->row_array();
    }

    public function list_slide_galeri()
    {
        $gallery_slide_id = $this->config_id()
            ->select('id')
            ->where('slider', 1)
            ->limit(1)
            ->get('gambar_gallery')
            ->row()
            ->id;

        return $this->config_id()
            ->select('id, nama as judul, gambar')
            ->where('parrent', $gallery_slide_id)
            ->where('tipe', 2)
            ->where('enabled', 1)
            ->order_by('urut ASC')
            ->get('gambar_gallery')
            ->result_array();
    }

    public function paging2($gal = 0, $p = 1)
    {
        $this->db->select('COUNT(*) AS jml');
        $this->list_sub_gallery_sql($gal);
        $row      = $this->db->get()->row_array();
        $jml_data = $row['jml'];

        $paging          = new Paging();
        $cfg['page']     = $p;
        $cfg['per_page'] = $this->session->per_page;
        $cfg['num_rows'] = $jml_data;
        $paging->init($cfg);

        return $paging;
    }

    private function list_sub_gallery_sql($gal)
    {
        $this->config_id()
            ->from('gambar_gallery')
            ->where('parrent', $gal)
            ->where('tipe', 2);
        $this->search_sql();
        $this->filter_sql();

        return $sql;
    }

    public function list_sub_gallery($gal = 1, $o = 0, $offset = 0, $limit = 500)
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

            case 5: $this->db->order_by('tgl_upload');
                break;

            case 6: $this->db->order_by('tgl_upload', 'desc');
                break;

            default: $this->db->order_by('urut');
        }

        $this->db->limit($limit, $offset);
        $this->list_sub_gallery_sql($gal);
        $data    = $this->db->get()->result_array();
        $counter = count($data);

        for ($i = 0; $i < $counter; $i++) {
            $data[$i]['no'] = $i + 1;

            $data[$i]['aktif'] = $data[$i]['enabled'] == 1 ? 'Ya' : 'Tidak';
        }

        return $data;
    }

    public function insert_sub_gallery($parrent = 0): void
    {
        session_success();

        $data          = [];
        $data['nama']  = nomor_surat_keputusan($this->input->post('nama')); //pastikan nama album hanya berisi
        $data['urut']  = $this->urut_model->urut_max(['parrent' => $parrent]) + 1;
        $data['jenis'] = $this->input->post('jenis');
        if ($this->input->post('jenis') == 2) {
            $data['gambar'] = $this->input->post('url');
        } else {
            if (UploadError($_FILES['gambar'])) {
                $_SESSION['success'] = -1;

                return;
            }

            $lokasi_file = $_FILES['gambar']['tmp_name'];
            $tipe_file   = TipeFile($_FILES['gambar']);
            // Bolehkan album tidak ada gambar cover
            if (! empty($lokasi_file)) {
                if (! CekGambar($_FILES['gambar'], $tipe_file)) {
                    $_SESSION['success'] = -1;

                    return;
                }
                $nama_file = urldecode(generator(6) . '_' . $_FILES['gambar']['name']);
                $nama_file = strtolower(str_replace(' ', '_', $nama_file));
                UploadGallery($nama_file, '', $tipe_file);
                $data['gambar'] = $nama_file;
            }
        }

        if ($this->session->grup == 4) {
            $data['enabled'] = 2;
        }

        $data['parrent']   = $parrent;
        $data['tipe']      = 2;
        $data['config_id'] = $this->config_id;
        $outp              = $this->db->insert('gambar_gallery', $data);
        if (! $outp) {
            session_error();
        }
    }

    public function update_sub_gallery($id = 0): void
    {
        session_success();

        $data          = [];
        $data['nama']  = nomor_surat_keputusan($this->input->post('nama')); //pastikan nama album hanya berisi
        $data['jenis'] = $this->input->post('jenis');
        if ($this->input->post('jenis') == 2) {
            $data['gambar'] = $this->input->post('url');
        } else {
            if (UploadError($_FILES['gambar'])) {
                $_SESSION['success'] = -1;

                return;
            }

            $lokasi_file = $_FILES['gambar']['tmp_name'];
            $tipe_file   = TipeFile($_FILES['gambar']);
            // Bolehkan album tidak ada gambar cover
            if (! empty($lokasi_file)) {
                if (! CekGambar($_FILES['gambar'], $tipe_file)) {
                    $_SESSION['success'] = -1;

                    return;
                }
                $nama_file = urldecode(generator(6) . '_' . $_FILES['gambar']['name']);
                $nama_file = strtolower(str_replace(' ', '_', $nama_file));
                UploadGallery($nama_file, '', $tipe_file);
                $data['gambar'] = $nama_file;
            }
        }

        unset($data['old_gambar']);
        $outp = $this->config_id()
            ->where('id', $id)
            ->update('gambar_gallery', $data);
        if (! $outp) {
            session_error();
        }
    }

    // $arah:
    //		1 - turun
    // 		2 - naik
    public function urut($id, $arah, $gallery = ''): void
    {
        $subset = empty($gallery) ? ['parrent' => 0] : ['parrent' => $gallery];
        $this->urut_model->urut($id, $arah, $subset);
    }
}
