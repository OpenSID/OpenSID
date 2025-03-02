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
use App\Models\Config;
use App\Models\FormatSurat;
use App\Models\SettingAplikasi;
use App\Traits\Migrator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

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

    public function tambahIndeks($tabel, $kolom, $index = 'UNIQUE', $multi = false)
    {
        if ($index == 'UNIQUE') {
            $kolomStr = $kolom . ' ,count(*) as jumlah';

            $duplikat = $this->db
                ->select($kolomStr)
                ->from($tabel)
                ->group_by($kolom)
                ->having('jumlah > 1')
                ->get()
                ->num_rows();
            if ($duplikat > 0) {
                session_error('--> Silakan Cek <a href="' . site_url('info_sistem') . '">Info Sistem > Log</a>.');
                log_message('error', "Data kolom {$kolom} pada tabel {$tabel} ada yang duplikat dan perlu diperbaiki sebelum migrasi dilanjutkan.");

                return false;
            }
        }

        $unique_name = preg_replace('/[^a-zA-Z0-9_-]+/i', '', $kolom);
        if (! $this->cek_indeks($tabel, $unique_name)) {
            if ($multi == true && $index == 'UNIQUE') {
                return $this->db->query("ALTER TABLE `{$tabel}` ADD UNIQUE INDEX `{$unique_name}` ({$kolom})");
            }

            return $this->db->query("ALTER TABLE {$tabel} ADD {$index} {$kolom} (`{$kolom}`)");
        }

        return true;
    }

    public function cek_indeks($tabel, $kolom)
    {
        $db = $this->db->database;

        return $this->db
            ->select('COUNT(index_name) as ada')
            ->from('INFORMATION_SCHEMA.STATISTICS')
            ->where('table_schema', $db)
            ->where('table_name', $tabel)
            ->where('index_name', $kolom)
            ->get()->row()->ada > 0;
    }

    public function tambah_modul($modul)
    {
        $this->createModul($modul);

        return true;
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

    /**
     * Ubah modul setting menu.
     *
     * @param mixed $where
     *
     * @return bool
     */
    public function ubah_modul($where, array $modul)
    {
        if (is_array($where)) {
            $this->db->where($where);
        } else {
            $this->db->where('id', $where);
        }

        $this->db->update('setting_modul', $modul);

        // TODO:: Ganti ini dengan cache prefix "{$grupId}_admin_menu"
        cache()->flush();

        return true;
    }

    public function tambah_setting($setting, $config_id = null)
    {
        cache()->forget('identitas_desa');

        if (Schema::hasColumn('setting_aplikasi', 'config_id')) {
            $cek = SettingAplikasi::withoutGlobalScope(App\Scopes\ConfigIdScope::class)->where('config_id', $config_id ?? $this->config_id)->where('key', $setting['key']);

            if ($cek->exists()) {
                unset($setting['value']);
                $cek->update($setting);
            } else {
                $setting['config_id'] = $config_id ?? $this->config_id;
                $cek->insert($setting);
            }
        } else {
            $sql   = $this->db->insert_string('setting_aplikasi', $setting) . ' ON DUPLICATE KEY UPDATE keterangan = VALUES(keterangan), jenis = VALUES(jenis), kategori = VALUES(kategori)';
            $hasil = $this->db->query($sql);
        }

        (new SettingAplikasi())->flushQueryCache();

        return true;
    }

    public function tambah_surat_tinymce($data, $config_id = null)
    {
        $config_id ??= $this->config_id;
        $data['url_surat']    = 'surat-' . url_title($data['nama'], '-', true);
        $data['jenis']        = FormatSurat::TINYMCE_SISTEM;
        $data['syarat_surat'] = json_encode($data['syarat_surat'], JSON_THROW_ON_ERROR);
        $data['created_by']   = ci_auth()->id;
        $data['updated_by']   = ci_auth()->id;
        $data['config_id']    = $config_id;
        if (is_array($data['form_isian'])) {
            $data['form_isian'] = json_encode($data['form_isian'], JSON_THROW_ON_ERROR);
        }
        if (is_array($data['kode_isian'])) {
            $data['kode_isian'] = json_encode($data['kode_isian'], JSON_THROW_ON_ERROR);
        }

        // Tambah data baru dan update (hanya kolom template) jika ada sudah ada
        $cek_surat = DB::table('tweb_surat_format')->where('config_id', $config_id)->where('url_surat', $data['url_surat']);

        if ($cek_surat->exists()) {
            $cek_surat->update(['template' => $data['template']]);
        } else {
            DB::table('tweb_surat_format')->insert($data);
        }

        return true;
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
     * Tambah kolom config_id di tabel.
     *
     * @param string $tabel
     * @param bool   $null
     * @param string $after
     *
     * @return bool
     */
    public function tambah_config_id($tabel, $after = 'id')
    {
        $hasil = true;

        if (! $this->db->field_exists('config_id', $tabel)) {
            $hasil = $hasil && $this->dbforge->add_column($tabel, [
                'config_id' => [
                    'type'       => 'INT',
                    'constraint' => 11,
                    'null'       => true,
                    'after'      => $after,
                    'default'    => null,
                ],
            ]);

            // Isi data tabel $tabel kolom config_id
            if ($this->config_id) {
                DB::table($tabel)->where('config_id', 0)->orWhere('config_id', null)->update(['config_id' => DB::table('config')->first()->id]);
            }

            // Hapus data dengan config_id = null
            // DB::table($tabel)->where('config_id', 0)->orWhere('config_id', null)->delete();
        }

        return $hasil && $this->tambahForeignKey("{$tabel}_config_fk", $tabel, 'config_id', 'config', 'id');

        // return $hasil && $this->dbforge->modify_column($tabel, [
        //     'config_id' => [
        //         'type'       => 'INT',
        //         'constraint' => 11,
        //         'null'       => false,
        //     ],
        // ]);
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

    // TODO:: Cek variabel $berulang
    public function data_awal(?string $tabel = null, array $data = [], $berulang = false)
    {
        // reset_auto_increment($tabel);

        $config_id = $this->config_id;

        if ($this->db->table_exists($tabel) && $data !== []) {
            collect($data)
                ->chunk(100)
                // tambahkan config_id terlebih dahulu
                ->map(static fn ($chunk) => $chunk->map(static function (array $item) use ($config_id): array {
                    $item['config_id'] = $config_id;

                    return $item;
                }))
                ->each(static function ($chunk) use ($tabel): void {
                    // upsert agar tidak duplikat
                    DB::table($tabel)->upsert($chunk->all(), 'config_id');
                });
            log_message('notice', 'Berhasil memperbarui data awal tabel ' . $tabel);

            return true;
        }

        return false;
    }

    // Hapus FOREIGN KEY $tabel, $nama_constraint
    public function hapus_foreign_key($tabel, $nama_constraint, $drop)
    {
        $query = $this->db
            ->from('INFORMATION_SCHEMA.REFERENTIAL_CONSTRAINTS')
            ->where('CONSTRAINT_SCHEMA', $this->db->database)
            ->where('REFERENCED_TABLE_NAME', $tabel)
            ->where('CONSTRAINT_NAME', $nama_constraint)
            ->get();

        $hasil = true;
        if ($query->num_rows() > 0) {
            try {
                $hasil = DB::query("ALTER TABLE {$drop} DROP FOREIGN KEY IF EXISTS {$nama_constraint}");
            } catch (Exception $e) {
                log_message('error', $e->getMessage());
            }

            return true;
        }

        return $hasil;
    }

    public function cek_primary_key($tabel, $kolom = [])
    {
        $schemaManager = DB::connection()->getDoctrineSchemaManager();
        $indexes       = $schemaManager->listTableIndexes($tabel);

        $isPrimaryKey = false;

        foreach ($indexes as $index) {
            if ($index->isPrimary() && $index->getColumns() == $kolom) {
                $isPrimaryKey = true;
                break;
            }
        }

        return $isPrimaryKey;
    }
}

function checkAndFixTable($tableName)
{
    $table = DB::table($tableName)->first();
    if ($table) {
        $kolom_id = DB::select("SHOW COLUMNS FROM {$tableName} WHERE Field = 'id' AND Extra = 'auto_increment'");
        $pk       = DB::select("SHOW INDEX FROM {$tableName} WHERE Key_name = 'PRIMARY'");

        if (! $kolom_id || ! $pk) {
            DB::statement("ALTER TABLE {$tableName} ADD PRIMARY KEY (id)");
            DB::statement("ALTER TABLE {$tableName} MODIFY id INT AUTO_INCREMENT");
        }
    }

    return true;
}
