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

class Plan_point_model extends MY_Model
{
    public function autocomplete()
    {
        return $this->autocomplete_str('nama', 'point');
    }

    private function search_sql(): void
    {
        if ($cari = $this->session->cari) {
            $this->db->like('nama', $cari);
        }
    }

    private function filter_sql(): void
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

        $j       = $offset;
        $counter = count($data);

        for ($i = 0; $i < $counter; $i++) {
            $data[$i]['no'] = $j + 1;

            $data[$i]['aktif'] = $data[$i]['enabled'] == 1 ? 'Ya' : 'Tidak';

            $j++;
        }

        return $data;
    }

    public function list_sub_point($point = 1)
    {
        $data = $this->config_id()
            ->from('point')
            ->where('parrent', $point)
            ->where('tipe', 2)
            ->get()
            ->result_array();
        $counter = count($data);

        for ($i = 0; $i < $counter; $i++) {
            $data[$i]['no']    = $i + 1;
            $data[$i]['aktif'] = $data[$i]['enabled'] == 1 ? 'Ya' : 'Tidak';
        }

        return $data;
    }

    public function point_lock($id = '', $val = 0): void
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
}
