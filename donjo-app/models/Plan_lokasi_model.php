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

class Plan_lokasi_model extends MY_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function autocomplete()
    {
        return $this->autocomplete_str('nama', 'lokasi');
    }

    private function search_sql()
    {
        if (isset($_SESSION['cari'])) {
            $cari       = $_SESSION['cari'];
            $kw         = $this->db->escape_like_str($cari);
            $kw         = '%' . $kw . '%';
            $search_sql = " AND l.nama LIKE '{$kw}'";

            return $search_sql;
        }
    }

    private function filter_sql()
    {
        if (isset($_SESSION['filter'])) {
            $kf         = $_SESSION['filter'];
            $filter_sql = " AND l.enabled = {$kf}";

            return $filter_sql;
        }
    }

    private function point_sql()
    {
        if (isset($_SESSION['point'])) {
            $kf        = $_SESSION['point'];
            $point_sql = " AND p.id = {$kf}";

            return $point_sql;
        }
    }

    private function subpoint_sql()
    {
        if (isset($_SESSION['subpoint'])) {
            $kf           = $_SESSION['subpoint'];
            $subpoint_sql = " AND m.id = {$kf}";

            return $subpoint_sql;
        }
    }

    public function paging($p = 1, $o = 0)
    {
        $sql      = 'SELECT COUNT(l.id) AS id ' . $this->list_data_sql();
        $query    = $this->db->query($sql);
        $row      = $query->row_array();
        $jml_data = $row['id'];

        $this->load->library('paging');
        $cfg['page']     = $p;
        $cfg['per_page'] = $_SESSION['per_page'];
        $cfg['num_rows'] = $jml_data;
        $this->paging->init($cfg);

        return $this->paging;
    }

    private function list_data_sql()
    {
        $sql = '
			FROM lokasi l
			LEFT JOIN point p ON l.ref_point = p.id
			LEFT JOIN point m ON p.parrent = m.id
			WHERE 1 ';

        $sql .= $this->search_sql();
        $sql .= $this->filter_sql();
        $sql .= $this->point_sql();
        $sql .= $this->subpoint_sql();

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

        $sql = 'SELECT l.*, p.nama AS kategori, m.nama AS jenis, p.simbol AS simbol ' . $this->list_data_sql();
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
        $data['nama']      = nomor_surat_keputusan($post['nama']);
        $data['ref_point'] = $post['ref_point'];
        $data['desk']      = htmlentities($post['desk']);
        $data['enabled']   = $post['enabled'];

        return $data;
    }

    public function insert()
    {
        $data        = $this->validasi($this->input->post());
        $lokasi_file = $_FILES['foto']['tmp_name'];
        $tipe_file   = $_FILES['foto']['type'];
        $nama_file   = $_FILES['foto']['name'];
        $nama_file   = str_replace(' ', '-', $nama_file); 	 // normalkan nama file
        if (! empty($lokasi_file)) {
            if ($tipe_file == 'image/jpg' || $tipe_file == 'image/jpeg') {
                UploadLokasi($nama_file);
                $data['foto'] = $nama_file;
                $outp         = $this->db->insert('lokasi', $data);
            }
        } else {
            unset($data['foto']);
            $outp = $this->db->insert('lokasi', $data);
        }

        if ($outp) {
            $_SESSION['success'] = 1;
        } else {
            $_SESSION['success'] = -1;
        }
    }

    public function update($id = 0)
    {
        $data        = $this->validasi($this->input->post());
        $lokasi_file = $_FILES['foto']['tmp_name'];
        $tipe_file   = $_FILES['foto']['type'];
        $nama_file   = $_FILES['foto']['name'];
        $nama_file   = str_replace(' ', '-', $nama_file); 	 // normalkan nama file
        if (! empty($lokasi_file)) {
            if ($tipe_file == 'image/jpg' || $tipe_file == 'image/jpeg') {
                UploadLokasi($nama_file);
                $data['foto'] = $nama_file;
                $this->db->where('id', $id);
                $outp = $this->db->update('lokasi', $data);
            }
        } else {
            unset($data['foto']);
            $this->db->where('id', $id);
            $outp = $this->db->update('lokasi', $data);
        }
        status_sukses($outp); //Tampilkan Pesan
    }

    public function delete($id = '', $semua = false)
    {
        if (! $semua) {
            $this->session->success = 1;
        }

        $outp = $this->db->where('id', $id)->delete('lokasi');

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

    public function list_point()
    {
        $sql = 'SELECT * FROM point WHERE tipe = 2 AND enabled = 1';

        if (isset($_SESSION['subpoint'])) {
            $kf = $_SESSION['subpoint'];
            $sql .= " AND parrent = {$kf}";
        }

        $query = $this->db->query($sql);
        $data  = $query->result_array();

        return $data;
    }

    public function list_subpoint()
    {
        $sql = 'SELECT * FROM point WHERE tipe = 0 AND enabled = 1';

        if (isset($_SESSION['point'])) {
            $sqlx  = 'SELECT * FROM point WHERE id = ?';
            $query = $this->db->query($sqlx, $_SESSION['point']);
            $temp  = $query->row_array();

            $kf = $temp['parrent'];
        }

        $query = $this->db->query($sql);
        $data  = $query->result_array();

        return $data;
    }

    public function lokasi_lock($id = '', $val = 0)
    {
        $sql  = 'UPDATE lokasi SET enabled = ? WHERE id = ?';
        $outp = $this->db->query($sql, [$val, $id]);

        status_sukses($outp); //Tampilkan Pesan
    }

    public function get_lokasi($id = 0)
    {
        return $this->db->where('id', $id)
            ->get('lokasi')->row_array();
    }

    public function update_position($id = 0)
    {
        $data['lat'] = koordinat($this->input->post('lat'));
        $data['lng'] = koordinat($this->input->post('lng'));
        $this->db->where('id', $id);
        $outp = $this->db->update('lokasi', $data);

        status_sukses($outp); //Tampilkan Pesan
    }

    public function list_lokasi()
    {
        return $this->db
            ->select('l.*, p.nama AS kategori, m.nama AS jenis, p.simbol AS simbol')
            ->from('lokasi l')
            ->join('point p', 'l.ref_point = p.id', 'left')
            ->join('point m', 'p.parrent = m.id', 'left')
            ->where('l.enabled = 1')
            ->where('p.enabled = 1')
            ->where('m.enabled = 1')
            ->get()->result_array();
    }
}
