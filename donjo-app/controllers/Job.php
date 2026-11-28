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

use App\Libraries\Database;
use App\Libraries\Ekspor;
use App\Libraries\FlxZipArchive;
use App\Libraries\RestoreFolderDesa;
use App\Models\LogAcak;
use App\Models\LogBackup;
use App\Models\LogRestoreDesa;
use App\Models\User;
use App\Services\Acak\AcakOptions;
use App\Services\Acak\AcakService;
use App\Services\FolderDesaCleaner\FolderDesaKeepList;
use Illuminate\Support\Facades\DB;

defined('BASEPATH') || exit('No direct script access allowed');

class Job extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->helper(['number', 'file']);
    }

    public function restore($database = null): void
    {
        if (ENVIRONMENT !== 'production' || (! config_item('demo_mode') && ENVIRONMENT === 'production')) {
            show_404();
        }

        kosongkanFolder(config_item('log_path'));
        log_message('notice', '>_ Mulai');

        // Kecuali folder
        $exclude = [
            'desa/config',
            'desa/themes',
        ];

        // Kosongkan folder desa
        foreach (glob('desa/*', GLOB_ONLYDIR) as $folder) {
            if (! in_array($folder, $exclude)) {
                delete_files(FCPATH . $folder, true);
            }
        }

        // Buat folder desa
        folder_desa();

        try {
            // Proses Restore Database
            $connection = DB::connection();
            $connection->statement('SET FOREIGN_KEY_CHECKS=0');
            $success = (new Ekspor())->restore($this->cekDB($database ?? 'contoh_data_awal'));
            $connection->statement('SET FOREIGN_KEY_CHECKS=1');
            if ($success) {
                (new Database())->migrateDatabase();
            } else {
                log_message('error', 'Proses Restore Database Gagal');
            }
        } catch (Exception $e) {
            $pesan = $e->getMessage();
            log_message('error', $pesan);
        }

        cache()->flush();
        kosongkanFolder('storage/framework/views/');

        log_message('notice', '>_ Selesai');
    }

    public function backup_inkremental($lokasi): void
    {
        /*
        variable status
        0 = sedang dalam prosess
        1 = selesai diproses
        2 = selesai di download
        3 = dibatalkan
        */

        $lokasi      = ($lokasi == 'null') ? sys_get_temp_dir() : 'backup_inkremental';
        $last_backup = LogBackup::latest()->first()->created_at;
        $last_backup = ($last_backup != null) ? $last_backup->format('Y-m-d') : '1990-01-01';
        $backup      = LogBackup::create(['permanen' => ($lokasi) ? 1 : 0, 'pid_process' => getmypid()]); // tandai backup sedang berlangsung

        try {
            $za = new FlxZipArchive();

            $path        = $za->read_dir(DESAPATH, $last_backup, $lokasi);
            $file_backup = get_file_info($path);
            $backup->update(['status' => 1, 'ukuran' => byte_format($file_backup['size']), 'path' => $path]); // update backup sudah selesai
        } catch (Exception $e) {
            logger()->error($e);

            $backup->update(['status' => -1]); // update backup gagal
            printf($e);
        }
    }

    public function restore_desa($id): void
    {
        /*
        variable status
        0 = sedang dalam prosess
        1 = selesai diproses
        2 = selesai di download
        3 = dibatalkan
        -1 = gagal restore
        */

        $restore = LogRestoreDesa::where('id', '=', $id)->first();
        $restore->update(['pid_process' => getmypid()]);

        try {
            // Validasi setiap nama entry untuk mencegah path traversal RCE sebelum
            // ekstraksi dilakukan (perbaikan keamanan dari rilis-dev).
            $zip = new ZipArchive();
            if ($zip->open($restore->path) === true) {
                if (($entry = validate_zip_entries($zip)) !== true) {
                    $zip->close();
                    $restore->update(['status' => -1]);

                    throw new Exception('File backup mengandung path ilegal: ' . $entry);
                }
                $zip->close();
            }

            // Saat opsi "Pertahankan kredensial lokal" aktif, ekstraksi tidak menimpa
            // config/database.php, app_key, dan .env lokal — untuk restore folder desa
            // dari instalasi berbeda.
            (new RestoreFolderDesa())->restore($restore->path, FCPATH . DESAPATH, (bool) $restore->paksa_restore);
            $restore->update(['status' => 1]);
        } catch (Exception $e) {
            $restore->update(['status' => -1]); // restore gagal
            log_message('error', 'Restore folder desa gagal: ' . $e->getMessage());
        }
    }

    /**
     * Jalankan Acak Data sebagai job latar belakang.
     *
     * Proses acak bisa memakan waktu menit (ribuan baris + render surat + foto),
     * jauh melewati batas fastcgi_read_timeout gateway. Alih-alih dialirkan lewat
     * SSE (yang mati saat gateway timeout), proses dijalankan detached dan progres
     * ditulis ke baris LogAcak untuk dipoll frontend.
     *
     * Dijalankan lewat CI3 (index.php job acak_data <id>) sehingga get_instance()
     * tersedia dan mode surat 'sintetis' ikut aktif — setara jalur web.
     *
     * TODO: pindahkan runner ini ke solusi Laravel-native — command Artisan yang
     * diluncurkan detached (nohup php artisan …) agar tetap tanpa worker daemon.
     * Saat dipindah, CI3 harus di-bootstrap di dalam command (lihat pola
     * DesaBaruCommand: getLaravel()->make(CI_Controller::class)) supaya
     * ci3Tersedia() tetap true dan render surat 'sintetis' tidak turun ke
     * placeholder. Setelah itu, route CLI job/acak_data & shim CI3 bisa dihapus.
     *
     * @param int|string $id id baris LogAcak
     */
    public function acak_data($id): void
    {
        /*
        variable status
        0 = sedang dalam prosess
        1 = selesai diproses
        -1 = gagal
        3 = dibatalkan
        */

        $log = LogAcak::where('id', '=', $id)->first();
        if (! $log) {
            return;
        }

        // Job detached ini tak punya sesi login, sehingga auth('admin') dan
        // ci_auth() kosong. Pulihkan user pelaku — yang memulai acak dari web,
        // tersimpan di created_by — agar atribusi created_by/updated_by (lewat
        // AuthorObserver) dan Periksa (lewat ci_auth) menunjuk user yang benar.
        // Fallback ke admin pertama bila created_by tak tersedia.
        $pelaku = ($log->created_by ? User::find($log->created_by) : null) ?? User::first();
        if ($pelaku !== null) {
            auth('admin')->setUser($pelaku);   // dipakai AuthorObserver (created_by/updated_by)
            $this->session->isAdmin = $pelaku; // dipakai ci_auth() (mis. Periksa::perbaiki)
        }

        $log->update(['pid_process' => getmypid()]);

        try {
            $acak = new AcakService();
            $acak->setProgressCallback(static function (string $step) use ($log): void {
                $log->update(['step' => $step]);
            });

            $result = $acak->run(new AcakOptions(
                (string) $log->seed,
                (new FolderDesaKeepList())->get(),
                (string) $log->foto,
                (string) $log->foto_konten,
                (string) $log->surat,
            ));

            $log->update([
                'status' => LogAcak::STATUS_SELESAI,
                'step'   => 'Selesai',
                'hasil'  => json_encode($result->toArray()),
            ]);
        } catch (Throwable $e) {
            $log->update([
                'status' => LogAcak::STATUS_GAGAL,
                'error'  => $e->getMessage(),
            ]);
            log_message('error', 'Acak data gagal: ' . $e->getMessage());
        }
    }

    private function cekDB($filename): string|false
    {
        $filename = DESAPATH . "/config/{$filename}.sql";

        if (file_exists($filename)) {
            return $filename;
        }

        log_message('error', 'File ' . $filename . ' tidak ditemukan');

        return false;
    }
}
