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
 * Sumber KUNCI (bukan boolean) untuk fitur berbayar — dipakai sebagai
 * gerbang LIVENESS tambahan, BUKAN untuk mendekripsi aset statis yang
 * dibagikan identik ke semua desa dalam satu ZIP rilis.
 *
 * KOREKSI 2026-08-20 (lihat dokumentasi/inventaris-permukaan-premium-layanan.md
 * §12.14.1 untuk kajian penuh): draf awal seam ini mengklaim kunci per-desa
 * bisa "mendekripsi aset milik add-on sendiri". Itu keliru secara matematis
 * untuk model distribusi OpenSID saat ini — satu ciphertext statis yang
 * dibagikan sama ke SEMUA desa dalam satu ZIP rilis (lihat Layanan#1325)
 * hanya bisa didekripsi oleh SATU kunci tertentu; kunci yang berbeda per
 * desa (dengan desain) tak bisa sekaligus membuka ciphertext yang sama
 * untuk semua desa. Tak ada trik pencampuran kunci yang menghindari batasan
 * ini selama artefak rilisnya tetap satu ZIP identik untuk semua desa.
 *
 * Nilai nyata seam ini: {@see kunci()} membaca data yang di-cache dengan
 * TTL KERAS 24 JAM dan TANPA fallback ke nilai basi (lihat
 * `PelangganService::ambilKunciFitur()` di modul-pelanggan) — kegagalan
 * jaringan/backoff/tak-berhak APA PUN mengembalikan `null`, bukan nilai
 * lama. Ini beda dari {@see GerbangFitur} ("boleh?") dan
 * {@see SumberStatusFitur} ("aktif?"), yang membaca cache `status_langganan`
 * bertahan 30 HARI. Menggerbangkan operasi fitur di sini (bukan cuma
 * kode gate yang trivial dipatch, TAPI karena sumber datanya sendiri jauh
 * lebih ketat) mempersempit jendela "langganan lewat tapi fitur masih
 * jalan" dari hingga 30 hari menjadi maksimum 24 jam. Seam ini TETAP hanya
 * gerbang boolean-setara (bisa dilewati dengan menghapus satu `if`, sama
 * seperti GerbangFitur) — nilainya murni pada siklus cache sumbernya, bukan
 * ketahanan kriptografis.
 *
 * Perlindungan aset-di-disk yang sesungguhnya (tak bisa dibaca sama sekali
 * tanpa loader proprietary) menunggu enkode IonCube menyeluruh atas modul
 * berbayar (§4.3/§12.14 dokumen master) — TODO, belum dikerjakan. Proteksi
 * genuinely per-desa (satu ciphertext BEDA untuk setiap desa, memakai
 * fitur domain-lock/license-lock bawaan IonCube, digenerate on-demand oleh
 * Layanan saat unduh) diajukan terpisah — lihat Layanan_OpenDESA#1345.
 *
 * Kunci PER-FITUR (bukan satu kunci global): setiap add-on memakai kunci
 * `$feature`-nya sendiri (mis. 'dtsen', 'anjungan') sehingga kebocoran/
 * pembongkaran kunci satu fitur tak otomatis membuka fitur lain.
 *
 * Add-on penyedia data langganan (mis. modul Pelanggan) memasang satu
 * provider lewat {@see setelPenyedia()} saat boot; konsumen (mis. add-on
 * fitur seperti DTSEN) bertanya lewat {@see kunci()}. Tanpa provider →
 * `null` (perilaku core OSS tanpa lapisan berbayar).
 */
class PenyediaKunciFitur
{
    /**
     * @var (callable(string): ?string)|null
     */
    private $provider = null;

    /**
     * Pasang provider kunci fitur. Provider menerima kunci fitur (mis.
     * 'dtsen') dan mengembalikan kunci turunan (base64/hex), atau `null`
     * bila desa tak berhak/kunci tak tersedia saat ini.
     *
     * @param callable(string): ?string $provider
     */
    public function setelPenyedia(callable $provider): void
    {
        $this->provider = $provider;
    }

    /**
     * Kunci untuk `$feature`, atau `null` bila tak ada provider terpasang
     * (mis. modul Pelanggan absen) atau desa tak berhak saat ini.
     */
    public function kunci(string $feature): ?string
    {
        return $this->provider !== null ? ($this->provider)($feature) : null;
    }
}
