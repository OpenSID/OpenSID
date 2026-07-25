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

namespace Modules\Pelanggan\Services;

use Exception;
use Illuminate\Support\Carbon;

/**
 * Value object atas respons pemesanan langganan (`apiPelangganPemesanan()`).
 *
 * Menampung logika **murni** (tanpa I/O, tanpa CI, tanpa cache) untuk menurunkan
 * status dari respons — sebelumnya tersebar sebagai method statis di
 * PelangganService. Dipisah agar dapat diuji unit tanpa boot HTTP/DB:
 *   StatusLangganan::dariResponse($response)->tier();
 *
 * Semua accessor bertahan terhadap respons `null`/malformed (mengembalikan
 * default "umum"/kosong) — perilaku identik dengan kode lama.
 */
final class StatusLangganan
{
    // Kategori layanan (id dari server Layanan).
    public const KATEGORI_SIAPPAKAI = 9;
    public const KATEGORI_PREMIUM   = 4;

    private function __construct(private readonly mixed $response)
    {
    }

    /**
     * @param mixed $response respons `apiPelangganPemesanan()` (objek, atau null/
     *                        malformed → semua accessor mengembalikan default)
     */
    public static function dariResponse(mixed $response): self
    {
        return new self($response);
    }

    /**
     * Daftar pemesanan (array of object), atau [] bila respons kosong/malformed.
     *
     * @return array<int, object>
     */
    private function pemesanan(): array
    {
        if (
            ! is_object($this->response)
            || ! isset($this->response->body)
            || ! is_object($this->response->body)
            || ! isset($this->response->body->pemesanan)
            || ! is_array($this->response->body->pemesanan)
        ) {
            return [];
        }

        return $this->response->body->pemesanan;
    }

    /**
     * Tingkat (tier) layanan aktif tertinggi: 'siappakai' > 'premium' > 'umum'.
     * Aktif ditentukan oleh `tanggal_akhir >= hari ini`.
     */
    public function tier(): string
    {
        $hasPremium = false;
        $today      = Carbon::today();

        foreach ($this->pemesanan() as $pemesanan) {
            if (! isset($pemesanan->layanan) || ! is_array($pemesanan->layanan)) {
                continue;
            }

            foreach ($pemesanan->layanan as $layanan) {
                if (! isset($layanan->kategori_id) || ! isset($layanan->tanggal_akhir)) {
                    continue;
                }

                try {
                    if (Carbon::parse($layanan->tanggal_akhir)->lt($today)) {
                        continue;
                    }
                } catch (Exception) {
                    continue;
                }

                if ($layanan->kategori_id === self::KATEGORI_SIAPPAKAI) {
                    return 'siappakai';
                }

                if ($layanan->kategori_id === self::KATEGORI_PREMIUM) {
                    $hasPremium = true;
                }
            }
        }

        return $hasPremium ? 'premium' : 'umum';
    }

    /**
     * Apakah suatu fitur (mis. 'anjungan') aktif menurut `tanggal_berlangganan`.
     */
    public function fiturAktif(string $feature): bool
    {
        return is_object($this->response)
            && isset($this->response->body->tanggal_berlangganan->{$feature})
            && $this->response->body->tanggal_berlangganan->{$feature} === 'aktif';
    }

    /**
     * Layanan Hosting yang **paling baru expired** di antara pemesanan aktif,
     * atau `null` bila desa punya SiapPakai aktif (hosting sudah ter-bundle) atau
     * tak ada hosting expired. Hasil: `['layanan', 'pemesanan', 'sisa_hari']`
     * dengan `sisa_hari` bernilai negatif (hari sejak expired).
     *
     * @return array{layanan: object, pemesanan: object, sisa_hari: int|float}|null
     */
    public function hostingExpiredTerbaru(): ?array
    {
        // SiapPakai aktif → hosting lama yang expired tidak relevan dinotifikasi.
        if ($this->adaSiapPakaiAktif()) {
            return null;
        }

        $terbaru         = null;
        $largestSisaHari = -PHP_INT_MAX;

        foreach ($this->pemesanan() as $pemesanan) {
            if (! (isset($pemesanan->status_pemesanan) && $pemesanan->status_pemesanan === 'aktif')) {
                continue;
            }

            if (empty($pemesanan->layanan)) {
                continue;
            }

            foreach ($pemesanan->layanan as $layanan) {
                if (! $this->hostingBerakhirValid($layanan)) {
                    continue;
                }

                try {
                    $sisaHari = Carbon::now()->diffInDays(Carbon::parse($layanan->tanggal_akhir), false);
                } catch (Exception $e) {
                    logger()->error('Error parsing tanggal_akhir for hosting service: ' . $e->getMessage());

                    continue;
                }

                // Hanya yang sudah expired (sisaHari < 0); ambil yang paling baru
                // (sisaHari paling besar / paling mendekati 0).
                if ($sisaHari < 0 && $sisaHari > $largestSisaHari) {
                    $largestSisaHari = $sisaHari;
                    $terbaru         = [
                        'layanan'   => $layanan,
                        'pemesanan' => $pemesanan,
                        'sisa_hari' => $sisaHari,
                    ];
                }
            }
        }

        return $terbaru;
    }

    /**
     * Masa berlaku (hari) langganan: premium via `tanggal_berlangganan.akhir`,
     * selain itu dihitung dari `tgl_akhir` tiap pemesanan. `null` bila tak dapat
     * dihitung (respons kosong/tanpa pemesanan).
     *
     * @return int|float|null
     */
    public function masaBerlaku()
    {
        $tglAkhir = is_object($this->response) ? ($this->response->body->tanggal_berlangganan->akhir ?? null) : null;

        if (empty($tglAkhir)) { // bukan premium
            $pemesanan = $this->pemesanan();

            if ($pemesanan === []) {
                return null;
            }

            $akhir = [];

            foreach ($pemesanan as $item) {
                $akhir[] = $item->tgl_akhir;
            }

            return calculate_date_intervals($akhir);
        }

        return round((strtotime($tglAkhir) - time()) / (60 * 60 * 24));
    }

    private function adaSiapPakaiAktif(): bool
    {
        foreach ($this->pemesanan() as $pemesanan) {
            if (! (isset($pemesanan->status_pemesanan) && $pemesanan->status_pemesanan === 'aktif')) {
                continue;
            }

            if (empty($pemesanan->layanan)) {
                continue;
            }

            foreach ($pemesanan->layanan as $layanan) {
                if (
                    isset($layanan->nama_kategori)
                    && $layanan->nama_kategori === 'Dasbor SiapPakai'
                    && ! empty($layanan->tanggal_akhir)
                    && $layanan->tanggal_akhir !== '9999-12-31'
                    && Carbon::parse($layanan->tanggal_akhir)->isFuture()
                ) {
                    return true;
                }
            }
        }

        return false;
    }

    private function hostingBerakhirValid(object $layanan): bool
    {
        return isset($layanan->nama_kategori)
            && $layanan->nama_kategori === 'Hosting'
            && ! empty($layanan->tanggal_akhir)
            && $layanan->tanggal_akhir !== '9999-12-31';
    }
}
