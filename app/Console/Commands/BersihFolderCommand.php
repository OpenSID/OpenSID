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

use App\Services\FolderDesaCleaner\FolderDesaScanner;
use App\Services\FolderDesaCleaner\ScanGroup;
use Illuminate\Console\Command;

/**
 * Scan and optionally delete unused files from the desa/ folder.
 *
 * Usage:
 *   php artisan opensid:bersih-folder              # dry-run: list candidates only
 *   php artisan opensid:bersih-folder --hapus      # delete all candidates
 *   php artisan opensid:bersih-folder --rule=stray_content --hapus  # single rule
 */
class BersihFolderCommand extends Command
{
    protected $signature = 'opensid:bersih-folder
        {--hapus : Hapus semua kandidat tanpa konfirmasi interaktif}
        {--rule= : Batasi ke satu rule saja (pakai nilai key() dari CleanupRule)}';
    protected $description = 'Scan folder desa/ untuk file tidak dibutuhkan; hapus dengan --hapus';

    public function handle(): int
    {
        $scanner = new FolderDesaScanner();
        $groups  = $scanner->scan();

        if (empty($groups)) {
            $this->info('Folder desa sudah bersih — tidak ada kandidat ditemukan.');

            return self::SUCCESS;
        }

        $ruleFilter = $this->option('rule');

        if ($ruleFilter !== null) {
            $groups = array_values(array_filter($groups, static fn (ScanGroup $g) => $g->key === $ruleFilter));

            if (empty($groups)) {
                $this->warn("Rule '{$ruleFilter}' tidak menghasilkan kandidat (atau key tidak ditemukan).");

                return self::SUCCESS;
            }
        }

        $totalFiles = array_sum(array_map(static fn (ScanGroup $g) => $g->fileCount, $groups));
        $totalBytes = array_sum(array_map(static fn (ScanGroup $g) => $g->totalSize, $groups));

        $this->line('');
        $this->table(
            ['Folder / Rule', 'Deskripsi', 'File', 'Ukuran'],
            array_map(static fn (ScanGroup $g) => [
                $g->folderLabel,
                mb_strimwidth($g->description, 0, 60, '…'),
                number_format($g->fileCount),
                $g->formattedSize(),
            ], $groups),
        );

        $this->line('');
        $this->info(sprintf(
            'Total: %s file, %s',
            number_format($totalFiles),
            ScanGroup::formatBytes($totalBytes),
        ));

        if (! $this->option('hapus')) {
            $this->line('');
            $this->comment('Jalankan dengan --hapus untuk menghapus semua kandidat di atas.');

            return self::SUCCESS;
        }

        $this->line('');

        if (! $this->confirm("Hapus {$totalFiles} file (" . ScanGroup::formatBytes($totalBytes) . ')? Tindakan ini tidak dapat dibatalkan.', false)) {
            $this->line('Dibatalkan.');

            return self::SUCCESS;
        }

        $bulkKeys  = array_map(static fn (ScanGroup $g) => $g->key, array_filter($groups, static fn (ScanGroup $g) => $g->isBulk));
        $filePaths = array_merge(...array_map(
            static fn (ScanGroup $g) => array_keys($g->files),
            array_filter($groups, static fn (ScanGroup $g) => ! $g->isBulk),
        ));

        $result = $scanner->delete($bulkKeys, $filePaths);

        $this->info(sprintf(
            'Selesai: %s file dihapus, %s dibebaskan.',
            number_format($result['deleted']),
            ScanGroup::formatBytes($result['freed_bytes']),
        ));

        foreach ($result['errors'] as $err) {
            $this->warn('  ! ' . $err);
        }

        return self::SUCCESS;
    }
}
