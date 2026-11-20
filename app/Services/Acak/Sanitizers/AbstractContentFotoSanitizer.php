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
use App\Services\Acak\Support\ContentImageProcessor;
use App\Services\Acak\Support\ImageToolkit;

/**
 * Dasar bersama untuk sanitizer foto konten (artikel/galeri/media). Menangani
 * pemilihan mode ({@see \App\Services\Acak\AcakOptions::$fotoKontenMode}), resolusi
 * direktori (dengan guard pengujian), dan pembentukan {@see ContentImageProcessor}.
 * Sub-kelas cukup mengumpulkan daftar berkas dan mendelegasikan pemrosesan.
 */
abstract class AbstractContentFotoSanitizer implements Sanitizer
{
    public function __construct(protected readonly ?string $dir = null)
    {
    }

    public function sanitize(AcakContext $context, AcakResult $result): void
    {
        $mode = $context->options->fotoKontenMode;
        if ($mode === 'none') {
            return;
        }

        $dir = $this->resolveDir();
        if ($dir === null) {
            return; // guard pengujian
        }

        if (! is_dir($dir)) {
            $result->addWarning($this->warnLabel() . ': direktori tidak ditemukan (' . $dir . ')');

            return;
        }

        $toolkit = new ImageToolkit($context->options->seed);
        if (! $toolkit->gdAvailable()) {
            $result->addWarning($this->warnLabel() . ': ekstensi GD tidak tersedia');

            return;
        }

        $processor = new ContentImageProcessor($toolkit, $mode);
        $count     = $this->process($processor, rtrim($dir, '/\\') . DIRECTORY_SEPARATOR, $context, $result);

        $this->recordCount($result, $count);
    }

    /**
     * Kumpulkan berkas & proses; kembalikan jumlah berkas yang berhasil diolah.
     */
    abstract protected function process(ContentImageProcessor $processor, string $dir, AcakContext $context, AcakResult $result): int;

    /** Nama sub-folder di bawah desa/upload (mis. 'artikel'). */
    abstract protected function defaultDirName(): string;

    /** Prefiks label untuk peringatan. */
    abstract protected function warnLabel(): string;

    /** Simpan jumlah ke penghitung yang sesuai di AcakResult. */
    abstract protected function recordCount(AcakResult $result, int $count): void;

    protected function resolveDir(): ?string
    {
        if ($this->dir !== null) {
            return $this->dir;
        }

        if (app()->environment('testing')) {
            return null;
        }

        return base_path('desa/upload/' . $this->defaultDirName());
    }
}
