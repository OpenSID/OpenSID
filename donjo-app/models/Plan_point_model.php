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

class Plan_point_model extends MY_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function autocomplete()
    {
        return $this->autocomplete_str('nama', 'point');
    }

    private function search_sql()
    {
        if (isset($_SESSION['cari'])) {
            $cari       = $_SESSION['cari'];
            $kw         = $this->db->escape_like_str($cari);
            $kw         = '%' . $kw . '%';
            $search_sql = " AND (nama LIKE '{$kw}')";

            return $search_sql;
        }
    }

    private function filter_sql()
    {
        if (isset($_SESSION['filter'])) {
            $kf         = $_SESSION['filter'];
            $filter_sql = " AND enabled = {$kf}";

            return $filter_sql;
        }
    }

    public function paging($p = 1, $o = 0)
    {
        $sql      = 'SELECT COUNT(*) AS jml ' . $this->list_data_sql();
        $query    = $this->db->query($sql);
        $row      = $query->row_array();
        $jml_data = $row['jml'];

        $this->load->library('paging');
        $cfg['page']     = $p;
        $cfg['per_page'] = $_SESSION['per_page'];
        $cfg['num_rows'] = $jml_data;
        $this->paging->init($cfg);

        return $this->paging;
    }

    private function list_data_sql()
    {
        $sql = ' FROM point WHERE tipe = 0 ';
        $sql .= $this->search_sql();
        $sql .= $this->filter_sql();

        return $sql;
    }

    public function list_data($o = 0, $offset = 0, $limit = 1000)
    {
        switch ($o) {
            case 1: $order_sql = ' ORDER BY nama';
                break;

            case 2: $order_sql = ' ORDER BY nama DESC';
                break;

            case 3: $order_sql = ' ORDER BY enabled';
                break;

            case 4: $order_sql = ' ORDER BY enabled DESC';
                break;

            default:$order_sql = ' ORDER BY id';
        }

        $paging_sql = ' LIMIT ' . $offset . ',' . $limit;

        $sql = 'SELECT * ' . $this->list_data_sql();
        $sql .= $order_sql;
        $sql .= $paging_sql;

        $query = $this->db->query($sql);
        $data  = $query->result_array();

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
        $data = $this->validasi($this->input->post());
        $outp = $this->db->insert('point', $data);

        status_sukses($outp); //Tampilkan Pesan
    }

    public function update($id = 0)
    {
        $data = $this->validasi($this->input->post());
        $this->db->where('id', $id);
        $outp = $this->db->update('point', $data);

        status_sukses($outp); //Tampilkan Pesan
    }

    public function delete($id = '', $semua = false)
    {
        if (! $semua) {
            $this->session->success = 1;
        }

        $outp = $this->db->where('id', $id)->delete('point');

        status_sukses($outp, $gagal_saja = true); //Tampilkan Pesan
    }

    public function delete_all()
    {
        $this->session->success = 1;

        $id_cb = $_POST['id_cb'];

        foreach ($id_cb as $id) {
            $this->delete($id, $semua = true);
        }
    }

    public function list_sub_point($point = 1)
    {
        $sql = 'SELECT * FROM point WHERE parrent = ? AND tipe = 2 ';

        $query = $this->db->query($sql, $point);
        $data  = $query->result_array();

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
        $data            = $this->validasi($this->input->post());
        $data['parrent'] = $parrent;
        $data['tipe']    = 2;
        $outp            = $this->db->insert('point', $data);
        status_sukses($outp); //Tampilkan Pesan
    }

    public function update_sub_point($id = 0)
    {
        $data = $this->validasi($this->input->post());
        $this->db->where('id', $id);
        $outp = $this->db->update('point', $data);
        status_sukses($outp); //Tampilkan Pesan
    }

    public function delete_sub_point($id = '', $semua = false)
    {
        if (! $semua) {
            $this->session->success = 1;
        }

        $outp = $this->db->where('id', $id)->delete('point');

        status_sukses($outp, $gagal_saja = true); //Tampilkan Pesan
    }

    public function delete_all_sub_point()
    {
        $this->session->success = 1;

        $id_cb = $_POST['id_cb'];

        foreach ($id_cb as $id) {
            $this->delete_sub_point($id, $semua = true);
        }
    }

    public function point_lock($id = '', $val = 0)
    {
        $sql  = 'UPDATE point SET enabled = ? WHERE id = ?';
        $outp = $this->db->query($sql, [$val, $id]);

        status_sukses($outp); //Tampilkan Pesan
    }

    public function get_point($id = 0)
    {
        $sql   = 'SELECT * FROM point WHERE id = ?';
        $query = $this->db->query($sql, $id);

        return $query->row_array();
    }

    public function list_simbol()
    {
        $sql   = 'SELECT * FROM gis_simbol WHERE 1';
        $query = $this->db->query($sql);

        return $query->result_array();
    }

    public function tambah_simbol()
    {
        $vdir_upload  = LOKASI_SIMBOL_LOKASI;
        $vfile_upload = $vdir_upload . basename($_FILES['simbol']['name']);
        $fupload_name = basename($_FILES['simbol']['name']);

        $error = periksa_file('simbol', unserialize(MIME_TYPE_SIMBOL), unserialize(EXT_SIMBOL));
        if ($error != '') {
            $this->session->set_userdata('success', -1);
            $this->session->set_userdata('error_msg', $error);

            return null;
        }
        move_uploaded_file($_FILES['simbol']['tmp_name'], $vfile_upload);

        $im_src     = imagecreatefrompng($vfile_upload);
        $src_width  = imageSX($im_src);
        $src_height = imageSY($im_src);
        if (($src_width * 3) < ($src_height * 3)) {
            $dst_width  = 32;
            $dst_height = ($dst_width / $src_width) * $src_height;
            $cut_height = $dst_height - 32;

            $im = imagecreatetruecolor(32, 32);
            imagecopyresampled($im, $im_src, 0, 0, 0, $cut_height, $dst_width, $dst_height, $src_width, $src_height);
        } else {
            $dst_height = 32;
            $dst_width  = ($dst_height / $src_height) * $src_width;
            $cut_width  = $dst_width - 32;

            $im = imagecreatetruecolor(32, 32);
            imagecopyresampled($im, $im_src, 0, 0, $cut_width, 0, $dst_width, $dst_height, $src_width, $src_height);
        }

        imagepng($im, $vdir_upload . $fupload_name);
        $data['simbol'] = basename($_FILES['simbol']['name']);
        $outp           = $this->db->insert('gis_simbol', $data);
        status_sukses($outp);
    }

    public function delete_simbol($id = '')
    {
        $outp = $this->db->where('id', $id)->delete('gis_simbol');
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
                    $outp           = $outp && copy($source, $destination);
                    $data['simbol'] = basename($file);
                    $sql            = $this->db->insert_string('gis_simbol', $data) . ' ON DUPLICATE KEY UPDATE simbol = VALUES(simbol)';
                    $outp           = $outp && $this->db->query($sql);
                }
            }
        }
        status_sukses($outp);
    }
}
