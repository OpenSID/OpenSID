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

defined('BASEPATH') || exit('No direct script access allowed');

use App\Events\KapabilitasModulDiperbarui;
use App\Repositories\SettingAplikasiRepository;
use CI_Controller;
use Exception;
use Illuminate\Support\Facades\Log;

class PelangganService
{
    // Konstanta untuk kategori layanan
    public const KATEGORI_SIAPPAKAI = StatusLangganan::KATEGORI_SIAPPAKAI;
    public const KATEGORI_PREMIUM   = StatusLangganan::KATEGORI_PREMIUM;

    /**
     * Ambil status langganan dari api layanan.opendeda.id
     */
    public static function statusLangganan(): ?array
    {
        if (empty($response = self::apiPelangganPemesanan()) || self::isDemoMode()) {
            return null;
        }

        $statusLangganan = StatusLangganan::dariResponse($response);

        // Peringatan hosting expired paling baru (null bila SiapPakai aktif / tak ada).
        if (($hosting = $statusLangganan->hostingExpiredTerbaru()) !== null) {
            $layanan     = $hosting['layanan'];
            $pemesanan   = $hosting['pemesanan'];
            $daysOverdue = abs($hosting['sisa_hari']); // Ubah ke nilai positif

            // Buat pesan peringatan yang detail dan informatif
            $pesan = sprintf(
                'Layanan Hosting %s Anda telah berakhir sejak %d hari yang lalu (Faktur: %s). Hubungi pelaksana layanan untuk informasi biaya. Segera perpanjang untuk menghindari gangguan.',
                $layanan->nama,
                $daysOverdue,
                $pemesanan->faktur
            );

            // Buat link langsung ke halaman perpanjangan layanan
            $link = site_url('pelanggan/perpanjang_layanan?pemesanan_id=' . $pemesanan->id . '&server=' . config_item('server_layanan') . '&invoice=' . $pemesanan->faktur . '&token=' . setting('layanan_opendesa_token'));

            // Return data peringatan
            return [
                'status_key' => 'hosting_expired',
                'warna'      => 'red',
                'ikon'       => 'fa-exclamation-triangle',
                'pesan'      => $pesan,
                'link'       => $link,
            ];
        }

        $masa_berlaku = $statusLangganan->masaBerlaku();

        $status = match (true) {
            $masa_berlaku > 30 => ['status' => 1, 'warna' => 'lightgreen', 'ikon' => 'fa-battery-full'],
            $masa_berlaku > 10 => ['status' => 2, 'warna' => 'orange', 'ikon' => 'fa-battery-half'],
            default            => ['status' => 3, 'warna' => 'pink', 'ikon' => 'fa-battery-empty'],
        };
        $status['masa'] = $masa_berlaku;

        return $status;
    }

    public static function statusPercobaan(): ?array
    {
        $token = setting('layanan_opendesa_token');

        if (empty($token)) {
            return null;
        }

        $jwtPayload = (new CekService())->decodeTokenPayload($token);

        if (empty($jwtPayload->tanggal_berlangganan->percobaan) || $jwtPayload->tanggal_berlangganan->percobaan !== true) {
            return null; // bukan trial
        }

        $akhirPercobaan = $jwtPayload->tanggal_berlangganan->akhir_percobaan ?? null;
        if (empty($akhirPercobaan)) {
            return null;
        }

        $sisaHari = (strtotime($akhirPercobaan) - time()) / (60 * 60 * 24);

        if ($sisaHari < 0) {
            return null; // trial habis
        }

        return [
            'status' => 1,
            'akhir'  => $akhirPercobaan,
            'sisa'   => round($sisaHari),
        ];
    }

    /**
     * Ambil data pemesanan dari api layanan.opendeda.id
     *
     * @return mixed
     */
    public static function apiPelangganPemesanan()
    {
        $ci = get_instance();
        $ci->load->driver(['cache', 'session']);

        if (empty(setting('layanan_opendesa_token'))) {
            app('ci')->session->set_userdata('error_status_langganan', 'Token Pelanggan Kosong.');

            return null;
        }

        if ($cache = app('ci')->cache->file->get('status_langganan')) {
            // set_session('error_status_langganan', 'Tunggu sebentar, halaman akan dimuat ulang.');
            app('ci')->session->set_userdata('error_status_langganan', 'Tunggu sebentar, halaman akan dimuat ulang.');

            return $cache;
        }

        return null;
    }

