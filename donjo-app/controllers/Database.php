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

defined('BASEPATH') || exit('No direct script access allowed');

use App\Libraries\FlxZipArchive;
use App\Models\LogBackup;
use App\Models\LogRestoreDesa;
use App\Models\Migrasi;
use App\Models\SettingAplikasi;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Symfony\Component\Process\Process;

class Database extends Admin_Controller
{
    public $modul_ini     = 'pengaturan';
    public $sub_modul_ini = 'database';

    public function __construct()
    {
        parent::__construct();
        isCan('b');
        $this->load->model(['ekspor_model', 'database_model']);
        $this->load->helper('number');
        $this->load->library('OTP/OTP_manager', null, 'otp_library');
    }

    public function index(): void
    {
        $data = [
            'content'      => 'admin.database.backup',
            'form_action'  => setting('multi_desa') ? ci_route('multiDB.restore') : ci_route('database.restore'),
            'size_folder'  => byte_format(dirSize(DESAPATH)),
            'size_sql'     => byte_format(getSizeDB()->size),
            'act_tab'      => 1,
            'inkremental'  => LogBackup::where('status', '<', 2)->latest()->first(),
            'restore'      => LogRestoreDesa::where('status', '=', 0)->exists(),
            'memory_limit' => Arr::get($this->setting_model->cekKebutuhanSistem(), 'memory_limit.result'),
        ];

        view('admin.database.index', $data);
    }

    public function migrasi_cri(): void
    {
        $data['form_action'] = site_url('database/migrasi_db_cri');

        $data['act_tab'] = 2;
        $data['content'] = 'admin.database.migrasi_cri';
        view('admin.database.index', $data);
    }

    public function migrasi_db_cri(): void
    {
        isCan('u');
        session_error_clear();
        set_time_limit(0);              // making maximum execution time unlimited
        ob_implicit_flush(1);           // Send content immediately to the browser on every statement which produces output
        ob_end_flush();
        $mode = $this->input->get('mode');
        if ($mode == 'all') {
            Migrasi::whereNotNull('id')->delete();
        } else {
            $migrasiTerakhir = Migrasi::orderBy('id', 'desc')->first();
            if ($migrasiTerakhir) {
                $migrasiTerakhir->delete();
            }
        }

        echo json_encode(['message' => 'Ulangi migrasi database versi ' . VERSI_DATABASE, 'status' => 0]);
        $this->database_model->setShowProgress(1)->cek_migrasi();
        echo json_encode(['message' => 'Proses migrasi database telah berhasil', 'status' => 1]);
    }

    public function exec_backup()
    {
        if (! Arr::get($this->setting_model->cekKebutuhanSistem(), 'memory_limit.result')) {
            return show_404();
        }

        $this->ekspor_model->backup();
    }

    public function desa_backup(): void
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
        $last   = LogBackup::where('status', '<', 2)->latest()->first();
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

    public function inkremental_download(): void
    {
        $file = LogBackup::latest()->first();
        $file->update(['downloaded_at' => Carbon::now(), 'status' => 2]);
        $za           = new FlxZipArchive();
        $za->tmp_file = $file->path;
        $za->download('backup_inkremental' . $file->created_at->format('Y_m-d') . '.zip');
    }

    public function restore(): void
    {
        isCan('h');

        if (config_item('demo_mode')) {
            redirect($this->controller);
        }

        if (setting('multi_desa')) {
            redirect_with('error', 'Restore database tidak diizinkan');
        }

        $token   = $this->setting->layanan_opendesa_token;
        $pesan   = 'Proses restore database berhasil';
        $success = false;

        try {
            $this->session->sedang_restore = 1;
            $filename                      = $this->file_restore();
            $success                       = $this->ekspor_model->proses_restore($filename);
        } catch (Exception $e) {
            $this->session->sedang_restore = 0;
            $pesan                         = $e->getMessage();
        } finally {
            if ($this->input->post('hapus_token') == 'N') {
                SettingAplikasi::where('key', 'layanan_opendesa_token')->update(['value' => $token]);
            }
            $this->session->sedang_restore = 0;
            if ($success) {
                redirect_with('success', $pesan);
            } else {
                redirect_with('error', $pesan);
            }
        }
    }

    public function acak()
    {
        isCan('u');
        if ($this->setting->penggunaan_server != 6 && ! super_admin()) {
            return;
        }

        $this->load->model('acak_model');

        $data = [
            'penduduk' => $this->acak_model->acak_penduduk(),
            'keluarga' => $this->acak_model->acak_keluarga(),
        ];

        return view('admin.database.acak.index', $data);
    }

    // Digunakan untuk server yg hanya digunakan untuk web publik
    public function mutakhirkan_data_server(): void
    {
        isCan('u');
        $this->session->error_msg = null;
        if ($this->setting->penggunaan_server != 6) {
            return;
        }
        $this->load->view('database/ajax_sinkronkan');
    }

    public function proses_sinkronkan(): void
    {
        isCan('u');
        $this->load->model('sinkronisasi_model');

        $this->load->library('MY_Upload', null, 'upload');
        $this->upload->initialize([
            'upload_path'   => sys_get_temp_dir(),
            'allowed_types' => 'zip',
            'overwrite'     => true,
            'file_name'     => namafile('Sinkronisasi'),
        ]);

        if (! $this->upload->do_upload('sinkronkan')) {
            status_sukses(false, false, $this->upload->display_errors());
            redirect($_SERVER['HTTP_REFERER']);
        }

        $upload = $this->upload->data();

        $hasil = $this->sinkronisasi_model->sinkronkan($upload['full_path']);
        status_sukses($hasil);
        redirect($_SERVER['HTTP_REFERER']);
    }

