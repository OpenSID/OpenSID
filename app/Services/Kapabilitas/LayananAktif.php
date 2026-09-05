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
 * Sumber tingkat (tier) layanan aktif milik add-on.
 *
 * Sebelumnya core (Tracker) memanggil langsung
 * `PelangganService::getLayananAktifTier()` untuk melaporkan tingkat layanan
 * desa (mis. 'umum'/'premium'/'siappakai') ke server pantau. Kini core hanya
 * bertanya ke sumber ini; add-on (mis. modul Pelanggan) memasang providernya
 * saat boot.
 *
 * Tanpa provider → {@see tier()} mengembalikan `'umum'` (core OSS: tak ada
 * lapisan berbayar, jadi tingkat layanan selalu umum).
 */
class LayananAktif
{
    /**
     * @var (callable(): string)|null
     */
    private $provider = null;

    /**
     * Pasang provider tingkat layanan aktif.
     *
     * @param callable(): string $provider
     */
    public function register(callable $provider): void
    {
        $this->provider = $provider;
    }

    /**
     * Tingkat layanan aktif desa. Default `'umum'` bila tak ada provider
     * terpasang (mis. modul Pelanggan absen di rilis Umum).
     */
    public function tier(): string
    {
        return $this->provider !== null ? ($this->provider)() : 'umum';
    }
}
