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

class IdentifikasiRtfDiArsip extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'arsip:identifikasi-rtf';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Identifikasi file RTF yang tersimpan di arsip resmi (desa/arsip/)';

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
        $this->info('>_ Mulai identifikasi RTF di arsip resmi...');
        $this->newLine();

        // Cari semua entri log_surat dengan nama_surat berekstensi .rtf
        $rtfEntries = LogSurat::where('nama_surat', 'like', '%.rtf')->get();

        $totalRtf = $rtfEntries->count();
        $rtfDenganPdf = 0;
        $rtfTanpaPdf = 0;
        $rtfFileExist = 0;
        $rtfFileNotExist = 0;

        $this->info("Total entri log_surat dengan ekstensi .rtf: {$totalRtf}");
        $this->newLine();

        if ($totalRtf === 0) {
            $this->info('>_ Tidak ada file RTF di arsip.');
            return;
        }

        $this->table(
            ['ID', 'Nama Surat', 'File RTF Ada', 'File PDF Ada'],
            $rtfEntries->map(function ($entry) use (&$rtfDenganPdf, &$rtfTanpaPdf, &$rtfFileExist, &$rtfFileNotExist) {
                $rtfPath = FCPATH . $entry->rtfFile();
                $pdfPath = FCPATH . $entry->pdfFile();
                
                $rtfExists = is_file($rtfPath);
                $pdfExists = is_file($pdfPath);
                
                if ($rtfExists) {
                    $rtfFileExist++;
                } else {
                    $rtfFileNotExist++;
                }
                
                if ($pdfExists) {
                    $rtfDenganPdf++;
                } else {
                    $rtfTanpaPdf++;
                }
                
                return [
                    $entry->id,
                    $entry->nama_surat,
                    $rtfExists ? 'Ya' : 'Tidak',
                    $pdfExists ? 'Ya' : 'Tidak',
                ];
            })
        );

        $this->newLine();
        $this->info('>_ Ringkasan:');
        $this->line("  - Total RTF di database: {$totalRtf}");
        $this->line("  - File RTF ada di disk: {$rtfFileExist}");
        $this->line("  - File RTF tidak ada di disk: {$rtfFileNotExist}");
        $this->line("  - RTF yang punya versi PDF: {$rtfDenganPdf}");
        $this->line("  - RTF yang tidak punya versi PDF: {$rtfTanpaPdf}");
        $this->newLine();
        
        if ($rtfDenganPdf > 0) {
            $this->warn("Ada {$rtfDenganPdf} file RTF yang sudah punya versi PDF. File RTF ini bisa dipindahkan ke folder sementara.");
        }
        
        if ($rtfTanpaPdf > 0) {
            $this->warn("Ada {$rtfTanpaPdf} file RTF yang tidak punya versi PDF. Perlu tindak lanjut manual.");
        }
        
        $this->newLine();
        $this->info('>_ Selesai.');
    }
}
