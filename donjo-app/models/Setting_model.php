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

use App\Models\SettingAplikasi;
use App\Repositories\SettingAplikasiRepository;

defined('BASEPATH') || exit('No direct script access allowed');

class Setting_model extends MY_Model
{
    public function init(): void
    {
        $CI = &get_instance();

        if ($this->setting) {
            return;
        }

        SettingAplikasiRepository::applySettingCI($CI);
        
        $this->load->model(['track_model', 'database_model']);
        $this->track_model->track_desa();
        $this->database_model->cek_migrasi();
    }

    public function update_setting($data)
    {
        $hasil = true;
        $this->load->model('theme_model');

        // TODO : Jika sudah dipisahkan, buat agar upload gambar dinamis/bisa menyesuaikan dengan kebutuhan tema (u/ Modul Pengaturan Tema)
        if ($data['latar_website']) {
            $data['latar_website'] = $this->upload_img('latar_website', $this->theme_model->lokasi_latar_website(str_replace('desa/', '', setting('web_theme'))));
        } else {
            $data['latar_website'] = setting('latar_website');
        }

        if ($data['latar_login']) {
            $data['latar_login'] = $this->upload_img('latar_login', LATAR_LOGIN);
        } else {
            $data['latar_login'] = setting('latar_login');
        }

        if ($data['latar_login_mandiri']) {
            $data['latar_login_mandiri'] = $this->upload_img('latar_login_mandiri', LATAR_LOGIN);
        } else {
            $data['latar_login_mandiri'] = setting('latar_login_mandiri');
        }

        if ($data['latar_kehadiran']) {
            $data['latar_kehadiran'] = $this->upload_img('latar_kehadiran', LATAR_LOGIN);
        } else {
            $data['latar_kehadiran'] = setting('latar_kehadiran');
        }

        foreach ($data as $key => $value) {
            // Update setting yang diubah
            if ($this->setting->{$key} != $value) {
                if (in_array($key, ['current_version', 'warna_tema', 'lock_theme'])) {
                    continue;
                }

                $value = is_array($value) ? $value : strip_tags($value);
                // update password jika terisi saja
                if ($key == 'email_smtp_pass' && $value === '') {
                    continue;
                }

                if ($key == 'tampilkan_pendaftaran' && $value == 1) {
                    if (setting('email_notifikasi') == 0 || setting('telegram_notifikasi') == 0) {
                        $value = 0;
                        $hasil = false;
                        set_session('flash_error_msg', 'Untuk menampilkan pendaftaran, notifikasi harus mengaktifkan pengaturan notifikasi email dan telegram');
                    }
                }

                if ($key == 'ip_adress_kehadiran' || $key == 'mac_adress_kehadiran') {
                    $value = trim($value);
                }

                if ($key == 'id_pengunjung_kehadiran') {
                    $value = alfanumerik(trim($value));
                }

                // update password jika terisi saja
                if ($key == 'api_opendk_password' && $value === '') {
                    continue;
                }

                if ($key == 'api_opendk_key' && (empty(setting('api_opendk_server')) || empty(setting('api_opendk_user')) || empty(setting('api_opendk_password')))) {
                    $value = null;
                }

                if (is_array($post = $this->input->post($key))) {
                    if (in_array('-', $post)) {
                        unset($post[0]);
                    }
                    $value = json_encode($post, JSON_THROW_ON_ERROR);
                }

                $hasil                 = $hasil && $this->update($key, $value);
                $this->setting->{$key} = $value;
                if ($key == 'enable_track') {
                    $hasil = $hasil && $this->notifikasi_tracker();
                }
            }
        }
        // model seperti diatas tidak bisa otomatis invalidated cache, jadi harus dihapus manual
        (new SettingAplikasi())->flushQueryCache();

        return $hasil;
    }

    public function upload_img($key = '', $lokasi = '')
    {
        $this->load->library('upload');

        $config['upload_path']   = $lokasi;
        $config['allowed_types'] = 'jpg|jpeg|png';
        $config['overwrite']     = true;
        $config['max_size']      = max_upload() * 1024;
        $config['file_name']     = time() . $key . '.jpg';

        $latar_old = setting($key);

        $this->upload->initialize($config);

        if ($this->upload->do_upload($key)) {
            $uploadData = $this->upload->data();

            if (file_exists($lokasi . $latar_old) && $latar_old != '') {
                unlink($lokasi . $latar_old); // hapus file yang sebelumya
            }

            return $uploadData['file_name'];
        }

        set_session('flash_error_msg', $this->upload->display_errors(null, null));

        return false;
    }

    private function notifikasi_tracker(): bool
    {
        if (setting('enable_track') == 0) {
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
        $this->config_id()->where('kode', 'tracking_off')->update('notifikasi', $notif);

        return true;
    }

    public function update($key = 'enable_track', $value = 1)
    {
        if ($key == 'tte' && $value == 1) {
            SettingAplikasi::where('key', 'verifikasi_kades')->update(['value' => 1]); // jika tte aktif, aktifkan juga verifikasi kades
        }

        $outp = SettingAplikasi::where('key', $key)->update(['value' => $value]);

        // Hapus Cache
        // $this->cache->hapus_cache_untuk_semua('status_langganan');
        // cache()->flush();
        $this->cache->hapus_cache_untuk_semua('_cache_modul');

        status_sukses($outp);

        return true;
    }

    public function aktifkan_tracking(): void
    {
        // ini bisa otomatis invalidate cache
        (SettingAplikasi::where('key', 'enable_track')->first())->update(['value' => 1]);
        status_sukses(1);
    }

    public function update_slider(): void
    {
        $_SESSION['success'] = 1;
        setting('sumber_gambar_slider', $this->input->post('pilihan_sumber'));
        setting('jumlah_gambar_slider', $this->input->post('jumlah_gambar_slider'));
        SettingAplikasi::where('key', 'sumber_gambar_slider')->update(['value' => $this->input->post('pilihan_sumber')]);
        SettingAplikasi::where('key', 'jumlah_gambar_slider')->update(['value' => $this->input->post('jumlah_gambar_slider')]);
        (new SettingAplikasi())->flushQueryCache();
        $outp = 1;
        if (! $outp) {
            $_SESSION['success'] = -1;
        }
    }

    /*
        Input post:
        - jenis_server dan server_mana menentukan setting penggunaan_server
        - offline_mode dan offline_mode_saja menentukan setting offline_mode
    */
    public function update_penggunaan_server(): void
    {
        $_SESSION['success'] = 1;
        $mode                = $this->input->post('offline_mode_saja');
        setting('offline_mode', ($mode === '0' || $mode) ? $mode : $this->input->post('offline_mode'));
        (SettingAplikasi::where('key', 'offline_mode')->first())->update(['value' => setting('offline_mode')]);
        $penggunaan_server = $this->input->post('server_mana') ?: $this->input->post('jenis_server');
        setting('penggunaan_server', $penggunaan_server);
        (SettingAplikasi::where('key', 'penggunaan_server')->first())->update(['value' => $penggunaan_server]);
    }
}
