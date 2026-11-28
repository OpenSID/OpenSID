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

namespace App\Services\Kapabilitas;

/**
 * Registry gerbang entitlement (langganan) fitur berbayar.
 *
 * Core TIDAK tahu fitur berbayar apa pun. Add-on mendaftarkan resolver
 * boolean-nya lewat {@see register()} saat boot; core bertanya lewat
 * {@see allows()}. Fitur tanpa resolver terdaftar → `false` (tidak berhak).
 *
 * Inilah titik penegakan terbuka pengganti gerbang `PREMIUM` tersembunyi:
 * status entitlement menjadi urusan add-on (mis. add-on Layanan), bukan core.
 */
class GerbangFitur
{
    /**
     * @var array<string, callable(): bool>
     */
    private array $resolvers = [];

    /**
     * Daftarkan resolver entitlement untuk sebuah fitur.
     *
     * @param callable(): bool $resolver
     */
    public function daftarkan(string $feature, callable $resolver): void
    {
        $this->resolvers[$feature] = $resolver;
    }

    /**
     * Apakah fitur ini berhak (berlangganan aktif)? Default `false` bila
     * tak ada add-on yang mendaftarkan resolver-nya.
     */
    public function mengizinkan(string $feature): bool
    {
        $resolver = $this->resolvers[$feature] ?? null;

        return $resolver !== null && (bool) $resolver();
    }
}
