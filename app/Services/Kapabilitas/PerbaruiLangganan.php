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
 * Registry penyegar (refresher) state langganan milik add-on.
 *
 * Sebelumnya core (Admin_Controller/Web_Controller) memanggil langsung
 * `PelangganService::perbaruiLangganan()` untuk menyinkronkan ulang cache
 * status langganan dari server Layanan. Kini core hanya memicu registry ini di
 * titik siklus-hidup yang sama; add-on (mis. modul Pelanggan) mendaftarkan
 * callback penyegarnya saat boot.
 *
 * Tiap callback tidak mengembalikan apa pun — ia melakukan efek samping
 * (mis. memanggil API lalu menulis ulang cache). Tanpa add-on → {@see refresh()}
 * tidak melakukan apa-apa (core OSS tak punya langganan untuk disegarkan).
 */
class PerbaruiLangganan
{
    /**
     * @var list<callable(): void>
     */
    private array $refreshers = [];

    /**
     * @var (callable(): bool)|null
     */
    private $predikat = null;

    /**
     * Daftarkan callback penyegar state langganan.
     *
     * @param callable(): void $refresher
     */
    public function register(callable $refresher): void
    {
        $this->refreshers[] = $refresher;
    }

    /**
     * Daftarkan predikat "perlu menyegarkan state langganan" (mis. cache status
     * kedaluwarsa / token hadir). Menggantikan pengecekan langsung core
     * (Admin_Controller) atas cache `status_langganan` + token berlangganan.
     *
     * @param callable(): bool $predikat
     */
    public function registerPredikat(callable $predikat): void
    {
        $this->predikat = $predikat;
    }

    /**
     * Apakah state langganan perlu disegarkan. Default `false` bila tak ada
     * predikat terpasang (mis. modul Pelanggan absen di rilis Umum).
     */
    public function perlu(): bool
    {
        return $this->predikat !== null && ($this->predikat)();
    }

    /**
     * Jalankan semua penyegar terdaftar, dalam urutan pendaftaran. No-op bila
     * tak ada penyegar (mis. modul Pelanggan absen di rilis Umum).
     */
    public function refresh(): void
    {
        foreach ($this->refreshers as $refresher) {
            $refresher();
        }
    }
}
