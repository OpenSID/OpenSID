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
 * Hak Cipta 2016 - 2026 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright Hak Cipta 2016 - 2026 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

use App\Libraries\Paging;
use App\Models\Config;
use App\Traits\Migrator;
use Illuminate\Support\Facades\DB;

defined('BASEPATH') || exit('No direct script access allowed');

/**
 * @property CI_Benchmark        $benchmark
 * @property CI_Config           $config
 * @property CI_DB_query_builder $db
 * @property CI_DB_forge         $dbforge
 * @property CI_Input            $input
 * @property CI_Lang             $lang
 * @property CI_Loader           $load
 * @property CI_Loader           $loader
 * @property CI_log              $log
 * @property CI_Output           $output
 * @property CI_Router           $router
 * @property CI_Security         $security
 * @property CI_Session          $session
 * @property CI_URI              $uri
 * @property CI_Utf8             $utf8
 */
class MY_Model extends CI_Model
{
    use Migrator;

    public $config_id;

    public function __construct()
    {
        parent::__construct();

        $this->load->dbforge();
        $this->config_id = Config::appKey()->first()->id;
    }

    public function autocomplete_str($kolom, $tabel, $cari = '', $where = '')
    {
        if ($cari) {
            $this->db->like($kolom, $cari);
        }

        if ($where) {
            $this->db->where($where);
        }

        $data = $this->config_id($tabel)
            ->distinct()
            ->select($kolom)
            ->order_by($kolom)
            ->limit(15)
            ->get($tabel)
            ->result_array();

        return autocomplete_data_ke_str($data);
    }

    /**
     * Autocomple str union query.
     *
     * ```php
     * $list_kode = [
     *     ['field_1', $table, $where, $cari],
     *     ['field_2', $table, $where, $cari],
     *     ['field_3', $table, $where, $cari],
     * ];
     *
     * $joins = [
     *     [$table2, "{$table2}.id = {$table}.id", "right"],
     * ];
     * ```
     *
     * @param array $list_kode
     * @param array $joins
     *
     * @return array
     */
    public function union($list_kode = [], $joins = [])
    {
        $sql = [];

        foreach ($list_kode as $kode) {
            if ($joins) {
                foreach ($joins as $val) {
                    [$join, $cond, $type] = $val;

                    $this->db->join($join, $cond, $type);
                }
            }

            [$kolom, $table, $where, $cari] = $kode;

            $sql[] = "({$this->config_id($table)->select($kolom)->from($table)->where($where)->like($kolom, $cari)->order_by($kolom, 'desc')->get_compiled_select()})";
        }

        $sql = implode('UNION', $sql);

        return $this->db->query($sql)->result_array();
    }

    public function grupAkses($id_grup, $id_modul, $akses, $config_id = null)
    {
        $insert = [
            'id_grup'  => $id_grup,
            'id_modul' => $id_modul,
            'akses'    => $akses,
        ];

        if ($this->db->field_exists('config_id', 'grup_akses')) {
            $insert['config_id'] = $config_id ?? $this->config_id;
        }

        return $this->db->insert('grup_akses', $insert);
    }

    // fungsi untuk format paginasi
    public function paginasi($page = 1, $jml_data = 0)
    {
        $paging           = new Paging();
        $cfg['page']      = $page;
        $cfg['per_page']  = $this->session->per_page ?? 10;
        $cfg['num_links'] = 10;
        $cfg['num_rows']  = $jml_data;
        $paging->init($cfg);

        return $paging;
    }

    /**
     * Scope config_id berdasarkan desa.
     *
     * @param mixed $fields_config_id
     *
     * @return CI_DB_query_builder
     */
    public function config_id(?string $alias = null, bool $boleh_null = false)
    {
        $this->db->group_start();
        if ($alias) {
            $this->db->where("{$alias}.config_id", $this->config_id);

            if ($boleh_null) {
                $this->db->or_where("{$alias}.config_id", null);
            }
        } else {
            $this->db->where('config_id', $this->config_id);

            if ($boleh_null) {
                $this->db->or_where('config_id', null);
            }
        }
        $this->db->group_end();

        return $this->db;
    }
}
