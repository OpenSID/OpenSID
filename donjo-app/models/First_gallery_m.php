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

class First_gallery_m extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function paging($p = 1)
    {
        $sql      = "SELECT COUNT(id) AS id FROM gambar_gallery WHERE enabled = 1 AND tipe='0'";
        $query    = $this->db->query($sql);
        $row      = $query->row_array();
        $jml_data = $row['id'];

        $this->load->library('paging');
        $cfg['page']     = $p;
        $cfg['per_page'] = 10;
        $cfg['num_rows'] = $jml_data;
        $this->paging->init($cfg);

        return $this->paging;
    }

    // daftar album galeri
    public function gallery_show($offset = 0, $limit = 50)
    {
        // OPTIMIZE: benarkah butuh paging?
        $paging_sql = ' LIMIT ' . $offset . ',' . $limit;

        $sql = "SELECT * FROM gambar_gallery
			WHERE enabled = 1 AND tipe ='0'
			ORDER BY urut";
        $sql .= $paging_sql;

        $query = $this->db->query($sql);
        $data  = $query->result_array();
        // Untuk album yang tidak ada gambar cover, cari gambar di sub-gallery
        for ($i = 0; $i < count($data); $i++) {
            if ($data[$i]['gambar'] == '') {
                $galeri             = $data[$i]['id'];
                $sql                = "SELECT gambar FROM gambar_gallery WHERE ((enabled = '1') AND ((parrent = '" . $galeri . "') OR (id = '" . $galeri . "')) AND (gambar<>'')) LIMIT 1";
                $query              = $this->db->query($sql);
                $row                = $query->row_array();
                $data[$i]['gambar'] = $row['gambar'];
            }
        }

        return $data;
    }

    public function paging2($gal = 0, $p = 1)
    {
        $sql      = "SELECT COUNT(id) AS id FROM gambar_gallery WHERE enabled = 1 AND (parrent = '{$gal}')";
        $query    = $this->db->query($sql, $gal);
        $row      = $query->row_array();
        $jml_data = $row['id'];

        $this->load->library('paging');
        $cfg['page']     = $p;
        $cfg['per_page'] = 10;
        $cfg['num_rows'] = $jml_data;
        $this->paging->init($cfg);

        return $this->paging;
    }

    // daftar gambar di tiap album
    public function sub_gallery_show($gal = 0, $offset = 0, $limit = 50)
    {
        $paging_sql = ' LIMIT ' . $offset . ',' . $limit;
        $sql        = "SELECT * FROM gambar_gallery
			WHERE ((enabled = '1') AND (parrent = '" . $gal . "'))
			ORDER BY urut
			";
        $sql .= $paging_sql;

        $query = $this->db->query($sql);

        return $query->result_array();
    }

    public function get_parent($parent)
    {
        $sql   = "SELECT * FROM gambar_gallery WHERE id = '{$parent}'";
        $query = $this->db->query($sql);

        return $query->row_array();
    }

    // daftar album di widget
    public function gallery_widget()
    {
        $sql   = "SELECT * FROM gambar_gallery WHERE enabled = '1' and parrent = 0 order by rand() limit 4";
        $query = $this->db->query($sql);

        return $query->result_array();
    }
}
