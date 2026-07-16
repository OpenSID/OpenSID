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

namespace App\Services\Kiosk;

/**
 * Registry titik-ekstensi untuk penyedia sesi "kios/anjungan" Layanan Mandiri.
 *
 * Core TIDAK tahu implementasi kios apa pun. Add-on (mis. modul Anjungan)
 * mendaftarkan {@see KioskProvider}-nya lewat {@see register()} saat boot.
 * Core memanggil {@see resolve()} (data sesi kios saat ini) atau
 * {@see activate()} (aktifkan dari uuid client).
 */
class KioskResolver
{
    /**
     * @var list<KioskProvider>
     */
    private array $providers = [];

    /**
     * Daftarkan penyedia kios. Dipanggil oleh ServiceProvider add-on.
     */
    public function register(KioskProvider $provider): void
    {
        $this->providers[] = $provider;
    }

    /**
     * Kembalikan data sesi kios pertama yang tidak kosong, atau `[]` bila tak ada.
     *
     * @return array<string, mixed>
     */
    public function resolve(): array
    {
        foreach ($this->providers as $provider) {
            $data = $provider->resolve();
            if ($data !== []) {
                return $data;
            }
        }

        return [];
    }

    /**
     * Aktifkan sesi kios dari uuid client via penyedia terdaftar pertama yang
     * mengenalinya. Status {@see KioskActivation::None} bila tak ada yang mengenali.
     *
     * @param KioskActivationMode $mode lihat {@see KioskProvider::activate()}
     */
    public function activate(string $uuid, KioskActivationMode $mode): KioskActivationResult
    {
        foreach ($this->providers as $provider) {
            $result = $provider->activate($uuid, $mode);
            if ($result->status !== KioskActivation::None) {
                return $result;
            }
        }

        return KioskActivationResult::none();
    }

    /**
     * Apakah sesi saat ini sesi kios menurut salah satu penyedia terdaftar?
     */
    public function isActiveSession(): bool
    {
        foreach ($this->providers as $provider) {
            if ($provider->isActiveSession()) {
                return true;
            }
        }

        return false;
    }
}