    public static function perbaruiLangganan()
    {
        $ci = app()->make('ci');

        $perbaharui = $ci->header['perbaharui_langganan'] ?? null && $ci->controller != 'pengguna' && ! config_item('demo_mode');

        if ($perbaharui) {
            try {
                $token = (string) $ci->list_setting->firstWhere('key', 'layanan_opendesa_token')?->value;
                $body  = app(LayananClient::class)->ambilPemesanan($token);

                static::pemesanan($ci, (object) ['body' => $body]);
            } catch (Exception $e) {
                Log::error($e);
            }
        }
    }

    private static function pemesanan(CI_Controller $ci, object $data)
    {
        $ci->load->helper('file');

        $token      = $data->body->token ?? null;
        $desaId     = $data->body->desa_id ?? null;
        $kodeDesa   = kode_wilayah($ci->header['desa']['kode_desa']);
        $configPath = LOKASI_CONFIG_DESA . '/config.php';

        if (empty($token)) {
            logger()->error('Token tidak ditemukan dalam response API layanan. Harap periksa kembali response dari server.');

            return;
        }

        if (! isset($data->body) || empty($data->body)) {
            logger()->error('Response data pemesanan dari API layanan kosong atau tidak valid.');

            return;
        }

        if (self::isDemoMode()) {
            logger()->error('Tidak dapat mengganti token pada website demo.');

            return;
        }

        if ($desaId != $kodeDesa) {
            $namaDesa = ucwords(setting('sebutan_desa') . ' ' . $ci->header['desa']['nama_desa']);
            $server   = config_item('server_layanan');

            logger()->error("{$namaDesa} tidak terdaftar di {$server} atau Token tidak sesuai dengan kode desa.");

            return;
        }

        // Hapus cache lama
        hapus_cache('status_langganan');
        cache()->forget('identitas_desa');

        // Update token di file config
        if (config_item('token_layanan')) {
            $config  = file($configPath);
            $updated = array_map(
                static fn ($line) => stristr($line, 'token_layanan')
                    ? "\$config['token_layanan']  = '{$token}';\n"
                    : $line,
                $config
            );
            file_put_contents($configPath, implode('', $updated));
        }

        // Simpan token ke DB
        (new SettingAplikasiRepository())->updateWithKey('layanan_opendesa_token', $token);

        // Simpan cache baru
        $ci->cache->pakai_cache(static fn () => $data, 'status_langganan', 60 * 60 * 24 * 365 * 30); // 30 tahun (forever)

        // Beri tahu modul yang berkepentingan bahwa langganan baru saja diperbarui.
        // Modul Anjungan (bila terpasang) memasang listener untuk mereaktivasi
        // barisnya sendiri — Pelanggan tak lagi menyentuh model modul lain.
        event(new KapabilitasModulDiperbarui());

        logger()->info('Token berhasil tersimpan.');
    }

    /**
     * Menentukan layanan yang aktif milik desa
     * Pengecekan tertinggi adalah layanan siappakai (kategori_id = 9),
     * kemudian premium (kategori_id = 4), dan jika tidak keduanya maka dianggap umum
     * Status aktif ditentukan berdasarkan tanggal_akhir >= tanggal hari ini
     *
     * @return string 'siappakai', 'premium', atau 'umum'
     */
    public function getLayananAktifTier(): string
    {
        return StatusLangganan::dariResponse(self::apiPelangganPemesanan())->tier();
    }

    private static function isDemoMode(): bool
    {
        // Mode-dev BUKAN mode-demo: 'development' sengaja dikeluarkan agar di
        // pengembangan langganan berperilaku nyata (data dilayani emulator lokal
        // saat "Sumber paket" = bursa lokal). 'testing' dipertahankan agar suite
        // tes tak menembak jaringan. "Demo" kini sinyal eksplisit (config +
        // domain WEBSITE_DEMO), lepas dari ENVIRONMENT.
        return app()->environment('testing')
            || (config_item('demo_mode') && in_array(get_domain(APP_URL), WEBSITE_DEMO));
    }
}
