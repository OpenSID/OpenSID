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

use App\Models\Theme as ThemeModel;

defined('BASEPATH') || exit('No direct script access allowed');

class Theme extends Admin_Controller
{
    public $modul_ini     = 'admin-web';
    public $sub_modul_ini = 'theme';

    public function __construct()
    {
        parent::__construct();
        isCan('b');
        $this->load->helper('theme');
    }

    public function index()
    {
        theme_active();

        return view('admin.theme.index', [
            'listTheme' => ThemeModel::orderBy('status', 'desc')->orderBy('sistem', 'desc')->get(),
        ]);
    }

    public function unggah()
    {
        isCan('u', 'theme', true, true);

        $form_action = site_url('theme/proses-unggah');

        return view('admin.theme.unggah', ['form_action' => $form_action]);
    }

    public function proses_unggah(): void
    {
        isCan('u', 'theme', true);

        $tema = $this->unggah_tema();

        redirect_with($tema['status'], $tema['data']);
    }

    public function pengaturan($id = '')
    {
        isCan('u');

        $tema = ThemeModel::findOrFail($id);

        $form_action = site_url("theme/ubah-pengaturan/{$id}");

        return view('admin.theme.pengaturan', ['form_action' => $form_action, 'tema' => $tema]);
    }

    public function ubah_pengaturan($id = ''): void
    {
        isCan('u');

        $tema = ThemeModel::findOrFail($id);

        $tema->update(['opsi' => $this->input->post('opsi')]);

        redirect_with('success', 'Berhasil Ubah Data', "theme/pengaturan/{$id}");
    }

    public function salin_config($id = ''): void
    {
        isCan('u');

        $tema = ThemeModel::findOrFail($id);

        if ($tema->sistem) {
            redirect_with('error', 'Tidak dapat menambahkan config pada tema sistem');
        }

        $sumber = FCPATH . 'storage/app/template/ekspor/config_tema.json';
        $tujuan = FCPATH . $tema->path . '/config.json';

        if (copy($sumber, $tujuan)) {
            redirect_with('success', 'Berhasil Salin Config', "theme/pengaturan/{$id}");
        }

        redirect_with('error', 'Gagal Salin Config', "theme/pengaturan/{$id}");
    }

    public function aktifkan($id = null): void
    {
        isCan('u');

        $status = ThemeModel::findOrFail($id);
        $status->update(['status' => 1]);

        ThemeModel::where('id', '!=', $id)->update(['status' => 0]);

        redirect_with('success', 'Berhasil Ubah Data');
    }

    public function delete($id = ''): void
    {
        isCan('h');

        $delete = ThemeModel::findOrFail($id);

        if ($delete->status) {
            redirect_with('error', 'Tema yang aktif tidak dapat dihapus');
        }

        if ($delete->sistem) {
            redirect_with('error', 'Tema sistem tidak dapat dihapus');
        }

        if ($delete->delete()) {
            redirect_with('success', 'Berhasil Hapus Data');
        }

        redirect_with('error', 'Gagal Hapus Data');
    }

    protected function unggah_tema()
    {
        $this->load->library('Upload');

        $nama_tema               = mt_rand(1000, 9999) . '-tema';
        $config['upload_path']   = sys_get_temp_dir();
        $config['allowed_types'] = 'zip';
        $config['overwrite']     = true;
        $config['max_size']      = max_upload() * 5 * 1024;
        $config['file_name']     = $nama_tema . '.zip';

        $this->upload->initialize($config);
        if ($this->upload->do_upload('userfile')) {
            $upload = $this->upload->data();
            $zip    = new ZipArchive();

            if ($zip->open($upload['full_path']) !== true) {
                unlink($upload['full_path']);

                return [
                    'status' => false,
                    'data'   => 'Tema tidak valid',
                ];
            }

            $lokasi_ekstrak = FCPATH . 'desa/themes/';
            $subfolder      = $zip->getNameIndex(0);
            $zip->extractTo($lokasi_ekstrak);
            $zip->close();

            $lokasi_tema = $lokasi_ekstrak . substr($subfolder, 0, -1);

            if (! file_exists($lokasi_tema . '/template.php')) {
                delete_files($lokasi_tema, true);

                return [
                    'status' => false,
                    'data'   => 'Tema tidak valid',
                ];
            }

            theme_scan();

            return [
                'status' => true,
                'data'   => 'Berhasil Unggah Tema',
            ];
        }

        return [
            'status' => false,
            'data'   => $this->upload->display_errors(),
        ];
    }

    public function pindai(): void
    {
        isCan('u');

        theme_scan();

        redirect_with('success', 'Berhasil Memindai Tema');
    }
}
