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

use App\Models\LogSurat;
use Illuminate\Console\Command;

class PindahRtfDariArsip extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'arsip:pindah-rtf {--dry-run : Simulasi tanpa memindahkan file}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Pindahkan file RTF dari arsip resmi ke folder sementara (opsional)';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $isDryRun = $this->option('dry-run');
        
        if ($isDryRun) {
            $this->warn('MODE DRY-RUN: Tidak ada file yang akan dipindahkan (hanya simulasi)');
            $this->newLine();
        } else {
            $this->warn('PERINGATAN: File RTF akan dipindahkan dari arsip resmi ke folder sementara.');
            $this->warn('Pastikan Anda sudah backup data sebelum melanjutkan.');
            $this->newLine();
            
            if (! $this->confirm('Lanjutkan pemindahan file RTF?')) {
                $this->info('>_ Dibatalkan.');
                return;
            }
            $this->newLine();
        }

        $this->info('>_ Mulai pemindahan RTF dari arsip resmi...');
        $this->newLine();

        // 1. Cari semua entri log_surat dengan nama_surat berekstensi .rtf
        $rtfEntries = LogSurat::where('nama_surat', 'like', '%.rtf')->get();

        // 2. Cari semua file RTF fisik di folder arsip (termasuk kk_*.rtf yang tidak tercatat di database)
        $arsipDir = FCPATH . LOKASI_ARSIP;
        $rtfFiles = glob($arsipDir . '*.rtf');

        $totalRtfDb = $rtfEntries->count();
        $totalRtfFiles = count($rtfFiles);
        $dipindahkan = 0;
        $gagal = 0;
        $skipTanpaPdf = 0;
        $skipFileNotExist = 0;

        $this->info("Total RTF di database log_surat: {$totalRtfDb}");
        $this->info("Total file RTF fisik di arsip: {$totalRtfFiles}");
        $this->newLine();

        if ($totalRtfDb === 0 && $totalRtfFiles === 0) {
            $this->info('>_ Tidak ada file RTF di arsip.');
            return;
        }

        // Proses RTF dari database log_surat
        if ($totalRtfDb > 0) {
            $this->info('Memproses RTF dari database log_surat...');
            $progressBar = $this->output->createProgressBar($totalRtfDb);
            $progressBar->start();

            foreach ($rtfEntries as $entry) {
                $rtfPath = FCPATH . $entry->rtfFile();
                $pdfPath = FCPATH . $entry->pdfFile();
                
                // Cek apakah file RTF ada
                if (! is_file($rtfPath)) {
                    $skipFileNotExist++;
                    $progressBar->advance();
                    continue;
                }
                
                // Cek apakah ada versi PDF
                if (! is_file($pdfPath)) {
                    $skipTanpaPdf++;
                    $progressBar->advance();
                    continue;
                }
                
                // Pindahkan file RTF ke folder sementara
                $rtfFileName = basename($rtfPath);
                $targetPath = FCPATH . LOKASI_RTF_SEMENTARA . $rtfFileName;
                
                // Pastikan folder tujuan ada
                $targetDir = dirname($targetPath);
                if (! is_dir($targetDir)) {
                    mkdir($targetDir, 0775, true);
                }
                
                if (! $isDryRun) {
                    if (rename($rtfPath, $targetPath)) {
                        $dipindahkan++;
                    } else {
                        $gagal++;
                    }
                } else {
                    $dipindahkan++;
                }
                
                $progressBar->advance();
            }

            $progressBar->finish();
            $this->newLine();
        }

        // Proses RTF fisik yang tidak tercatat di database (seperti kk_*.rtf)
        if ($totalRtfFiles > 0) {
            $this->info('Memproses file RTF fisik yang tidak tercatat di database...');
            $progressBar = $this->output->createProgressBar($totalRtfFiles);
            $progressBar->start();

            foreach ($rtfFiles as $rtfPath) {
                $rtfFileName = basename($rtfPath);
                
                // Cek apakah file ini sudah diproses dari database
                $alreadyProcessed = false;
                foreach ($rtfEntries as $entry) {
                    if (basename($entry->rtfFile()) === $rtfFileName) {
                        $alreadyProcessed = true;
                        break;
                    }
                }
                
                if ($alreadyProcessed) {
                    $progressBar->advance();
                    continue;
                }
                
                // Pindahkan file RTF ke folder sementara
                $targetPath = FCPATH . LOKASI_RTF_SEMENTARA . $rtfFileName;
                
                // Pastikan folder tujuan ada
                $targetDir = dirname($targetPath);
                if (! is_dir($targetDir)) {
                    mkdir($targetDir, 0775, true);
                }
                
                if (! $isDryRun) {
                    if (rename($rtfPath, $targetPath)) {
                        $dipindahkan++;
                    } else {
                        $gagal++;
                    }
                } else {
                    $dipindahkan++;
                }
                
                $progressBar->advance();
            }

            $progressBar->finish();
            $this->newLine();
        }

        $this->newLine();
        $this->info('>_ Ringkasan:');
        $this->line("  - Total RTF di database: {$totalRtfDb}");
        $this->line("  - Total file RTF fisik: {$totalRtfFiles}");
        $this->line("  - File RTF dipindahkan: {$dipindahkan}");
        $this->line("  - Gagal memindahkan: {$gagal}");
        $this->line("  - Skip (file tidak ada): {$skipFileNotExist}");
        $this->line("  - Skip (tidak ada PDF): {$skipTanpaPdf}");
        $this->newLine();
        
        if ($skipTanpaPdf > 0) {
            $this->warn("Ada {$skipTanpaPdf} file RTF yang tidak punya versi PDF. File ini tidak dipindahkan untuk keamanan.");
            $this->warn('Anda perlu mengecek manual file-file tersebut.');
        }
        
        if ($gagal > 0) {
            $this->error("Ada {$gagal} file yang gagal dipindahkan. Periksa permission file/folder.");
        }
        
        if ($dipindahkan > 0 && ! $isDryRun) {
            $this->info("File RTF yang berhasil dipindahkan sekarang ada di: " . LOKASI_RTF_SEMENTARA);
        }
        
        $this->newLine();
        $this->info('>_ Selesai.');
    }
}
