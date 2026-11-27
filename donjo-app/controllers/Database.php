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
 * Hak Cipta 2016 - 2026 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright Hak Cipta 2016 - 2026 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

defined('BASEPATH') || exit('No direct script access allowed');

use App\Libraries\Database as LibrariesDatabase;
use App\Libraries\Ekspor;
use App\Libraries\FlxZipArchive;
use App\Libraries\JobProses;
use App\Libraries\OTP\OtpManager;
use App\Libraries\Sinkronisasi;
use App\Libraries\Sistem;
use App\Models\LogBackup;
use App\Models\LogRestoreDesa;
use App\Models\Migrasi;
use App\Models\SettingAplikasi;
use App\Models\User;
use App\Services\FolderDesaCleaner\FolderDesaKeepList;
use App\Services\FolderDesaCleaner\FolderDesaScanner;
use App\Traits\Download;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;
use STS\ZipStream\Facades\Zip;

class Database extends Admin_Controller
{
    use Download;

    public $modul_ini     = 'pengaturan';
    public $sub_modul_ini = 'database';
    private $jobProses;
    private OtpManager $otp;

    public function __construct()
    {
        parent::__construct();
        isCan('b');
        $this->load->helper('number');
        $this->jobProses = new JobProses();
        $this->otp       = new OtpManager();
    }

