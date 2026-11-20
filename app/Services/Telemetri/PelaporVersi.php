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

namespace App\Services\Telemetri;

/**
 * Pelapor versi terpasang ke penyedia hulu milik add-on.
 *
 * Sebelumnya core (helper `kirim_versi_opensid`, dipanggil Tracker) mem-POST
 * versi ke `server_layanan/api/v1/pelanggan/catat-versi` langsung. Kini core
 * hanya memicu registry ini; add-on (mis. modul Pelanggan) memasang pelapornya
 * saat boot. Tanpa add-on → {@see lapor()} no-op (core OSS tak melapor ke hulu
 * berbayar mana pun).
 */
class PelaporVersi
{
    /**
     * @var (callable(string): void)|null
     */
    private $pelapor = null;

    /**
     * @param callable(string): void $pelapor
     */
    public function register(callable $pelapor): void
    {
        $this->pelapor = $pelapor;
    }

    /**
     * Laporkan versi terpasang untuk suatu kode desa. No-op bila tak ada pelapor
     * terpasang (mis. modul Pelanggan absen di rilis Umum).
     */
    public function lapor(string $kodeDesa): void
    {
        if ($this->pelapor !== null) {
            ($this->pelapor)($kodeDesa);
        }
    }
}
