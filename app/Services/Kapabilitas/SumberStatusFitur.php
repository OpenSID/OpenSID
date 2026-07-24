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
 * Sumber status keaktifan fitur berbasis data langganan.
 *
 * Berbeda dengan {@see GerbangFitur} yang menjawab "apakah berhak?" (dipakai
 * gerbang fitur), sumber ini menjawab "apakah fitur X tercatat aktif menurut
 * data langganan desa?" — dipakai antar-modul agar sebuah modul (mis. Anjungan)
 * tak perlu memanggil klien Layanan/PelangganService secara langsung.
 *
 * Add-on penyedia data langganan (mis. modul Pelanggan) memasang satu provider
 * lewat {@see setProvider()} saat boot; konsumen bertanya lewat {@see active()}.
 * Tanpa provider → `false` (perilaku core OSS tanpa lapisan berbayar).
 */
class SumberStatusFitur
{
    /**
     * @var (callable(string): bool)|null
     */
    private $provider = null;

    /**
     * Pasang provider status fitur. Provider menerima kunci fitur dan
     * mengembalikan apakah fitur itu aktif menurut data langganan.
     *
     * @param callable(string): bool $provider
     */
    public function setelPenyedia(callable $provider): void
    {
        $this->provider = $provider;
    }

    /**
     * Apakah fitur `$feature` tercatat aktif? Default `false` bila tak ada
     * provider terpasang (mis. modul Pelanggan absen di rilis Umum).
     */
    public function aktif(string $feature): bool
    {
        return $this->provider !== null && (bool) ($this->provider)($feature);
    }
}
