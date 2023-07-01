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

use App\Models\RefJabatan;
use App\Models\SettingAplikasi;
use Illuminate\Support\Facades\Schema;

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
        $CI = &get_instance();

        if ($this->setting || ! $this->db->table_exists('setting_aplikasi')) {
            return;
        }

        $CI->list_setting = SettingAplikasi::orderBy('key')->get();
        $CI->setting      = (object) SettingAplikasi::pluck('value', 'key')->toArray();

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

        // Ganti token_layanan sesuai config untuk mempermudah development
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

        // Sebutan kepala desa diambil dari tabel ref_jabatan dengan id = 1
        // Diperlukan karena masih banyak yang menggunakan variabel ini, hapus jika tidak digunakan lagi
        $this->setting->sebutan_kepala_desa = (Schema::hasTable('ref_jabatan')) ? RefJabatan::find(1)->nama : '';

        // Sebutan sekretaris desa diambil dari tabel ref_jabatan dengan id = 2
        $this->setting->sebutan_sekretaris_desa = (Schema::hasTable('ref_jabatan')) ? RefJabatan::find(2)->nama : '';

        $this->load->model('database_model');
        $this->database_model->cek_migrasi();
    }

    public function update_setting($data)
    {
        $hasil = true;

        // TODO : Jika sudah dipisahkan, buat agar upload gambar dinamis/bisa menyesuaikan dengan kebutuhan tema (u/ Modul Pengaturan Tema)
        if ($data['latar_website'] != '') {
            $hasil = $hasil && $this->upload_img('latar_website', $this->theme_model->lokasi_latar_website(str_replace('desa/', '', $this->setting->web_theme)), $this->setting->latar_website);
        }

        if ($data['latar_login'] != '') {
            $hasil = $hasil && $this->upload_img('latar_login', LATAR_LOGIN, $this->setting->latar_login);
        }

        if ($data['latar_login_mandiri'] != '') {
            $hasil = $hasil && $this->upload_img('latar_login_mandiri', LATAR_LOGIN, $this->setting->latar_login_mandiri);
        }

        if ($this->setting->latar_website) {
            $data['latar_website'] = $this->setting->latar_website;
        }

        if ($this->setting->latar_login) {
            $data['latar_login'] = $this->setting->latar_login;
        }

        if ($this->setting->latar_login_mandiri) {
            $data['latar_login_mandiri'] = $this->setting->latar_login_mandiri;
        }

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

                if ($key == 'api_opendk_key' && (empty(setting('api_opendk_server')) || empty(setting('api_opendk_user')) || empty(setting('api_opendk_password')))) {
                    $value = null;
                }

                $hasil                 = $hasil && $this->update($key, $value);
                $this->setting->{$key} = $value;
                if ($key == 'enable_track') {
                    $hasil = $hasil && $this->notifikasi_tracker();
                }
            }
        }
        $this->apply_setting();

        return $hasil;
    }

    public function upload_img($key = '', $lokasi = '', $latar_old = '')
    {
        $this->load->library('MY_Upload', null, 'upload');

        $config['upload_path']   = $lokasi;
        $config['allowed_types'] = 'jpg|jpeg|png';
        $config['overwrite']     = true;
        $config['max_size']      = max_upload() * 1024;
        $config['file_name']     = time() . $key . '.jpg';
        $data['value']           = $config['file_name'];

        $this->upload->initialize($config);

        if ($this->upload->do_upload($key)) {
            $this->upload->data();

            if ($latar_old) {
                unlink($lokasi . $latar_old); // hapus file yang sebelumya
            }

            if ($key . '.jpg') {
                unlink($lokasi . $key . '.jpg'); // hapus file yang sebelumya
            }

            $this->db->where('key', $key)->update('setting_aplikasi', $data); // simpan ke database

            return $lokasi . $config['file_name']; // simpan ke path
        }

        set_session('flash_error_msg', $this->upload->display_errors(null, null));

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

        return true;
    }

    public function update($key = 'enable_track', $value = 1)
    {
        if (in_array($key, ['latar_kehadiran'])) {
            $value = $this->upload_img('latar_kehadiran', LATAR_LOGIN, null);
        }

        if ($key == 'tte' && $value == 1) {
            $this->db->where('key', 'verifikasi_kades')->update('setting_aplikasi', ['value' => 1]); // jika tte aktif, aktifkan juga verifikasi kades
        }

        $outp = $this->db->where('key', $key)->update('setting_aplikasi', ['key' => $key, 'value' => $value]);

        // Hapus Cache
        // $this->cache->hapus_cache_untuk_semua('status_langganan');
        $this->cache->hapus_cache_untuk_semua('setting_aplikasi');
        $this->cache->hapus_cache_untuk_semua('_cache_modul');

        status_sukses($outp);

        return true;
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

    public function cekKebutuhanSistem()
    {
        $data = [];

        $sistem = [
            ['max_execution_time', '>=', '300'],
            ['post_max_size', '>=', '10M'],
            ['upload_max_filesize', '>=', '20M'],
            ['memory_limit', '>=', '256M'],
        ];

        foreach ($sistem as $value) {
            [$key, $kondisi, $val] = $value;

            $data[$key] = [
                'v'      => $val,
                $key     => ini_get($key),
                'result' => version_compare(ini_get($key), $val, $kondisi),
            ];
        }

        return $data;
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
            'cek'   => (version_compare(PHP_VERSION, minPhpVersion, '>=') && version_compare(PHP_VERSION, maxPhpVersion, '<')),
        ];
    }

    public function cekDatabase()
    {
        $versi = $this->db->query('SELECT VERSION() AS version')->row()->version;

        return [
            'versi' => $versi,
            'cek'   => (version_compare($versi, minMySqlVersion, '>=') && version_compare($versi, maxMySqlVersion, '<')) || (version_compare($versi, minMariaDBVersion, '>=')),
        ];
    }
}
