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

namespace App\Services\Pengumuman;

/**
 * Registry banner/pemberitahuan admin milik add-on.
 *
 * Sebelumnya core (Admin_Controller/Beranda) memanggil langsung
 * `PelangganService::statusLangganan()` / `statusPercobaan()` untuk merakit
 * banner status langganan. Kini core hanya menanyakan registry ini; add-on
 * (mis. modul Pelanggan) mendaftarkan penyedia banner-nya saat boot.
 *
 * Tiap penyedia mengembalikan satu notice (array data banner) atau nilai
 * kosong/`null` bila tak ada yang perlu ditampilkan. Tanpa add-on →
 * {@see notices()} mengembalikan `[]` (core OSS tak menampilkan banner apa pun).
 */
class SumberPengumuman
{
    /**
     * @var list<callable(): mixed>
     */
    private array $providers = [];

    /**
     * Daftarkan penyedia banner admin.
     *
     * @param callable(): mixed $provider
     */
    public function daftarkan(callable $provider): void
    {
        $this->providers[] = $provider;
    }

    /**
     * Kumpulan notice non-kosong dari semua penyedia terdaftar, dalam urutan
     * pendaftaran. Default `[]` bila tak ada penyedia.
     *
     * @return list<mixed>
     */
    public function pengumuman(): array
    {
        $notices = [];

        foreach ($this->providers as $provider) {
            $notice = $provider();

            if (! empty($notice)) {
                $notices[] = $notice;
            }
        }

        return $notices;
    }
}
