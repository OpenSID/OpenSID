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

define('EKSTENSI_WAJIB', serialize([
    'curl',
    'fileinfo',
    'gd',
    'iconv',
    'json',
    'mbstring',
    'mysqli',
    'mysqlnd',
    'tidy',
    'zip',
]));
define('minPhpVersion', '7.3.0');
define('maxPhpVersion', '8.0.0');
define('minMySqlVersion', '5.6.0');
define('maxMySqlVersion', '8.0.0');
define('minMariaDBVersion', '10.3.0');

class Setting_model extends MY_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function init()
    {
        $pre = [];
        $CI  = &get_instance();

        if ($this->setting || ! $this->db->table_exists('setting_aplikasi')) {
            return;
        }

        if ($this->config->item('useDatabaseConfig')) {
            $pr = $this->db
                ->order_by('key')
                ->get('setting_aplikasi')
                ->result();

            foreach ($pr as $p) {
                $pre[addslashes($p->key)] = trim(addslashes($p->value));
            }
        } else {
            $pre = (object) $CI->config->config;
        }
        $CI->setting      = (object) $pre;
        $CI->list_setting = $pr; // Untuk tampilan daftar setting
        $this->apply_setting();
    }

    // Setting untuk PHP
    private function apply_setting()
    {
        //  https://stackoverflow.com/questions/16765158/date-it-is-not-safe-to-rely-on-the-systems-timezone-settings
        date_default_timezone_set($this->setting->timezone); // ganti ke timezone lokal

        // Ambil google api key dari desa/config/config.php kalau tidak ada di database
        if (empty($this->setting->mapbox_key)) {
            $this->setting->mapbox_key = config_item('mapbox_key');
        }

        // Ganti token_layanan sesuai config untuk development untuk mempermudah rilis
        if ((ENVIRONMENT == 'development') || config_item('token_layanan')) {
            $this->setting->layanan_opendesa_token = config_item('token_layanan');
        }

        $this->setting->user_admin = config_item('user_admin');
        // Kalau folder tema ubahan tidak ditemukan, ganti dengan tema default
        $pos = strpos($this->setting->web_theme, 'desa/');
        if ($pos !== false) {
            $folder = FCPATH . '/desa/themes/' . substr($this->setting->web_theme, $pos + strlen('desa/'));
            if (! file_exists($folder)) {
                $this->setting->web_theme = 'esensi';
            }
        }
        $this->load->model('database_model');
        $this->database_model->cek_migrasi();
    }

    public function update_setting($data)
    {
        foreach ($data as $key => $value) {
            // Update setting yang diubah
            if ($this->setting->{$key} != $value) {
                if ($key == 'current_version') {
                    continue;
                }

                $value = strip_tags($value);

                if ($key == 'ip_adress_kehadiran' || $key == 'mac_adress_kehadiran') {
                    $value = trim($value);
                }

                if ($key == 'id_pengunjung_kehadiran') {
                    $value = alfanumerik(trim($value));
                }

                $this->update($key, $value);
                $this->setting->{$key} = $value;
                if ($key == 'enable_track') {
                    $this->notifikasi_tracker();
                }
            }
        }
        $this->apply_setting();
        // TODO : Jika sudah dipisahkan, buat agar upload gambar dinamis/bisa menyesuaikan dengan kebutuhan tema (u/ Modul Pengaturan Tema)
        if ($data['latar_website'] != '') {
            $this->upload_img('latar_website', $this->theme_model->lokasi_latar_website(str_replace('desa/', '', $this->setting->web_theme)));
        } // latar_website
        if ($data['latar_login'] != '') {
            $this->upload_img('latar_login', LATAR_LOGIN);
        } // latar_login
        if ($data['latar_login_mandiri'] != '') {
            $this->upload_img('latar_login_mandiri', LATAR_LOGIN);
        } // latar_login_mandiri

        return $data;
    }

    public function upload_img($key = '', $lokasi = '')
    {
        $this->load->library('upload');

        $config['upload_path']   = $lokasi;
        $config['allowed_types'] = 'jpg|jpeg|png';
        $config['overwrite']     = true;
        $config['max_size']      = max_upload() * 1024;
        $config['file_name']     = $key . '.jpg';

        $this->upload->initialize($config);

        if ($this->upload->do_upload($key)) {
            $this->upload->data();

            return $lokasi . $config['file_name'];
        }

        session_error($this->upload->display_errors());

        return false;
    }

    private function notifikasi_tracker()
    {
        if ($this->setting->enable_track == 0) {
            // Notifikasi tracker dimatikan
            $notif = [
                'updated_at'     => date('Y-m-d H:i:s'),
                'tgl_berikutnya' => date('Y-m-d H:i:s'),
                'aktif'          => 1,
            ];
        } else {
            // Matikan notifikasi tracker yg sdh aktif
            $notif = [
                'updated_at' => date('Y-m-d H:i:s'),
                'aktif'      => 0,
            ];
        }
        $this->db->where('kode', 'tracking_off')->update('notifikasi', $notif);
    }

    public function update($key = 'enable_track', $value = 1)
    {
        if (in_array($key, ['latar_kehadiran'])) {
            $value = $this->upload_img('latar_kehadiran', LATAR_KEHADIRAN);
        }

        $outp = $this->db->where('key', $key)->update('setting_aplikasi', ['key' => $key, 'value' => $value]);

        // Hapus Cache
        $this->cache->hapus_cache_untuk_semua('status_langganan');
        $this->cache->hapus_cache_untuk_semua('setting_aplikasi');
        $this->cache->hapus_cache_untuk_semua('_cache_modul');

        status_sukses($outp);
    }

    public function aktifkan_tracking()
    {
        $outp = $this->db->where('key', 'enable_track')->update('setting_aplikasi', ['value' => 1]);
        $this->cache->hapus_cache_untuk_semua('setting_aplikasi');

        status_sukses($outp);
    }

    public function update_slider()
    {
        $_SESSION['success']                 = 1;
        $this->setting->sumber_gambar_slider = $this->input->post('pilihan_sumber');
        $outp                                = $this->db->where('key', 'sumber_gambar_slider')->update('setting_aplikasi', ['value' => $this->input->post('pilihan_sumber')]);
        $this->cache->hapus_cache_untuk_semua('setting_aplikasi');

        if (! $outp) {
            $_SESSION['success'] = -1;
        }
    }

    /*
        Input post:
        - jenis_server dan server_mana menentukan setting penggunaan_server
        - offline_mode dan offline_mode_saja menentukan setting offline_mode
    */
    public function update_penggunaan_server()
    {
        $_SESSION['success']              = 1;
        $mode                             = $this->input->post('offline_mode_saja');
        $this->setting->offline_mode      = ($mode === '0' || $mode) ? $mode : $this->input->post('offline_mode');
        $out1                             = $this->db->where('key', 'offline_mode')->update('setting_aplikasi', ['value' => $this->setting->offline_mode]);
        $penggunaan_server                = $this->input->post('server_mana') ?: $this->input->post('jenis_server');
        $this->setting->penggunaan_server = $penggunaan_server;
        $out2                             = $this->db->where('key', 'penggunaan_server')->update('setting_aplikasi', ['value' => $penggunaan_server]);
        $this->cache->hapus_cache_untuk_semua('setting_aplikasi');

        if (! $out1 || ! $out2) {
            $_SESSION['success'] = -1;
        }
    }

    public function load_options()
    {
        foreach ($this->list_setting as $i => $set) {
            if (in_array($set->jenis, ['option', 'option-value', 'option-kode'])) {
                $this->list_setting[$i]->options = $this->get_options($set->id);
            }
        }
    }

    private function get_options($id)
    {
        return $this->db->select('id, kode, value')
            ->where('id_setting', $id)
            ->get('setting_aplikasi_options')
            ->result();
    }

    public function cekEkstensi()
    {
        $e = get_loaded_extensions();
        usort($e, 'strcasecmp');
        $ekstensi = array_flip($e);
        $e        = unserialize(EKSTENSI_WAJIB);
        usort($e, 'strcasecmp');
        $ekstensi_wajib = array_flip($e);
        $lengkap        = true;

        foreach ($ekstensi_wajib as $key => $value) {
            $ekstensi_wajib[$key] = isset($ekstensi[$key]);
            $lengkap              = $lengkap && $ekstensi_wajib[$key];
        }
        $data['lengkap']  = $lengkap;
        $data['ekstensi'] = $ekstensi_wajib;

        return $data;
    }

    public function disableFunctions()
    {
        $wajib    = ['exec'];
        $disabled = explode(',', ini_get('disable_functions'));

        $functions = [];
        $lengkap   = true;

        foreach ($wajib as $fuc) {
            $functions[$fuc] = (in_array($fuc, $disabled)) ? false : true;
            $lengkap         = $lengkap && $functions[$fuc];
        }

        $data['lengkap']   = $lengkap;
        $data['functions'] = $functions;

        return $data;
    }

    public function cekPhp()
    {
        return [
            'versi' => PHP_VERSION,
            'cek'   => (version_compare(PHP_VERSION, minPhpVersion) > 0 && version_compare(PHP_VERSION, maxPhpVersion) < 0),
        ];
    }

    public function cekDatabase()
    {
        $versi = $this->db->query('SELECT VERSION() AS version')->row()->version;

        return [
            'versi' => $versi,
            'cek'   => (version_compare($versi, minMySqlVersion) > 0 && version_compare($versi, minMySqlVersion) > 0) || (version_compare($versi, minMariaDBVersion) > 0),
        ];
    }
}
