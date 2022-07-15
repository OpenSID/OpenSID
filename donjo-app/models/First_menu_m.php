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

class First_menu_m extends MY_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    private function list_submenu($parrent = 0)
    {
        $data = $this->db->where(['parrent' => $parrent, 'enabled' => 1])->order_by('urut')->get('menu')->result_array();

        for ($i = 0; $i < count($data); $i++) {
            // 99 adalah link eksternal
            if ($data[$i]['link_tipe'] != 99) {
                $data[$i]['link'] = $this->menu_slug($data[$i]['link']);
            }
        }

        return $data;
    }

    public function list_menu_atas()
    {
        $data = $this->db->where(['parrent' => 0, 'enabled' => 1])->order_by('urut')->get('menu')->result_array();

        for ($i = 0; $i < count($data); $i++) {
            // 99 adalah link eksternal
            if ($data[$i]['link_tipe'] != 99) {
                $data[$i]['link'] = $this->menu_slug($data[$i]['link']);
            }
            $data[$i]['submenu'] = $this->list_submenu($data[$i]['id']);
        }

        return $data;
    }

    private function list_kategori($parrent = 0)
    {
        return $this->db
            ->where('enabled', 1)
            ->where('parrent', $parrent)
            ->order_by('urut')
            ->get('kategori')
            ->result_array();
    }

    public function list_menu_kiri()
    {
        $data = $this->list_kategori();

        foreach ($data as $key => $sub_menu) {
            $data[$key]['submenu'] = $this->list_kategori($sub_menu['id']);
        }

        return $data;
    }
}
