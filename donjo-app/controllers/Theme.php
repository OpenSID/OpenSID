<?php

use App\Models\Theme as ThemeModel;

defined('BASEPATH') || exit('No direct script access allowed');

class Theme extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->modul_ini     = 'admin-web';
        $this->sub_modul_ini = 'theme';
        $this->load->helper('theme');
    }

    public function index()
    {
        theme_active();

        return view('admin.theme.index', [
            'listTheme'   => ThemeModel::orderBy('status', 'desc')->orderBy('sistem', 'desc')->get(),
        ]);
    }

    public function unggah()
    {
        $this->redirect_hak_akses('u');

        $form_action = site_url('theme/proses-unggah');

        return view('admin.theme.unggah', compact('form_action'));
    }

    public function proses_unggah()
    {
        $this->redirect_hak_akses('u');

        $tema = $this->unggah_tema();

        redirect_with($tema['status'], $tema['data']);
    }

    public function pengaturan($id = '')
    {
        $this->redirect_hak_akses('u');

        $tema = ThemeModel::findOrFail($id);

        if (count($tema->config) == 0) {
            redirect_with('error', "Tema <b>{$tema->nama}</b> tidak memiliki pengaturan");
        }

        $form_action = site_url("theme/ubah-pengaturan/{$id}");

        return view('admin.theme.pengaturan', compact('form_action', 'tema'));
    }

    public function ubah_pengaturan($id = '')
    {
        $this->redirect_hak_akses('u');

        $tema = ThemeModel::findOrFail($id);

        $tema->update(['opsi' => $this->input->post('opsi')]);

        redirect_with('success', 'Berhasil Ubah Data', "theme/pengaturan/{$id}");
    }

    public function aktifkan($id = null)
    {
        $this->redirect_hak_akses('u');

        $status = ThemeModel::findOrFail($id);
        $status->update(['status' => 1]);

        ThemeModel::where('id', '!=', $id)->update(['status' => 0]);

        redirect_with('success', 'Berhasil Ubah Data');
    }

    public function delete($id = '')
    {
        $this->redirect_hak_akses('h');

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

    public function pindai()
    {
        $this->redirect_hak_akses('u');

        theme_scan();

        redirect_with('success', 'Berhasil Memindai Tema');
    }
}
