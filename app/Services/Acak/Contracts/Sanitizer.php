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

namespace App\Services\Acak\Contracts;

use App\Services\Acak\AcakContext;
use App\Services\Acak\AcakResult;

/**
 * Satu unit pengacakan (de-identifikasi) untuk satu domain data.
 *
 * Setiap Sanitizer bertanggung jawab atas satu kelompok tabel/entitas
 * (mis. penduduk, keluarga, wilayah). Orkestrasi dan penanganan error
 * ditangani oleh {@see \App\Services\Acak\AcakService}, sehingga sebuah
 * Sanitizer cukup fokus pada logika domainnya sendiri.
 */
interface Sanitizer
{
    /**
     * Label singkat langkah ini (dipakai untuk progress dan pesan error).
     */
    public function label(): string;

    /**
     * Jalankan pengacakan untuk domain ini.
     *
     * Membaca info bersama (kode kecamatan, generator, pemetaan) dari
     * {@see AcakContext} dan mencatat statistik ke {@see AcakResult}.
     */
    public function sanitize(AcakContext $context, AcakResult $result): void;
}
