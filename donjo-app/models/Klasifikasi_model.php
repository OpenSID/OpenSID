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

class Klasifikasi_model extends MY_model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function autocomplete()
    {
        return $this->autocomplete_str('nama', 'klasifikasi_surat');
    }

    public function search_sql()
    {
        if (isset($_SESSION['cari'])) {
            $cari       = $_SESSION['cari'];
            $kw         = $this->db->escape_like_str($cari);
            $kw         = '%' . $kw . '%';
            $search_sql = " AND (u.nama LIKE '{$kw}' OR u.uraian LIKE '{$kw}')";

            return $search_sql;
        }
    }

    public function filter_sql()
    {
        if (isset($_SESSION['filter'])) {
            $kf         = $_SESSION['filter'];
            $filter_sql = " AND enabled = {$kf}";

            return $filter_sql;
        }
    }

    public function paging($p = 1, $o = 0)
    {
        $list_data_sql = $this->list_data_sql($log);
        $sql           = 'SELECT COUNT(u.id) AS jml ' . $list_data_sql;
        $query         = $this->db->query($sql);
        $row           = $query->row_array();
        $jml_data      = $row['jml'];

        $this->load->library('paging');
        $cfg['page']     = $p;
        $cfg['per_page'] = $_SESSION['per_page'];
        $cfg['num_rows'] = $jml_data;
        $this->paging->init($cfg);

        return $this->paging;
    }

    // Digunakan untuk paging dan query utama supaya jumlah data selalu sama
    private function list_data_sql()
    {
        $sql = '
			FROM klasifikasi_surat u
			WHERE 1';

        $sql .= $this->search_sql();
        $sql .= $this->filter_sql();

        return $sql;
    }

    public function list_data($o = 0, $offset = 0, $limit = 500)
    {
        $select_sql = 'SELECT * ';
        //Main Query
        $list_data_sql = $this->list_data_sql();
        $sql           = $select_sql . ' ' . $list_data_sql;

        //Ordering SQL
        switch ($o) {
            case 1: $order_sql = ' ORDER BY u.kode * 1';
                break;

            case 2: $order_sql = ' ORDER BY u.kode * 1 DESC';
                break;

            case 3: $order_sql = ' ORDER BY u.nama';
                break;

            case 4: $order_sql = ' ORDER BY u.nama DESC';
                break;

            default:$order_sql = ' ORDER BY u.kode * 1';
        }

        //Paging SQL
        $paging_sql = ' LIMIT ' . $offset . ',' . $limit;

        $sql .= $order_sql;
        $sql .= $paging_sql;

        $query = $this->db->query($sql);
        $data  = $query->result_array();
        //Formating Output
        $j = $offset;

        for ($i = 0; $i < count($data); $i++) {
            $data[$i]['no'] = $j + 1;
            $j++;
        }

        return $data;
    }

    // Ambil kode yang aktif untuk ditampilkan di form surat_masuk
    public function list_kode()
    {
        return $this->db->select('kode, nama')->
                where('enabled', '1')->
                order_by('kode')->
                get('klasifikasi_surat')->result_array();
    }

    public function insert()
    {
        $data = $_POST;
        $this->sterilkan_data($data);

        return $this->db->insert('klasifikasi_surat', $data);
    }

    private function sterilkan_data(&$data)
    {
        $data['kode']   = alfanumerik_titik($data['kode']);
        $data['nama']   = alfa_spasi($data['nama']);
        $data['uraian'] = strip_tags($data['uraian']);
    }

    public function update($id = 0)
    {
        $data = $_POST;
        $this->sterilkan_data($data);

        return $this->db->where('id', $id)->update('klasifikasi_surat', $data);
    }

    public function delete($id = '', $semua = false)
    {
        if (! $semua) {
            $this->session->success = 1;
        }

        $outp = $this->db->where('id', $id)->delete('klasifikasi_surat');

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

    public function lock($id = '', $val = 0)
    {
        $outp = $this->db->where('id', $id)->update('klasifikasi_surat', ['enabled' => $val]);
        if ($outp) {
            $_SESSION['success'] = 1;
        } else {
            $_SESSION['success'] = -1;
        }
    }

    public function get_klasifikasi($id = 0)
    {
        return $this->db->where('id', $id)->get('klasifikasi_surat')->row_array();
    }

    /**
     * Hapus tabel klasifikasi_surat dan ganti isinya
     * dengan data dari berkas csv.
     * Baris pertama berisi nama kolom tabel.
     *
     * @param mixed $file
     */
    public function impor($file)
    {
        ini_set('auto_detect_line_endings', '1');
        if (($handle = fopen($file, 'rb')) == false) {
            $_SESSION['success']   = -1;
            $_SESSION['error_msg'] = 'Berkas tidak ada atau bermasalah';

            return;
        }
        $this->db->trans_start();
        $this->db->truncate('klasifikasi_surat');
        $header    = fgetcsv($handle);
        $jml_kolom = count($header);

        while (($csv = fgetcsv($handle)) !== false) {
            $data = [];

            for ($c = 0; $c < $jml_kolom; $c++) {
                $data[$header[$c]] = $csv[$c];
            }
            $this->db->insert('klasifikasi_surat', $data);
        }
        $this->db->trans_complete();
        fclose($handle);
        $_SESSION['success'] = 1;
    }
}
