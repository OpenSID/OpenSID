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

use App\Libraries\FlxZipArchive;
use App\Models\LogBackup;
use Carbon\Carbon;
use Symfony\Component\Process\Process;

class Database extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model(['ekspor_model', 'database_model']);
        $this->load->helper('number');
        $this->modul_ini     = 11;
        $this->sub_modul_ini = 45;
    }

    public function index()
    {
        $data = [
            'act_tab'     => 1,
            'content'     => 'database/backup',
            'form_action' => site_url('database/restore'),
            'size_folder' => byte_format(dirSize(DESAPATH)),
            'size_sql'    => byte_format(getSizeDB()->size),
            'act_tab'     => 1,
            'inkremental' => $this->db->table_exists('log_backup') ? LogBackup::latest()->first() : null,
        ];

        $this->load->view('database/database.tpl.php', $data);
    }

    public function migrasi_cri()
    {
        $data['form_action'] = site_url('database/migrasi_db_cri');

        $data['act_tab'] = 2;
        $data['content'] = 'database/migrasi_cri';
        $this->load->view('database/database.tpl.php', $data);
    }

    public function migrasi_db_cri()
    {
        $this->redirect_hak_akses('u');
        $this->session->unset_userdata(['success, error_msg']);
        $this->database_model->migrasi_db_cri();
        redirect('database/migrasi_cri');
    }

    public function exec_backup()
    {
        $this->ekspor_model->backup();
    }

    public function desa_backup()
    {
        $za = new FlxZipArchive();
        $za->read_dir(DESAPATH);
        $za->download('backup_folder_desa_' . date('Y_m_d') . '.zip');
    }

    public function desa_inkremental()
    {
        if ($this->input->is_ajax_request()) {
            return datatables(LogBackup::query())
                ->addIndexColumn()
                ->make();
        }

        return view('admin.database.inkremental');
    }

    public function inkremental_job()
    {
        // cek tanggal
        // job hanya bisa dilakukan 1 hari 1 kali
        $now    = Carbon::now()->format('Y-m-d');
        $last   = LogBackup::latest()->first();
        $lokasi = $this->input->post('lokasi');

        if ($last != null && $now == $last->created_at->format('Y-m-d')) {
            return json([
                'status'  => false,
                'message' => 'Anda sudah melakukan Backup inkremental hari ini',
            ]);
        }

        $process = new Process(['php', '-f', FCPATH . 'index.php', 'job', 'backup_inkremental', $lokasi]);
        $process->disableOutput()->setOptions(['create_new_console' => true]);
        $process->start();

        return json([
            'status'  => true,
            'message' => 'Backup inkremental sedang berlangsung',
        ]);
    }

    public function inkremental_download()
    {
        $file = LogBackup::latest()->first();
        $file->update(['downloaded_at' => Carbon::now(), 'status' => 2]);
        $za           = new FlxZipArchive();
        $za->tmp_file = $file->path;
        $za->download('backup_inkremental' . $file->created_at->format('Y_m-d') . '.zip');
    }

    public function restore()
    {
        $this->redirect_hak_akses('h');

        if (config_item('demo_mode')) {
            redirect($this->controller);
        }

        try {
            $this->session->success        = 1;
            $this->session->error_msg      = '';
            $this->session->sedang_restore = 1;
            $this->ekspor_model->restore();
        } catch (Exception $e) {
            $this->session->success   = -1;
            $this->session->error_msg = $e->getMessage();
        } finally {
            $this->session->sedang_restore = 0;
            redirect('database');
        }
    }

    // Dikhususkan untuk server yg hanya digunakan untuk web publik
    public function acak()
    {
        $this->redirect_hak_akses('u');
        if ($this->setting->penggunaan_server != 6) {
            return;
        }

        $this->load->model('acak_model');
        echo $this->load->view('database/hasil_acak', '', true);
        $hasil = $this->acak_model->acak_penduduk();
        $hasil = $hasil && $this->acak_model->acak_keluarga();
        echo $this->load->view('database/hasil_acak', '', true);
    }

    // Digunakan untuk server yg hanya digunakan untuk web publik
    public function mutakhirkan_data_server()
    {
        $this->redirect_hak_akses('u');
        $this->session->error_msg = null;
        if ($this->setting->penggunaan_server != 6) {
            return;
        }
        $this->load->view('database/ajax_sinkronkan');
    }

    public function proses_sinkronkan()
    {
        $this->redirect_hak_akses('u');
        $this->load->model('sinkronisasi_model');

        $this->load->library('upload');

        $config['upload_path']   = LOKASI_SINKRONISASI_ZIP;
        $config['allowed_types'] = 'zip';
        $config['overwrite']     = true;
        //$config['max_size']				= max_upload() * 1024;
        $config['file_name'] = namafile('sinkronisasi');

        $this->upload->initialize($config);

        if (! $this->upload->do_upload('sinkronkan')) {
            status_sukses(false, false, $this->upload->display_errors());
            redirect($_SERVER['HTTP_REFERER']);
        }

        $hasil = $this->sinkronisasi_model->sinkronkan();
        status_sukses($hasil);
        redirect($_SERVER['HTTP_REFERER']);
    }
}
