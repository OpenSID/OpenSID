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

namespace App\Services\Theme;

/**
 * Sumber data pemesanan tema premium milik add-on.
 *
 * Sebelumnya core (Theme controller) memanggil langsung
 * `PelangganService::apiPelangganPemesanan()` untuk mengurutkan/menandai tema
 * premium yang dipesan desa. Kini core hanya bertanya ke sumber ini; add-on
 * (mis. modul Pelanggan) memasang providernya saat boot.
 *
 * Berbeda dengan {@see \App\Services\Kapabilitas\SumberStatusFitur} yang hanya
 * menjawab boolean, sumber ini mengembalikan data pemesanan mentah (objek respon
 * langganan) agar core dapat menavigasi daftar tema. Tanpa provider →
 * {@see pemesanan()} mengembalikan `null` (core OSS tak punya tema premium).
 */
class SumberTemaBursa
{
    /**
     * @var (callable(): mixed)|null
     */
    private $provider = null;

    /**
     * Pasang provider data pemesanan tema premium.
     *
     * @param callable(): mixed $provider
     */
    public function setelPenyedia(callable $provider): void
    {
        $this->provider = $provider;
    }

    /**
     * Data pemesanan langganan (untuk mengurut tema premium), atau `null` bila
     * tak ada provider terpasang (mis. modul Pelanggan absen di rilis Umum).
     *
     * @return mixed
     */
    public function pemesanan()
    {
        return $this->provider !== null ? ($this->provider)() : null;
    }
}
