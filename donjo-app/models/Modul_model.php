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
 * Hak Cipta 2016 - 2024 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright Hak Cipta 2016 - 2024 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

use App\Models\Modul;

defined('BASEPATH') || exit('No direct script access allowed');

class Modul_model extends MY_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('user_model');
    }

    public function list_data()
    {
        $this->search_sql();
        $this->filter_sql();

        $data = $this->config_id()
            ->where('hidden', 0)
            ->where('parent', 0)
            ->order_by('urut')
            ->get('setting_modul')
            ->result_array();
        $counter = count($data);

        for ($i = 0; $i < $counter; $i++) {
            $data[$i]['no']       = $i + 1;
            $data[$i]['submodul'] = $this->list_sub_modul($data[$i]['id']);
        }

        return $data;
    }

    // Menampilkan menu dan sub menu halaman pengguna login berdasarkan daftar modul dan sub modul yang aktif.
    public function list_aktif()
    {
        $aktif = [];

        if (empty($_SESSION['grup'])) {
            return [];
        }

        $aktif[] = [
            'modul' => 'Beranda',
            'slug'  => 'beranda',
            'url'   => 'beranda',
            'ikon'  => 'fa-dashboard',
        ];

        $data = $this->config_id()
            ->where('aktif', 1)
            ->where('parent', 0)
            ->order_by('urut')
            ->get('setting_modul')
            ->result_array();
        $counter = count($data);

        for ($i = 0; $i < $counter; $i++) {
            if ($this->ada_sub_modul($data[$i]['id'])) {
                $data[$i]['modul']    = str_replace('[Pemerintah Desa]', ucwords(setting('sebutan_pemerintah_desa')), SebutanDesa($data[$i]['modul']));
                $data[$i]['submodul'] = $this->list_sub_modul_aktif($data[$i]['id']);
                // Kelompok submenu yg kosong tidak dimasukkan
                if (! empty($data[$i]['submodul']) || ! can('b')) {
                    $aktif[] = $data[$i];
                }
            } elseif (can('b', $data[$i]['slug'])) {
                // Modul yang tidak boleh diakses tidak dimasukkan
                $data[$i]['modul'] = str_replace('[Pemerintah Desa]', ucwords(setting('sebutan_pemerintah_desa')), SebutanDesa($data[$i]['modul']));
                $aktif[]           = $data[$i];
            }
        }

        return $aktif;
    }

    private function ada_sub_modul($modul_id)
    {
        $jml = $this->config_id()
            ->select("count('id') as jml")
            ->where('parent', $modul_id)
            ->get('setting_modul')
            ->row()
            ->jml;

        return $jml > 0;
    }

    private function list_sub_modul_aktif($modul_id)
    {
        $this->db->where('aktif', 1);
        $data  = $this->list_sub_modul($modul_id);
        $aktif = [];

        foreach ($data as $sub_modul) {
            // Modul yang tidak boleh diakses tidak dimasukkan
            if (can('b', $sub_modul['slug'])) {
                $aktif[] = $sub_modul;
            }
        }

        return $aktif;
    }

    // Menampilkan tabel sub modul
    public function list_sub_modul($modul_id)
    {
        // jangan aktifkan jika demo dan di domain whitelist
        if (config_item('demo_mode') && in_array(get_domain(APP_URL), WEBSITE_DEMO)) {
            $this->db->where_not_in('slug', [
                'layanan-pelanggan',
                'pendaftaran-kerjasama',
            ]);
        }

        $data = $this->config_id()
            ->where('parent', $modul_id)
            ->where('hidden <>', 2)
            ->order_by('urut')
            ->get('setting_modul')
            ->result_array();
        $counter = count($data);

        for ($i = 0; $i < $counter; $i++) {
            $data[$i]['no']    = $i + 1;
            $data[$i]['modul'] = str_replace('[Pemerintah Desa]', ucwords(setting('sebutan_pemerintah_desa')), SebutanDesa($data[$i]['modul']));
        }

        return $data;
    }

    public function autocomplete()
    {
        $status = $this->session->status;

        if ($status != '') {
            $this->db->where('aktif', $status);
        }

        $data = $this->config_id()
            ->select('modul')
            ->where('hidden', 0)
            ->where('parent', 0)
            ->order_by('modul')
            ->get('setting_modul')
            ->result_array();

        return autocomplete_data_ke_str($data);
    }

    private function search_sql(): void
    {
        if ($cari = $this->session->cari) {
            $this->db->grup_start()
                ->like('modul', $cari)
                ->or_like('url', $cari)
                ->grup_end();
        }
    }

    private function filter_sql(): void
    {
        if ($status = $this->session->status) {
            $this->db->where('aktif', $status);
        }
    }

    public function get_data($id)
    {
        return $this->config_id()->get_where('setting_modul', ['id' => $id])->row_array();
    }

    public function update($id): void
    {
        $data          = $this->input->post();
        $data['modul'] = strip_tags($data['modul']);
        $data['ikon']  = strip_tags($data['ikon']);

        $outp = $this->config_id()->where('id', $id)->update('setting_modul', $data);
        $this->lock($id, $data['aktif']);
        $this->cache->hapus_cache_untuk_semua('_cache_modul');

        status_sukses($outp); //Tampilkan Pesan
    }

    private function set_aktif_submodul(int $id, int $aktif)
    {
        $outp = true;

        $submodul      = $this->config_id()->select('id')->where('parent', $id)->get('setting_modul')->result_array();
        $list_submodul = array_column($submodul, 'id');
        if ($list_submodul === []) {
            return;
        }

        foreach ($submodul as $modul) {
            $sub           = $this->config_id()->select('id')->where('parent', $modul['id'])->get('setting_modul')->result_array();
            $list_submodul = array_merge($list_submodul, array_column($sub, 'id'));
        }
        $list_id = implode(',', $list_submodul);
        $outp    = $outp && $this->config_id()->where('id IN (' . $list_id . ')')->update('setting_modul', ['aktif' => $aktif]);
        $this->cache->hapus_cache_untuk_semua('_cache_modul');

        return $outp;
    }

    /*
        Setting modul yang diaktifkan sesuai dengan setting penggunaan_server,
        dan setting online_mode

        penggunaan_server:
        1 - offline saja di kantor desa
        2 - online saja di hosting
        3 - [lebih spesifik di 5 dan 6]
        4 - offline dan online di kantor desa
        5 - offline di kantor desa, dan ada online di hosting
        6 - online di hosting, dan ada offline di kantor desa

        offline_mode:
        0 - web bisa diakses publik
        1 - web hanya bisa diakses petugas web
        2 - web non-aktif sama sekali
    */
    public function default_server(): void
    {
        $outp = true;

        switch ($this->setting->penggunaan_server) {
            case '1':
            case '5':
                $outp = $outp && $this->config_id()->update('setting_modul', ['aktif' => 1]);
                // Kalau web tidak diaktifkan sama sekali, non-aktifkan modul Admin Web
                if ($this->setting->offline_mode == 2) {
                    $modul_web = 13;
                    $outp      = $outp && $this->config_id()->where('id', $modul_web)->update('setting_modul', ['aktif' => 0]);
                    $outp      = $outp && $this->set_aktif_submodul($modul_web, 0);
                }
                break;

            case '6':
                // Online digunakan hanya untuk publikasi web; admin penduduk dan lain-lain
                // dilakukan offline di kantor desa. Yaitu, hanya modul Admin Web yang aktif
                // Kecuali Pengaturan selalu aktif
                $modul_pengaturan = 11;
                $outp             = $outp && $this->config_id()->where('id <>', $modul_pengaturan)
                    ->where('parent <>', $modul_pengaturan)
                    ->update('setting_modul', ['aktif' => 0]);
                $modul_web = 13;
                $outp      = $outp && $this->config_id()->where('id', $modul_web)->update('setting_modul', ['aktif' => 1]);
                $outp      = $outp && $this->set_aktif_submodul($modul_web, 1);
                break;

            default:
                // semua modul aktif
                $outp = $outp && $this->config_id()->update('setting_modul', ['aktif' => 1]);
                break;
        }
        $this->cache->hapus_cache_untuk_semua('_cache_modul');

        status_sukses($outp);
    }

    public function modul_aktif($controller)
    {
        if (in_array($controller, Modul::SELALU_AKTIF)) {
            return true;
        }

        // Periksa apakah modulnya aktif atau tidak
        $aktif = $this->config_id()
            ->select('url')
            ->where('aktif', 1)
            ->get('setting_modul')
            ->result_array();

        foreach ($aktif as $key => $modul) {
            // url ada yg berbentuk 'modul/clear'
            $aktif[$key] = explode('/', $modul['url'])[0];
        }

        return in_array($controller, $aktif);
    }

    /**
     * @param $id  id
     * @param $val status : 1 = Unlock, 2 = Lock
     */
    public function lock($id, $val): void
    {
        $outp = $this->config_id()
            ->where('id', $id)
            ->or_where('parent', $id)
            ->update('setting_modul', ['aktif' => $val]);
        $this->cache->hapus_cache_untuk_semua('_cache_modul');

        status_sukses($outp);
    }

    public function list_icon()
    {
        $list_icon = [];

        $file = FCPATH . 'assets/fonts/fontawesome.txt';

        if (file_exists($file)) {
            $list_icon = file_get_contents($file);
            $list_icon = explode('.', $list_icon);

            return array_map(static fn ($a): string => explode(':', $a)[0], $list_icon);
        }

        return false;
    }
}
