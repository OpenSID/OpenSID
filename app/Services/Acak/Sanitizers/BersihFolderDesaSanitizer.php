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
use App\Services\FolderDesaCleaner\FolderDesaScanner;

/**
 * Bersihkan folder desa sebagai bagian dari acak — memakai engine yang sama
 * dengan fitur "Bersihkan Folder Desa" (App\Services\FolderDesaCleaner). Semua
 * grup yang ditemukan pemindaian dihapus (cache, file usang/orphan, konten liar),
 * tanpa perlu pilihan pengguna.
 *
 * Berjalan setelah de-identifikasi DB agar file yang baru menjadi usang (mis.
 * kolom foto yang dikosongkan) ikut tersapu.
 */
final class BersihFolderDesaSanitizer implements Sanitizer
{
    /**
     * @param string                $basePath Root folder untuk dipindai. Kosong = base_path()
     *                                       (folder desa nyata). Dapat diinjeksi ke
     *                                       direktori sementara untuk pengujian.
     * @param FolderDesaScanner|null $scanner Scanner khusus untuk pengujian; default
     *                                       dibangun dari $basePath dengan rule bawaan.
     * @param list<string>          $keep    Path relatif yang DIKECUALIKAN dari penghapusan
     *                                       (mis. file yang di-deselect pengguna di halaman
     *                                       Bersihkan Folder Desa). Berlaku juga untuk grup
     *                                       direktori: bila ada file yang dipertahankan di
     *                                       bawahnya, direktori tidak dihapus massal.
     */
    public function __construct(
        private string $basePath = '',
        private ?FolderDesaScanner $scanner = null,
        private array $keep = [],
    ) {
    }

    public function label(): string
    {
        return 'Membersihkan folder desa';
    }

    public function sanitize(AcakContext $context, AcakResult $result): void
    {
        // Lindungi folder desa nyata saat pengujian: pemakaian default (tanpa
        // scanner/basePath injeksi) dilewati di lingkungan testing.
        if ($this->scanner === null && $this->basePath === '' && app()->environment('testing')) {
            return;
        }

        $scanner = $this->scanner ?? new FolderDesaScanner([], $this->basePath);
        $groups  = $scanner->scan();

        if ($groups === []) {
            return;
        }

        // File yang dipertahankan: dari konstruktor (pengujian) + opsi run (daftar
        // tersimpan dari halaman Bersihkan Folder Desa, diteruskan lewat keepFiles).
        $keep      = array_merge($this->keep, $context->options->keepFiles);
        $partition = FolderDesaScanner::partition($groups, $keep);

        $swept = $scanner->delete($partition['bulkKeys'], $partition['filePaths']);

        $result->orphanedDeleted += $swept['deleted'];
        if ($swept['deleted'] > 0) {
            $result->addRows($swept['deleted']);
        }

        foreach ($swept['errors'] as $error) {
            $result->addWarning('bersih folder desa: ' . $error);
        }
    }
}
