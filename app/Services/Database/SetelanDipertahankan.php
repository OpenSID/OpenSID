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

namespace App\Services\Database;

use Closure;

/**
 * Registry kunci setting yang dipertahankan saat restore database.
 *
 * Core `Database::restore()` sudah mempertahankan kredensial lokal generik
 * (notifikasi telegram/email) agar tak tertimpa data dari instalasi asal backup.
 * Sebelumnya ia JUGA meng-hardcode `layanan_opendesa_token` — pengetahuan
 * langganan. Kini add-on penyedia langganan mendaftarkan kunci setting miliknya
 * ke registry ini; core hanya melestarikan apa pun yang terdaftar.
 *
 * Null-object default: tanpa pendaftaran → `[]` (core OSS tak melestarikan setting
 * langganan apa pun saat restore).
 */
class SetelanDipertahankan
{
    /**
     * @var list<Closure(): (array<int, string>)>
     */
    private array $penyedia = [];

    /**
     * Daftarkan penyedia kunci setting yang harus dipertahankan saat restore.
     *
     * Penyedia berupa closure agar dapat memutuskan secara kondisional (mis.
     * hanya bila opsi "hapus token" tidak dicentang). Dievaluasi sekali sebelum
     * restore; nilainya ditangkap lalu dikembalikan setelah restore.
     *
     * @param Closure(): (array<int, string>) $penyedia
     */
    public function daftar(Closure $penyedia): void
    {
        $this->penyedia[] = $penyedia;
    }

    /**
     * Kumpulan kunci setting yang saat ini harus dipertahankan.
     *
     * @return list<string>
     */
    public function kunci(): array
    {
        return collect($this->penyedia)
            ->flatMap(static fn (Closure $penyedia): array => (array) $penyedia())
            ->filter(static fn (string $kunci): bool => $kunci !== '')
            ->unique()
            ->values()
            ->all();
    }
}