    public function batal_backup(): void
    {
        $this->load->library('job_prosess');
        // ambil semua data pid yang masih dalam prosess
        $last_backup = LogBackup::where('status', '=', 0)->get();

        foreach ($last_backup as $value) {
            $this->job_prosess->kill($value->pid_process);
            $value->status = 3;
            $value->save();
        }
        redirect($this->controller);
    }

    public function kirim_otp()
    {
        $method                  = $this->input->post('method');
        $this->session->kode_otp = null;

        if (! in_array($method, ['telegram', 'email'])) {
            return json([
                'status'  => false,
                'message' => 'Metode tidak ditemukan',
            ], 400);
        }

        $user = User::when($method == 'telegram', static fn ($query) => $query->whereNotNull('telegram_verified_at'))
            ->when($method == 'email', static fn ($query) => $query->whereNotNull('email_verified_at'))
            ->first();

        if ($user == null) {
            return json([
                'status'  => false,
                'message' => "{$method} belum terverifikasi",
            ], 400);
        }

        try {
            $token           = hash('sha256', $raw_token = random_int(100000, 999999));
            $user->token     = $token;
            $user->token_exp = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s') . ' +5 minutes'));
            $user->save();
            if ($method == 'telegram') {
                $this->otp_library->driver('telegram')->kirim_otp($user->id_telegram, $raw_token);
            } else {
                $this->otp_library->driver('email')->kirim_otp($user->email, $raw_token);
            }

            return json([
                'status'  => true,
                'message' => "Kode verifikasi sudah terkirim ke {$method}",
            ]);
        } catch (Exception $e) {
            return json([
                'status'   => false,
                'messages' => $e->getMessage(),
            ], 400);
        }
    }

    public function verifikasi_otp()
    {
        if ($this->input->post()) {
            $otp = $this->input->post('otp');
            if ($this->cek_otp($otp)) {
                $this->session->kode_otp = $otp;

                return json([
                    'status'  => true,
                    'message' => 'Verifikasi berhasil',
                ]);
            }

            return json([
                'status'  => false,
                'message' => 'Kode OTP Salah',
            ]);
        }

        show_404();
    }

    public function upload_restore()
    {
        if (! $this->cek_otp(bilangan($this->session->kode_otp))) {
            return json([
                'status'  => false,
                'message' => 'Kode OTP Salah',
            ]);
        }

        $this->session->kode_otp = null;
        $config                  = [
            'upload_path'   => sys_get_temp_dir(),
            'allowed_types' => 'zip',
            'file_ext'      => 'zip',
            'max_size'      => max_upload() * 1024,
            'check_script'  => false,
        ];
        $this->load->library('MY_Upload', null, 'upload');
        $this->upload->initialize($config);

        try {
            if (! $this->upload->do_upload('file')) {
                return json([
                    'status'  => false,
                    'message' => $this->upload->display_errors(null, null),
                ]);
            }
            $uploadData = $this->upload->data();

            $id = LogRestoreDesa::create([
                'ukuran'     => $uploadData['file_name'],
                'path'       => $uploadData['full_path'],
                'restore_at' => date('Y-m-d H:i:s'),
                'status'     => 0,
            ])->id;

            $process = new Process(['php', '-f', FCPATH . 'index.php', 'job', 'restore_desa', $id]);
            $process->disableOutput()->setOptions(['create_new_console' => true]);
            $process->start();

            return json([
                'status'  => true,
                'message' => 'upload file berhasil. restore dijalankan melalui job background',
            ]);
        } catch (Exception $e) {
            return json([
                'status'   => false,
                'messages' => $e->getMessage(),
            ]);
        }
    }

    public function batal_restore(): void
    {
        $this->load->library('job_prosess');
        // ambil semua data pid yang masih dalam prosess
        $last_restore = LogRestoreDesa::where('status', '=', 0)->get();

        foreach ($last_restore as $value) {
            $this->job_prosess->kill($value->pid_process);
            $value->status = 3;
            $value->save();
        }
        redirect($this->controller);
    }

    private function cek_otp($otp)
    {
        return User::where('id', '=', auth()->id)
            ->where('token_exp', '>', date('Y-m-d H:i:s'))
            ->where('token', '=', hash('sha256', bilangan($otp)))
            ->exists();
    }

    public function file_restore()
    {
        $this->load->library('MY_Upload', null, 'upload');
        $uploadConfig = [
            'upload_path'   => sys_get_temp_dir(),
            'allowed_types' => 'sql', // File sql terdeteksi sebagai text/plain
            'file_ext'      => 'sql',
            'max_size'      => max_upload() * 1024,
            'cek_script'    => false,
        ];
        $this->upload->initialize($uploadConfig);
        // Upload sukses
        if (! $this->upload->do_upload('userfile')) {
            $pesan = $this->upload->display_errors(null, null);

            throw new Exception($pesan);
        }
        $uploadData = $this->upload->data();

        return $uploadConfig['upload_path'] . '/' . $uploadData['file_name'];
    }
}
