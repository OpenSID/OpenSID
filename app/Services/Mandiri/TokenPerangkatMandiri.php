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

namespace App\Services\Mandiri;

/**
 * Pemeriksa token perangkat Layanan Mandiri milik add-on.
 *
 * Sebelumnya core (AuthenticatedSessionController) membandingkan token perangkat
 * kios langsung dengan `setting('layanan_opendesa_token')`. Kini core hanya
 * bertanya ke registry ini; add-on (mis. modul Pelanggan) memasang pemeriksanya
 * saat boot. Tanpa add-on → {@see cocok()} selalu `false` (core OSS tak punya
 * token berlangganan; autentikasi perangkat via token dinonaktifkan, hanya
 * guard biasa yang berlaku).
 */
class TokenPerangkatMandiri
{
    /**
     * @var (callable(?string): bool)|null
     */
    private $pemeriksa = null;

    /**
     * @param callable(?string): bool $pemeriksa
     */
    public function register(callable $pemeriksa): void
    {
        $this->pemeriksa = $pemeriksa;
    }

    /**
     * Apakah token perangkat yang diberikan cocok dengan token sah. Default
     * `false` bila tak ada pemeriksa terpasang (mis. modul Pelanggan absen).
     */
    public function cocok(?string $token): bool
    {
        return $this->pemeriksa !== null && ($this->pemeriksa)($token);
    }
}
