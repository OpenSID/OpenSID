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

use App\Models\Config;
use App\Models\FormatSurat;
use App\Models\Migrasi;
use App\Models\SettingAplikasi;
use App\Models\User;
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
    public $config_id;

    public function __construct()
    {
        parent::__construct();

        $this->load->driver('cache', ['adapter' => 'file', 'backup' => 'dummy']);
        $this->load->dbforge();
        $this->config_id = Config::appKey()->first()->id;
    }

    public function autocomplete_str($kolom, $tabel, $cari = '')
    {
        if ($cari) {
            $this->db->like($kolom, $cari);
        }

        $data = $this->config_id_exist($tabel)
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

            $sql[] = "({$this->config_id_exist($table, $table)->select($kolom)->from($table)->where($where)->like($kolom, $cari)->order_by($kolom, 'desc')->get_compiled_select()})";
        }

        $sql = implode('UNION', $sql);

        return $this->db->query($sql)->result_array();
    }

    public function hapus_indeks($tabel, $indeks)
    {
        if ($this->cek_indeks($tabel, $indeks)) {
            return $this->db->query("DROP INDEX {$indeks} ON {$tabel}");
        }

        return true;
    }

    public function tambahIndeks($tabel, $kolom, $index = 'UNIQUE', $multi = false)
    {
        if ($index == 'UNIQUE') {
            $duplikat = $this->db
                ->select("CONCAT({$kolom}) AS jmlh")
                ->from($tabel)
                ->group_by('jmlh')
                ->having('COUNT(jmlh) > 1')
                ->get()
                ->num_rows();

            if ($duplikat > 0) {
                session_error('--> Silahkan Cek <a href="' . site_url('info_sistem') . '">Info Sistem > Log</a>.');
                log_message('error', "Data kolom {$kolom} pada tabel {$tabel} ada yang duplikat dan perlu diperbaiki sebelum migrasi dilanjutkan.");
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
        // Modul
        $sql = $this->db->insert_string('setting_modul', $modul) . ' ON DUPLICATE KEY UPDATE modul = VALUES(modul), url = VALUES(url), ikon = VALUES(ikon), hidden = VALUES(hidden), urut = VALUES(urut), parent = VALUES(parent)';

        $hasil = $this->db->query($sql);

        // Hak Akses Default Operator
        // Hanya lakukan jika tabel grup_akses sudah ada. Tabel ini belum ada sebelum Migrasi_fitur_premium_2105.php
        if ($this->db->table_exists('grup_akses')) {
            $hasil = $hasil && $this->grupAkses(2, $modul['id'], 3);
        }

        // Hapus cache menu navigasi
        $this->cache->hapus_cache_untuk_semua('_cache_modul');

        return $hasil;
    }

    public function grupAkses($id_grup, $id_modul, $akses)
    {
        return $this->db->insert('grup_akses', ['id_grup' => $id_grup, 'id_modul' => $id_modul, 'akses' => $akses]);
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

        // Hapus cache menu navigasi
        $this->cache->hapus_cache_untuk_semua('_cache_modul');

        return true;
    }

    public function tambah_setting($setting, $config_id = null)
    {
        hapus_cache('identitas_desa');

        if (Schema::hasColumn('setting_aplikasi', 'config_id')) {
            $cek = SettingAplikasi::withoutGlobalScope('App\Scopes\ConfigIdScope')->where('config_id', $config_id ?? $this->config_id)->where('key', $setting['key']);

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

        hapus_cache('setting_aplikasi');

        return true;
    }

    public function tambah_surat_tinymce($data)
    {
        $data['url_surat']    = 'surat-' . strtolower(str_replace([' ', '_'], '-', $data['nama']));
        $data['jenis']        = FormatSurat::TINYMCE_SISTEM;
        $data['syarat_surat'] = json_encode($data['syarat_surat']);
        $data['created_by']   = auth()->id;
        $data['updated_by']   = auth()->id;

        // Tambah data baru dan update (hanya kolom template) jika ada sudah ada
        $cek_surat = DB::table('tweb_surat_format')->where('url_surat', $data['url_surat']);

        if (Schema::hasColumn('tweb_surat_format', 'config_id')) {
            $cek_surat->where('config_id', $this->config_id);
            $data['config_id'] = $this->config_id;
        }

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
        $this->load->library('paging');
        $cfg['page']      = $page;
        $cfg['per_page']  = $this->session->per_page ?? 10;
        $cfg['num_links'] = 10;
        $cfg['num_rows']  = $jml_data;
        $this->paging->init($cfg);

        return $this->paging;
    }

    // Buat FOREIGN KEY $nama_constraint $di_tbl untuk $fk menunjuk $ke_tbl di $ke_kolom
    public function tambahForeignKey($nama_constraint, $di_tbl, $fk, $ke_tbl, $ke_kolom)
    {
        $query = $this->db
            ->where('CONSTRAINT_SCHEMA', $this->db->database)
            ->where('TABLE_NAME', $di_tbl)
            ->where('CONSTRAINT_NAME', $nama_constraint)
            ->where('REFERENCED_TABLE_NAME', $ke_tbl)
            ->get('INFORMATION_SCHEMA.REFERENTIAL_CONSTRAINTS');

        $hasil = true;

        //pastikan engine yang dipakai innoDB
        $q_check = "SHOW TABLE STATUS WHERE Name in('{$di_tbl}', '{$ke_tbl}') and ENGINE != 'InnoDB'";

        $cek_engine = $this->db->query($q_check)->result();
        if ($cek_engine) {
            foreach ($cek_engine as $table) {
                $q_set_engine = 'ALTER TABLE ' . $table->Name . ' ENGINE = InnoDB'; //query untuk ubah ke innoDB;
                $this->db->query($q_set_engine);
            }
        }

        if ($query->num_rows() == 0) {
            $hasil = $hasil && $this->dbforge->add_column($di_tbl, [
                "CONSTRAINT `{$nama_constraint}` FOREIGN KEY (`{$fk}`) REFERENCES `{$ke_tbl}` (`{$ke_kolom}`) ON DELETE CASCADE ON UPDATE CASCADE",
            ]);
        }

        return $hasil;
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
            $hasil = $hasil && $this->db->query("ALTER TABLE `{$drop}` DROP FOREIGN KEY `{$nama_constraint}`");
        }

        return $hasil;
    }

    public function jalankan_migrasi($migrasi, $cek_app_key = true)
    {
        if ($cek_app_key && $this->db->field_exists('app_key', 'config')) {
            return true;
        }

        if (is_array($this->session->daftar_migrasi) && in_array($migrasi, $this->session->daftar_migrasi)) {
            return true;
        }

        $this->load->model('migrations/' . $migrasi);
        if ($this->{$migrasi}->up()) {
            log_message('notice', 'Berhasil Jalankan ' . $migrasi);

            $_SESSION['daftar_migrasi'][] = $migrasi;

            return true;
        }

        log_message('error', 'Gagal Jalankan ' . $migrasi);

        return false;
    }

    public function timestamps($table = '', $creator = false)
    {
        $hasil  = true;
        $fields = [];

        // Kolom created_at
        if (! $this->db->field_exists('created_at', $table)) {
            $fields[] = 'created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP';
        }

        // Kolom created_by
        if ($creator && ! $this->db->field_exists('created_by', $table)) {
            $fields['created_by'] = [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => true,
            ];
        }

        // Kolom updated_at
        if (! $this->db->field_exists('updated_at', $table)) {
            $fields[] = 'updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP';
        }

        // Kolom updated_by
        if ($creator && ! $this->db->field_exists('updated_by', $table)) {
            $fields['updated_by'] = [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => true,
            ];
        }

        if ($fields) {
            $hasil = $hasil && $this->dbforge->add_column($table, $fields);
        }

        // Update created_by dan updated_by jika kosong
        $user = User::select('id')->where('id_grup', 1)->first();

        if ($this->db->field_exists('created_by', $table)) {
            DB::table($table)->whereNull('created_by')->update(['created_by' => $user->id]);
        }

        if ($this->db->field_exists('created_by', $table)) {
            DB::table($table)->whereNull('updated_by')->update(['updated_by' => $user->id]);
        }

        return $hasil;
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
            DB::table($tabel)->where('config_id', 0)->orWhere('config_id', null)->delete();
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

    // Buat ulang indexes di tabel $tabel
    public function buat_ulang_index($tabel, $unique_name, $unique_colom, $index = 'UNIQUE')
    {
        $hasil = true;

        // Hapus index nik pada tabel tweb_penduduk
        // Tambahkan unique index pada kolom config_id dan nik pada tabel tweb_penduduk
        if ($this->cek_indeks($tabel, $unique_name) && ! $this->cek_indeks($tabel, $unique_name . '_config')) {
            $hasil = $hasil && $this->db->query("ALTER TABLE `{$tabel}` DROP INDEX `{$unique_name}`, ADD {$index} INDEX `{$unique_name}_config` {$unique_colom}");
        }

        return $hasil;
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

    /**
     * Scope config_id exist
     *
     * @return CI_DB_query_builder
     */
    public function config_id_exist(string $table, ?string $alias = null, bool $boleh_null = false)
    {
        if ($this->db->field_exists('config_id', $table)) {
            return $this->config_id($alias, $boleh_null);
        }

        return $this->db;
    }

    public function data_awal(?string $tabel = null, array $data = [], $berulang = false)
    {
        $config_id = $this->config_id;

        // Cek apakah migrasi berulang dan sudah ada data sebelumnya
        if ($berulang == false && DB::table($tabel)->where('config_id', $this->config_id)->count() > 0) {
            return true;
        }

        if ($this->db->table_exists($tabel) && count($data) > 0) {
            collect($data)
                ->chunk(100)
                // tambahkan config_id terlebih dahulu
                ->map(static function ($chunk) use ($config_id) {
                    return $chunk->map(static function ($item) use ($config_id) {
                        $item['config_id'] = $config_id;

                        return $item;
                    });
                })
                ->each(static function ($chunk) use ($tabel) {
                    DB::table($tabel)->insertOrIgnore($chunk->all());
                });

            return true;
        }

        return false;
    }
}
