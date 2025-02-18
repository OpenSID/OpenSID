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

namespace App\Traits;

use App\Models\Theme;
use Exception;

trait Upload
{
    protected function upload($file, $config = [], $redirectUrl = null)
    {
        $isAjax = request()->ajax();
        if (! is_dir($config['upload_path'])) {
            folder($config['upload_path'], '0755', 'htaccess1');
        }

        $this->load->library('upload');

        if (isset($config['resize']) && is_array($config['resize'])) {
            $resizeConfig = $config['resize'];
            unset($config['resize']);
            $this->upload->initialize($config);
        } else {
            $this->upload->initialize($config);
        }

        try {
            $upload = $this->upload->do_upload($file);

            if (! $upload) {
                if ($isAjax) {
                    return ['error' => $this->upload->display_errors()];
                }
                redirect_with('error', $this->upload->display_errors(), $redirectUrl ?? $this->controller);
            }

            $uploadData = $this->upload->data();

            if (isset($resizeConfig)) {
                resizeImage($uploadData['full_path'], $uploadData['file_type'], $resizeConfig);
            }

            return $uploadData['file_name'];
        } catch (Exception $e) {
            log_message('error', $e->getMessage());

            if ($isAjax) {
                return ['error' => $e->getMessage()];
            }

            redirect_with('error', $this->upload->display_errors(), $redirectUrl ?? $this->controller);
        }

        return null;
    }

    public function uploadImg($key = '', $lokasi = '')
    {
        $this->load->library('upload', null, 'upload');

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

    public function uploadImgSetting(&$data)
    {
        // TODO : Jika sudah dipisahkan, buat agar upload gambar dinamis/bisa menyesuaikan dengan kebutuhan tema (u/ Modul Pengaturan Tema)
        if ($data['latar_website']) {
            $data['latar_website'] = $this->uploadImg('latar_website', (new Theme())->lokasiLatarWebsite());
        } else {
            $data['latar_website'] = setting('latar_website');
        }

        if ($data['latar_login']) {
            $data['latar_login'] = $this->uploadImg('latar_login', LATAR_LOGIN);
        } else {
            $data['latar_login'] = setting('latar_login');
        }

        if ($data['latar_login_mandiri']) {
            $data['latar_login_mandiri'] = $this->uploadImg('latar_login_mandiri', LATAR_LOGIN);
        } else {
            $data['latar_login_mandiri'] = setting('latar_login_mandiri');
        }

        if ($data['latar_kehadiran']) {
            $data['latar_kehadiran'] = $this->uploadImg('latar_kehadiran', LATAR_LOGIN);
        } else {
            $data['latar_kehadiran'] = setting('latar_kehadiran');
        }
    }
}