    public function index(): void
    {
        $kebutuhan = Sistem::cekKebutuhanSistem();
        $data = [
            'content'      => 'admin.database.backup',
            'form_action'  => setting('multi_desa') ? ci_route('multiDB.restore') : ci_route('database.restore'),
            'size_folder'  => byte_format(dirSize(DESAPATH)),
            'size_sql'     => byte_format(getSizeDB()->size),
            'act_tab'      => 1,
            'inkremental'  => LogBackup::where('status', '<', 2)->latest()->first(),
            'restore'      => LogRestoreDesa::where('status', '=', 0)->exists(),
            'memory_limit' => Arr::get($kebutuhan, 'memory_limit.result'),
            'upload_max'   => Arr::get($kebutuhan, 'upload_max_filesize.result'),
            'post_max'     => Arr::get($kebutuhan, 'post_max_size.result'),
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
        (new LibrariesDatabase())->setShowProgress(1)->checkMigration();
        echo json_encode(['message' => 'Proses migrasi database telah berhasil', 'status' => 1]);
    }

    public function exec_backup()
    {
        try {
            if (! Arr::get(Sistem::cekKebutuhanSistem(), 'memory_limit.result')) {
                throw new Exception('Memory limit tidak mencukupi untuk melakukan backup. Silakan periksa konfigurasi server.');
            }

            // Parameter force_all untuk backup semua desa (hanya valid untuk super admin, bukan database gabungan)
            $forceAll = $this->input->get('force_all') === '1' && is_super_admin() && ! setting('multi_desa');

            if ($forceAll) {
                $dbName = (new Ekspor())->backup();
            } else {
                // Backup database desa saat ini (database gabungan)
                $configId = identitas('id');
                $dbName   = (new Ekspor())->setConfigId($configId)->backup();
            }

            if (! file_exists($dbName)) {
                throw new Exception('File backup gagal dibuat. Silakan coba lagi.');
            }

            return $this->downloadFile($dbName);
        } catch (Exception $e) {
            logger()->error($e);
            session_error('Backup gagal: ' . $e->getMessage());

            return redirect('database');
        }
    }

    public function desa_backup()
    {
        // Matikan semua buffer sebelum streaming
        while (ob_get_level() > 0) {
            ob_end_clean();
        }

        set_time_limit(0);
        ignore_user_abort(true);

        // Mapping file dengan path yang benar dan validasi symlink
        $files = collect(Storage::disk('desa')->allFiles())
            ->filter(static function ($file) {
                $fullPath = Storage::disk('desa')->path($file);

                // Skip symlink untuk mencegah corrupt/infinite loop
                if (is_link($fullPath)) {
                    return false; // Skip symbolic link
                }

                // Skip file yang tidak bisa dibaca atau tidak ada
                return ! (! file_exists($fullPath) || ! is_readable($fullPath));
            })
            ->mapWithKeys(static fn ($file) => [
                Storage::disk('desa')->path($file) => $file,
            ])
            ->toArray();

        if (empty($files)) {
            return redirect_with('error', 'Backup folder desa gagal: Tidak ada file dalam folder desa untuk di-backup', 'database');
        }

        return Zip::create(
            name: 'backup_folder_desa_' . date('Y_m_d') . '.zip',
            files: $files
        );
    }

    public function desa_inkremental()
    {
        return view('admin.database.inkremental');
    }

    public function desa_inkremental_datatables()
    {
        if ($this->input->is_ajax_request()) {
            return datatables()->of(LogBackup::query())
                ->addIndexColumn()
                ->addColumn('aksi', static fn ($row): string => View::make('admin.layouts.components.buttons.hapus', [
                    'url'           => ci_route('database.inkremental_delete', $row->id),
                    'confirmDelete' => true,
                ])->render())
                ->rawColumns(['aksi'])
                ->make();
        }

        return show_404();
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

        $index = escapeshellarg(PUBLICPATH . 'index.php');
        $lok   = escapeshellarg($lokasi);
        shell_exec('nohup ' . escapeshellarg($this->phpCliBinary()) . " -f {$index} job backup_inkremental {$lok} > /dev/null 2>&1 &");

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

    public function inkremental_delete($id): void
    {
        $file = LogBackup::findOrFail($id);
        if ($file->delete()) {
            redirect_with('success', 'Data berhasil dihapus', 'database/desa_inkremental');
        }

        redirect_with('error', 'Data gagal dihapus', 'database/desa_inkremental');
    }

    public function restore()
    {
        isCan('u', 'database', true, true);

        $token            = setting('layanan_opendesa_token');
        $telegramSettings = SettingAplikasi::whereIn('key', ['telegram_notifikasi', 'telegram_token', 'telegram_user_id'])
            ->pluck('value', 'key')
            ->all();
        $emailSettings = SettingAplikasi::whereIn('key', ['email_notifikasi', 'email_protocol', 'email_smtp_host', 'email_smtp_user', 'email_smtp_pass', 'email_smtp_port', 'email_smtp_encryption'])
            ->pluck('value', 'key')
            ->all();
        $pesan   = 'Proses restore database berhasil';
        $success = false;

        try {
            $this->session->sedang_restore = 1;

            $filename     = $this->file_restore();
            $paksaRestore = $this->input->post('paksa_restore') === 'Y';

            // Saat "Pertahankan kredensial lokal" dicentang, restore dari instalasi
            // berbeda diizinkan — lewati cek kecocokan App Key.
            if (! $paksaRestore && ! $this->validateAppKeyFromSqlFile($filename)) {
                throw new Exception('File backup tidak dapat di-restore. File backup berasal dari instalasi OpenSID yang berbeda (App Key tidak cocok). Centang "Pertahankan kredensial lokal" untuk tetap melanjutkan.');
            }

            DB::statement('SET FOREIGN_KEY_CHECKS=0');
            $success = (new Ekspor())->restore($filename, $paksaRestore);
        } catch (Throwable $e) {
            logger()->error($e);
            $pesan = $e->getMessage();
        } finally {
            // Reconnect karena koneksi PDO lama bisa stale setelah MySQLImport
            // menimpa seluruh database via koneksi mysqli terpisah.
            DB::reconnect();
            DB::statement('SET FOREIGN_KEY_CHECKS=1');

            if ($this->input->post('hapus_token') == 'N') {
                SettingAplikasi::where('key', 'layanan_opendesa_token')->update(['value' => $token]);
            }

            // Pertahankan kredensial lokal: kembalikan setelan notifikasi lokal agar
            // notifikasi login tidak terkirim ke admin instalasi asal backup, dan paksa
            // admin verifikasi ulang karena hash password bisa berubah setelah restore.
            if ($this->input->post('paksa_restore') === 'Y') {
                foreach ($telegramSettings as $key => $value) {
                    SettingAplikasi::where('key', $key)->update(['value' => $value]);
                }

                if ($emailSettings !== []) {
                    foreach ($emailSettings as $key => $value) {
                        SettingAplikasi::where('key', $key)->update(['value' => $value]);
                    }
                }

                // Mass update tidak memicu model events sehingga QueryCacheable tidak
                // otomatis di-invalidate. Flush eksplisit agar request berikutnya membaca
                // setelan notifikasi yang sudah dikembalikan dari DB.
                (new SettingAplikasi())->flushQueryCache();

                $this->session->change_password = true;
            }

            $this->session->sedang_restore = 0;

            if ($success) {
                return redirect_with('success', $pesan);
            }

            return redirect_with('error', $pesan, 'database', true);
        }
    }

    /**
     * Scan folder desa/ dan tampilkan kandidat file untuk dihapus.
     * Hanya dapat diakses oleh super admin.
     */
    public function bersih_folder(): void
    {
        isCan('u');
        if (! super_admin()) {
            redirect('database');
        }

        // "Scan ulang" mengosongkan daftar file yang dipertahankan (mulai dari awal);
        // navigasi biasa ke tab mempertahankan pilihan sebelumnya.
        if ($this->input->get('rescan')) {
            (new FolderDesaKeepList())->clear();
        }

        $this->tampilkanScanBersihFolder();
    }

    /**
     * Simpan pilihan (file yang di-deselect / dipertahankan) tanpa menghapus,
     * agar pilihan tetap berlaku saat kembali ke tab maupun saat proses Acak.
     */
    public function simpan_bersih_folder(): void
    {
        isCan('u');
        if (! super_admin()) {
            redirect('database');
        }

        (new FolderDesaKeepList())->set($this->keepDariRequest());

        $this->tampilkanScanBersihFolder(tersimpan: true);
    }

    /**
     * Hapus file (semua hasil scan KECUALI yang dipertahankan) dan tampilkan hasil.
     * Pilihan file yang dipertahankan juga disimpan. Hanya untuk super admin.
     */
    public function hapus_bersih_folder(): void
    {
        isCan('u');
        if (! super_admin()) {
            redirect('database');
        }

        $keep = $this->keepDariRequest();
        (new FolderDesaKeepList())->set($keep);

        $scanner   = new FolderDesaScanner();
        $partition = FolderDesaScanner::partition($scanner->scan(), $keep);

        $data = [
            'act_tab' => 3,
            'content' => 'admin.database.bersih_folder',
            'step'    => 'result',
            'result'  => $scanner->delete($partition['bulkKeys'], $partition['filePaths']),
        ];

        view('admin.database.index', $data);
    }

    /**
     * Render langkah scan Bersihkan Folder Desa dengan daftar file yang
     * dipertahankan (tersimpan) diterapkan.
     */
    private function tampilkanScanBersihFolder(bool $tersimpan = false): void
    {
        $data = [
            'act_tab'   => 3,
            'content'   => 'admin.database.bersih_folder',
            'step'      => 'scan',
            'groups'    => (new FolderDesaScanner())->scan(),
            'keep'      => (new FolderDesaKeepList())->get(),
            'scannedAt' => Carbon::now(),
            'tersimpan' => $tersimpan,
        ];

        view('admin.database.index', $data);
    }

    /**
     * Path relatif yang dipertahankan (di-deselect) dari request, dikirim sebagai
     * JSON pada field keep_json.
     *
     * @return list<string>
     */
    private function keepDariRequest(): array
    {
        $keep = (array) $this->input->post('keep_paths');

        $json = $this->input->post('keep_json');
        if (is_string($json) && $json !== '') {
            $decoded = json_decode($json, true);
            if (is_array($decoded)) {
                $keep = array_merge($keep, $decoded);
            }
        }

        return array_values(array_filter($keep, 'is_string'));
    }

    // Digunakan untuk server yg hanya digunakan untuk web publik
    public function mutakhirkan_data_server(): void
    {
        isCan('u');
        $this->session->error_msg = null;
        if (setting('penggunaan_server') != 6) {
            return;
        }
        view('admin.database.ajax_sinkronkan');
    }

    public function proses_sinkronkan(): void
    {
        isCan('u');

        $this->load->library('upload');
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

        $hasil = (new Sinkronisasi())->sinkronkan($upload['full_path']);
        status_sukses($hasil);
        redirect($_SERVER['HTTP_REFERER']);
    }

    public function batal_backup(): void
    {
        $last_backup = LogBackup::where('status', '=', 0)->get();

        foreach ($last_backup as $value) {
            $this->jobProses->kill($value->pid_process);
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

        $user = auth('admin')->user();

        $isVerified = $user && match ($method) {
            'telegram' => null !== $user->telegram_verified_at,
            'email'    => null !== $user->email_verified_at,
            default    => false,
        };

        if (! $isVerified) {
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
                $this->otp->driver('telegram')->kirimOtp($user->id_telegram, $raw_token);
            } else {
                $this->otp->driver('email')->kirimOtp($user->email, $raw_token);
            }

            return json([
                'status'  => true,
                'message' => "Kode verifikasi sudah terkirim ke {$method}",
            ]);
        } catch (Exception $e) {
            logger()->error($e);

            return json([
                'status'  => false,
                'message' => 'Tidak dapat mengirim kode verifikasi saat ini. Silakan coba lagi nanti.',
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
            ], 400);
        }

        show_404();
    }

    public function upload_restore()
    {
        // isMultiDB();
        // isSiapPakai();
        isCan('u', 'database', true, true);

        if (! $this->cek_otp(bilangan($this->session->kode_otp))) {
            return json([
                'status'  => false,
                'message' => 'Kode OTP Salah',
            ], 400);
        }

        $this->session->kode_otp = null;
        $config                  = [
            'upload_path'   => sys_get_temp_dir(),
            'allowed_types' => 'zip',
            'file_ext'      => 'zip',
            'max_size'      => max_upload() * 1024,
            'check_script'  => false,
        ];
        $this->load->library('upload');
        $this->upload->initialize($config);

        try {
            if (! $this->upload->do_upload('file')) {
                return json([
                    'status'  => false,
                    'message' => $this->upload->display_errors(null, null),
                ], 400);
            }
            $uploadData = $this->upload->data();

            $id = LogRestoreDesa::create([
                'ukuran'        => $uploadData['file_name'],
                'path'          => $uploadData['full_path'],
                'restore_at'    => date('Y-m-d H:i:s'),
                'status'        => 0,
                'paksa_restore' => $this->input->post('paksa_restore_desa') === 'Y',
            ])->id;

            $index = escapeshellarg(PUBLICPATH . 'index.php');
            shell_exec('nohup ' . escapeshellarg($this->phpCliBinary()) . " -f {$index} job restore_desa " . (int) $id . ' > /dev/null 2>&1 &');

            return json([
                'status'  => true,
                'message' => 'Upload file berhasil, restore dijalankan melalui job background',
            ]);
        } catch (Exception $e) {
            logger()->error($e);

            return json([
                'status'  => false,
                'message' => 'Upload file gagal, silakan coba lagi nanti.',
            ], 400);
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

    /**
     * Status restore folder desa terkini untuk polling dari frontend.
     * Status: 0 = proses, 1 = selesai, -1 = gagal, 3 = dibatalkan.
     */
    public function status_restore()
    {
        $log = LogRestoreDesa::latest()->first();

        return json([
            'status'  => $log ? (int) $log->status : null,
            'message' => match ($log?->status) {
                1       => 'Restore folder desa berhasil.',
                -1      => 'Restore folder desa gagal.',
                3       => 'Restore dibatalkan.',
                default => 'Sedang dalam proses...',
            },
        ]);
    }

    public function file_restore()
    {
        $this->load->library('upload');
        $uploadConfig = [
            'upload_path'   => sys_get_temp_dir(),
            'allowed_types' => 'sql|gz', // File sql terdeteksi sebagai text/plain
            'file_ext'      => 'sql|gz',
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

    private function cek_otp($otp)
    {
        return User::where('id', '=', ci_auth()->id)
            ->where('token_exp', '>', date('Y-m-d H:i:s'))
            ->where('token', '=', hash('sha256', (string) bilangan($otp)))
            ->exists();
    }

    /**
     * Validasi app_key dari file SQL.gz dengan app_key sistem saat ini.
     */
    private function validateAppKeyFromSqlFile(string $filename): bool
    {
        try {
            // Buka file SQL.gz
            $handle = strcasecmp(substr($filename, -3), '.gz') ? fopen($filename, 'rb') : gzopen($filename, 'rb');

            if (! $handle) {
                return false;
            }

            $currentAppKey = get_app_key();
            $foundAppKey   = null;
            $lineCount     = 0;
            $maxLines      = 3000; // Increase limit based on test result
            $searchBuffer  = '';

            // Cari INSERT statement untuk tabel config
            while (! feof($handle) && $lineCount < $maxLines) {
                $line = fgets($handle);
                $lineCount++;
                $searchBuffer .= $line;

                // Cari INSERT INTO config
                if (preg_match('/INSERT INTO\s+`?config`?/i', $searchBuffer)) {

                    // Pattern utama: VALUES dengan single quotes (berdasarkan test yang berhasil)
                    if (preg_match("/VALUES\\s*\\(\\s*\\d+\\s*,\\s*'([^']+)'/i", $searchBuffer, $matches)) {
                        $foundAppKey = $matches[1];
                        break;
                    }

                    // Pattern alternatif: VALUES dengan double quotes
                    if (preg_match('/VALUES\\s*\\(\\s*\\d+\\s*,\\s*"([^"]+)"/i', $searchBuffer, $matches)) {
                        $foundAppKey = $matches[1];
                        break;
                    }

                    // Pattern untuk multi-line VALUES
                    if (preg_match("/\\(\\s*\\d+\\s*,\\s*'([^']+)'/i", $searchBuffer, $matches)) {
                        $foundAppKey = $matches[1];
                        break;
                    }
                }

                // Reset buffer jika terlalu besar untuk mencegah memory issue
                if (strlen($searchBuffer) > 15000) {
                    $searchBuffer = substr($searchBuffer, -5000);
                }
            }

            // Tutup file handle
            if (strcasecmp(substr($filename, -3), '.gz')) {
                fclose($handle);
            } else {
                gzclose($handle);
            }

            // Jika tidak menemukan app_key, anggap valid (untuk kompatibilitas dengan backup lama)
            if ($foundAppKey === null) {
                return true;
            }

            // Bandingkan app_key
            return $foundAppKey === $currentAppKey;
        } catch (Exception $e) {
            logger()->error($e);

            // Jika terjadi error dalam validasi, anggap valid untuk menghindari blocking
            return true;
        }
    }

    /**
     * Temukan biner PHP CLI yang bisa dipakai untuk proses job background.
     *
     * PHP_BINARY pada konteks FPM adalah daemon php-fpm, bukan biner CLI. Pemetaannya
     * berbeda-beda tergantung lingkungan:
     *   Homebrew macOS : …/sbin/php-fpm        → …/bin/php
     *   Docker         : /usr/local/bin/php-fpm → /usr/local/bin/php
     *   Ubuntu/Debian  : /usr/sbin/php-fpm8.4   → /usr/bin/php8.4
     *   Local/Laragon  : …/bin/php-fpm          → …/bin/php
     */
    private function phpCliBinary(): string
    {
        return php_cli_binary();
    }
}
