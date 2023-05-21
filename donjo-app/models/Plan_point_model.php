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

defined('BASEPATH') || exit('No direct script access allowed');

class Plan_point_model extends MY_Model
{
    public function autocomplete()
    {
        return $this->autocomplete_str('nama', 'point');
    }

    private function search_sql()
    {
        if ($cari = $this->session->cari) {
            $this->db->like('nama', $cari);
        }
    }

    private function filter_sql()
    {
        if ($filter = $this->session->filter) {
            $this->db->where('enabled', $filter);
        }
    }

    public function paging($p = 1, $o = 0)
    {
        $this->list_data_sql();
        $row      = $this->db->select('count(*) as jml')->get()->row_array();
        $jml_data = $row['jml'];

        $this->load->library('paging');
        $cfg['page']     = $p;
        $cfg['per_page'] = $this->session->per_page;
        $cfg['num_rows'] = $jml_data;
        $this->paging->init($cfg);

        return $this->paging;
    }

    private function list_data_sql()
    {
        $this->config_id()
            ->from('point')
            ->where('tipe', 0);

        $this->search_sql();
        $this->filter_sql();
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
            ->select('*')
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
        $data['nama']   = nomor_surat_keputusan($post['nama']);
        $data['simbol'] = $post['simbol'];

        return $data;
    }

    public function insert()
    {
        $data              = $this->validasi($this->input->post());
        $data['config_id'] = identitas('id');
        $outp              = $this->db->insert('point', $data);

        status_sukses($outp); //Tampilkan Pesan
    }

    public function update($id = 0)
    {
        $data = $this->validasi($this->input->post());
        $outp = $this->config_id()->where('id', $id)->update('point', $data);

        status_sukses($outp); //Tampilkan Pesan
    }

    public function delete($id = '', $semua = false)
    {
        if (! $semua) {
            $this->session->success = 1;
        }

        $outp = $this->config_id()->where('id', $id)->delete('point');

        status_sukses($outp, $gagal_saja = true); //Tampilkan Pesan
    }

    public function delete_all()
    {
        $this->session->success = 1;

        $id_cb = $this->input->post('id_cb');

        foreach ($id_cb as $id) {
            $this->delete($id, $semua = true);
        }
    }

    public function list_sub_point($point = 1)
    {
        $data = $this->config_id()
            ->from('point')
            ->where('parrent', $point)
            ->where('tipe', 2)
            ->get()
            ->result_array();

        for ($i = 0; $i < count($data); $i++) {
            $data[$i]['no'] = $i + 1;
            if ($data[$i]['enabled'] == 1) {
                $data[$i]['aktif'] = 'Ya';
            } else {
                $data[$i]['aktif'] = 'Tidak';
            }
        }

        return $data;
    }

    public function insert_sub_point($parrent = 0)
    {
        $data              = $this->validasi($this->input->post());
        $data['config_id'] = identitas('id');
        $data['parrent']   = $parrent;
        $data['tipe']      = 2;
        $outp              = $this->db->insert('point', $data);
        status_sukses($outp); //Tampilkan Pesan
    }

    public function update_sub_point($id = 0)
    {
        $data = $this->validasi($this->input->post());
        $outp = $this->config_id()->where('id', $id)->update('point', $data);

        status_sukses($outp); //Tampilkan Pesan
    }

    public function delete_sub_point($id = '', $semua = false)
    {
        if (! $semua) {
            $this->session->success = 1;
        }

        $outp = $this->config_id()->where('id', $id)->delete('point');

        status_sukses($outp, $gagal_saja = true); //Tampilkan Pesan
    }

    public function delete_all_sub_point()
    {
        $this->session->success = 1;

        $id_cb = $this->input->post('id_cb');

        foreach ($id_cb as $id) {
            $this->delete_sub_point($id, $semua = true);
        }
    }

    public function point_lock($id = '', $val = 0)
    {
        $outp = $this->config_id()
            ->where('id', $id)
            ->update('point', ['enabled' => $val]);

        status_sukses($outp); //Tampilkan Pesan
    }

    public function get_point($id = 0)
    {
        return $this->config_id()
            ->where('id', $id)
            ->get('point')
            ->row_array();
    }

    public function list_simbol()
    {
        return $this->config_id()
            ->get('gis_simbol')
            ->result_array();
    }

    public function tambah_simbol()
    {
        $config['upload_path']   = LOKASI_SIMBOL_LOKASI;
        $config['allowed_types'] = 'gif|jpg|png|jpeg';
        $this->load->library('upload');
        $this->upload->initialize($config);

        if (! $this->upload->do_upload('simbol')) {
            session_error($this->upload->display_errors());

            return;
        }

        $uploadedImage = $this->upload->data();
        ResizeGambar($uploadedImage['full_path'], $uploadedImage['full_path'], ['width' => 32, 'height' => 32]); // ubah ukuran gambar

        $data['simbol']    = $uploadedImage['file_name'];
        $data['config_id'] = identitas('id');
        $outp              = $this->db->insert('gis_simbol', $data);
        status_sukses($outp);
    }

    public function delete_simbol($id = '')
    {
        $outp = $this->config_id()->where('id', $id)->delete('gis_simbol');
        status_sukses($outp);
    }

    public function delete_simbol_file($simbol = '')
    {
        $target_dir  = LOKASI_SIMBOL_LOKASI;
        $target_file = $target_dir . $simbol;

        if (file_exists($target_file)) {
            $outp = unlink($target_file);
        }
        status_sukses($outp);
    }

    public function salin_simbol_default()
    {
        $dir     = LOKASI_SIMBOL_LOKASI_DEF;
        $files   = scandir($dir);
        $new_dir = LOKASI_SIMBOL_LOKASI;
        $outp    = true;

        foreach ($files as $file) {
            if (! empty($file) && $file != '.' && $file != '..') {
                $source      = $dir . '/' . $file;
                $destination = $new_dir . '/' . $file;
                if (! file_exists($destination)) {
                    $outp              = $outp && copy($source, $destination);
                    $data['simbol']    = basename($file);
                    $data['config_id'] = identitas('id');
                    $sql               = $this->db->insert_string('gis_simbol', $data) . ' ON DUPLICATE KEY UPDATE simbol = VALUES(simbol), config_id = VALUES(config_id)';
                    $outp              = $outp && $this->db->query($sql);
                }
            }
        }
        status_sukses($outp);
    }
}
