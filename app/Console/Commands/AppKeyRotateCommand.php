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

namespace App\Console\Commands;

use App\Actions\Desa\RotateAppKeyAction;
use Illuminate\Console\Command;
use Throwable;

class AppKeyRotateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:key-rotate
        {--dry-run : Simulasi rotasi key tanpa mengubah file atau database}
        {--force : Jalankan rotasi tanpa konfirmasi interaktif}
        {--skip-uploads : Lewati re-encrypt nama file upload}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Rotasi APP_KEY secara aman untuk instalasi OpenSID (multitenant)';

    /**
     * Execute the console command.
     */
    public function handle(RotateAppKeyAction $action): int
    {
        $dryRun      = (bool) $this->option('dry-run');
        $force       = (bool) $this->option('force');
        $skipUploads = (bool) $this->option('skip-uploads');

        $this->info('=== Rotasi APP_KEY OpenSID ===');

        if ($dryRun) {
            $this->warn('[SIMULASI DRY-RUN] Tidak ada perubahan yang akan ditulis ke sistem.');
        }

        if (! $force && ! $dryRun) {
            $this->warn('PERHATIAN:');
            $this->warn('- Semua sesi pengguna aktif akan di-logout.');
            $this->warn('- Link publik lama yang menggunakan slug terenkripsi akan menjadi invalid (404).');

            if (! $this->confirm('Apakah Anda yakin ingin melanjutkan rotasi APP_KEY?')) {
                $this->info('Proses rotasi dibatalkan oleh pengguna.');

                return Command::SUCCESS;
            }
        }

        try {
            $result = $action->execute($dryRun, $skipUploads);

            if ($result['dry_run']) {
                $this->newLine();
                $this->info('--- HASIL SIMULASI (DRY-RUN) ---');
                $this->line("Key Lama : {$result['old_key']}");
                $this->line("Key Baru : {$result['new_key']}");
                $this->line("Path Backup Rencana : {$result['backup_dir']}");
                $this->line("Jumlah Rekord Upload Terdeteksi : {$result['upload_stats']['updated_records_count']}");
                $this->line("Jumlah File Upload Terdeteksi : {$result['upload_stats']['renamed_files_count']}");
                $this->line("Jumlah Rekord Upload Dilewati (tidak valid) : {$result['upload_stats']['skipped_records_count']}");
                $this->info('Simulasi selesai dengan sukses.');

                return Command::SUCCESS;
            }

            $this->newLine();
            $this->info('--- HASIL ROTASI ---');
            $this->line("Key Lama : {$result['old_key']}");
            $this->line("Key Baru : {$result['new_key']}");
            $this->line("Path Backup : {$result['backup_dir']}");
            $this->line("Jumlah Rekord Upload Di-update : {$result['upload_stats']['updated_records_count']}");
            $this->line("Jumlah File Upload Di-rename : {$result['upload_stats']['renamed_files_count']}");
            $this->line("Jumlah Rekord Upload Dilewati (tidak valid) : {$result['upload_stats']['skipped_records_count']}");
            $this->line("Jumlah Rekord Kolom DB Terenkripsi Di-update : {$result['db_column_stats']['updated_records_count']}");
            $this->info('Rotasi APP_KEY berhasil dilaksanakan!');

            return Command::SUCCESS;

        } catch (Throwable $e) {
            $this->error('GAGAL: ' . $e->getMessage());

            return Command::FAILURE;
        }
    }
}
