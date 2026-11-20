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

namespace App\Services\Acak;

use App\Services\Acak\Support\SyntheticValueGenerator;
use Closure;

/**
 * State bersama yang mengalir melalui rangkaian Sanitizer dalam sekali proses acak.
 *
 * Selain generator dan info identitas desa, context menyimpan pemetaan nilai
 * lama → baru yang dibangun oleh sebuah Sanitizer dan dibutuhkan oleh Sanitizer
 * berikutnya untuk menjaga integritas referensial (mis. NIK di tabel bantuan,
 * no_kk di log penduduk).
 */
final class AcakContext
{
    /** @var array<string, string> nik lama → nik baru */
    public array $nikMap = [];

    /** @var array<string, string> no_kk lama → no_kk baru */
    public array $noKkMap = [];

    /** @var array<string, string> nik lama → nama sintetis baru (untuk konsistensi lintas tabel) */
    public array $nikNama = [];

    /** @var array<string, string> no_kk lama → nama kepala keluarga sintetis */
    public array $kkKepalaNama = [];

    private ?Closure $onProgress = null;

    public function __construct(
        public readonly AcakOptions $options,
        public readonly SyntheticValueGenerator $gen,
        public readonly string $kodeKecamatan,
        public readonly string $namaKabupaten,
    ) {
    }

    public function setProgress(?Closure $onProgress): void
    {
        $this->onProgress = $onProgress;
    }

    /**
     * Laporkan langkah berjalan. Dipanggil per-langkah oleh AcakService dan
     * per-batch oleh Sanitizer besar agar stream progres (SSE) tetap hidup dan
     * gateway tidak timeout saat memproses ribuan baris.
     */
    public function progress(string $step): void
    {
        if ($this->onProgress !== null) {
            ($this->onProgress)($step);
        }
    }
}
