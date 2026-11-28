<?php

declare(strict_types=1);

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

namespace App\Services\Acak\Sanitizers;

use App\Services\Acak\AcakContext;
use App\Services\Acak\AcakResult;
use App\Services\Acak\Contracts\Sanitizer;
use App\Services\Acak\Support\ArsipPlaceholderWriter;
use App\Services\Acak\Support\ImageToolkit;
use App\Services\Acak\Support\SuratRenderer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Throwable;

/**
 * Ganti berkas arsip surat berisi PII dengan berkas sintetis, dan arahkan ulang
 * nama berkas di database (`nama_surat`/`lampiran`) ke `surat_{id}.pdf` /
 * `surat_dinas_{id}.pdf`.
 *
 * Berjalan SETELAH pembersihan folder desa (agar berkas asli yatim ikut tersapu),
 * lalu untuk tiap surat: hapus berkas asli yang tersisa, ganti nama di DB, dan
 * buat berkas baru — PDF placeholder minimal (default) atau render surat sintetis
 * dari template (mode 'sintetis', hanya konteks web; jatuh ke placeholder bila gagal).
 */
final class ArsipSuratSanitizer implements Sanitizer
{
    /** @var array<string, string> tabel → prefiks nama berkas */
    private const TABLES = [
        'log_surat'       => 'surat',
        'log_surat_dinas' => 'surat_dinas',
    ];

    public function __construct(
        private readonly ?string $arsipDir = null,
        private readonly ?string $thumbsDir = null,
    ) {
    }

    public function label(): string
    {
        return 'Membuat arsip surat sintetis';
    }

    public function sanitize(AcakContext $context, AcakResult $result): void
    {
        $arsipDir = $this->resolveDir($this->arsipDir, 'desa/arsip');
        if ($arsipDir === null) {
            return; // guard pengujian
        }
        $thumbsDir = $this->resolveDir($this->thumbsDir, 'desa/upload/thumbs') ?? $arsipDir;

        @mkdir($arsipDir, 0777, true);
        @mkdir($thumbsDir, 0777, true);

        $writer   = new ArsipPlaceholderWriter(new ImageToolkit($context->options->seed));
        $sintetis = $context->options->suratMode === 'sintetis' && $this->ci3Tersedia();
        $renderer = $sintetis ? new SuratRenderer() : null;

        $done  = 0;
        $total = $this->totalRows();

        foreach (self::TABLES as $table => $prefix) {
            if (! Schema::hasTable($table)) {
                continue;
            }

            $rows = DB::table($table)->select(['id', 'nama_surat', 'lampiran', 'input'])->orderBy('id')->get();

            foreach ($rows as $r) {
                $newName     = "{$prefix}_{$r->id}.pdf";
                $newLampiran = $r->lampiran !== null ? "{$prefix}_{$r->id}_lampiran.pdf" : null;

                $this->deleteStale($arsipDir, $thumbsDir, (string) ($r->nama_surat ?? ''), (string) ($r->lampiran ?? ''), $newName);

                DB::table($table)->where('id', $r->id)->update([
                    'nama_surat' => $newName,
                    'lampiran'   => $newLampiran,
                ]);

                if ($renderer !== null && $table === 'log_surat' && $this->hasInput($r->input)) {
                    try {
                        $renderer->render((int) $r->id, $arsipDir, $newName);
                        $writer->thumbnail($thumbsDir, $newName);
                        $result->suratSintetisGenerated++;
                        $result->arsipReplaced++;
                        $this->tick($context, ++$done, $total);

                        continue;
                    } catch (Throwable $e) {
                        $result->addWarning("render surat sintetis #{$r->id} gagal, memakai placeholder: " . $e->getMessage());
                        Log::warning("[AcakService] render surat sintetis #{$r->id} gagal: " . $e->getMessage());
                    }
                }

                if ($writer->writePlaceholder($arsipDir, $newName, $newLampiran)) {
                    $result->arsipReplaced++;
                }
                $writer->thumbnail($thumbsDir, $newName);
                $this->tick($context, ++$done, $total);
            }

            $result->markTable("{$table} (arsip)");
        }
    }

    /**
     * Hapus berkas arsip & thumbnail lama bila namanya berbeda dari nama baru,
     * agar dokumen ber-PII tidak tertinggal di disk.
     */
    private function deleteStale(string $arsipDir, string $thumbsDir, string $oldMain, string $oldLampiran, string $newName): void
    {
        $arsipDir  = rtrim($arsipDir, '/\\') . DIRECTORY_SEPARATOR;
        $thumbsDir = rtrim($thumbsDir, '/\\') . DIRECTORY_SEPARATOR;

        if ($oldMain !== '' && $oldMain !== $newName) {
            @unlink($arsipDir . $oldMain);
            @unlink($thumbsDir . pathinfo($oldMain, PATHINFO_FILENAME) . '.png');
        }
        if ($oldLampiran !== '') {
            @unlink($arsipDir . $oldLampiran);
        }
    }

    private function totalRows(): int
    {
        $total = 0;
        foreach (array_keys(self::TABLES) as $table) {
            if (Schema::hasTable($table)) {
                $total += (int) DB::table($table)->count();
            }
        }

        return $total;
    }

    /**
     * Emit progres berkala; jaga koneksi SSE tetap hidup & GC memori saat mode
     * sintetis me-render ratusan PDF.
     */
    private function tick(AcakContext $context, int $done, int $total): void
    {
        if ($done % 10 !== 0 && $done !== $total) {
            return;
        }

        $context->progress("Membuat arsip surat… ({$done}/{$total})");
        gc_collect_cycles();
    }

    private function hasInput(mixed $input): bool
    {
        $input = (string) $input;

        return $input !== '' && $input !== '[]' && $input !== '{}' && $input !== 'null';
    }

    private function ci3Tersedia(): bool
    {
        return $this->ciInstance() !== null;
    }

    /**
     * Instance CI3 yang aktif, atau null pada konteks non-CI (artisan murni /
     * pengujian). Return type nullable — get_instance() dari stub CI dianggap
     * non-null oleh analisis statis, padahal runtime bisa null.
     */
    private function ciInstance(): ?object
    {
        return function_exists('get_instance') ? get_instance() : null;
    }

    private function resolveDir(?string $override, string $default): ?string
    {
        if ($override !== null) {
            return $override;
        }

        if (app()->environment('testing')) {
            return null;
        }

        return base_path($default);
    }
}
